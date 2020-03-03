<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <div id="body">

        <!---------------------------------------------------------

                                  Search

        ---------------------------------------------------------->

        <div class="searchBox">
            <div class="page_title"><h1 style="color: white; text-shadow: 3px 3px 0 black; padding: 1em">
                Autorenliste</h1>
            </div>

            <b-input-group class="searchBar">

                <b-input class="search" placeholder="Nach Autoren suchen" type="search" v-model="search"
                         v-on:keyup.enter="ausgabe()">
                </b-input>

                <b-input-group-append>
                    <b-button v-on:click="ausgabe()">
                        <font-awesome-icon icon="search"></font-awesome-icon>
                    </b-button>
                </b-input-group-append>
            </b-input-group>
        </div>

        <!---------------------------------------------------------

                                View-Body

        ---------------------------------------------------------->

        <div v-if="!notFound" class="UserViewBody">
            <b-button v-if="isAdmin" type="light" class="addButton" pill v-b-modal.AddAuthor
                      v-on:click="addAuthor(liste.length)">
                <font-awesome-icon icon="plus"/>
            </b-button>

            <div class="list">
                <div v-for="author in liste.data.data" class="listitem">
                    <div class="card_flex">
                        <div class="bildbruh">&#160;</div>

                        <div class="text">
                            <div class="book_title">
                                {{author.name}}
                            </div>
                        </div>

                        <div class="buttons">
                            <b-button pill v-on:click="deleteAuthor(author.id)">
                                <font-awesome-icon icon="trash"></font-awesome-icon>
                            </b-button>

                            <b-button v-b-modal.EditAuthor pill
                                      v-on:click="editAuthor(author.name, author.id)">
                                <font-awesome-icon icon="pen"></font-awesome-icon>
                            </b-button>
                        </div>
                    </div>
                </div>

                <div v-if="platzhalter" class="listitem" style="cursor: auto; border: 0 black solid"></div>
            </div>
        </div>

        <!---------------------------------------------------------

                                NoBooksFound

        ---------------------------------------------------------->

        <h4 v-if="notFound" class="notFound">
            Leider nichts gefunden! Bitte suchen Sie einen anderen Begriff oder
            versuchen Sie es später noch einmal.
        </h4>

        <!---------------------------------------------------------

                                Pagingbuttons

        ---------------------------------------------------------->

        <div class="page_buttons">
            <b-button v-on:click="sendtoFirst()" :disabled=isAnfang>
                <font-awesome-icon icon="angle-double-left"></font-awesome-icon>
            </b-button>
            <b-button v-on:click="decrement()" :disabled=isAnfang>
                <font-awesome-icon icon="angle-left"></font-awesome-icon>
            </b-button>

            <b-dropdown id="dropdown-dropup" v-model="item_size" dropup :text="item_size">
                <b-dropdown-item v-on:click="setItemSize(6)">6</b-dropdown-item>
                <b-dropdown-item v-on:click="setItemSize(12)">12</b-dropdown-item>
                <b-dropdown-item v-on:click="setItemSize(18)">18</b-dropdown-item>
                <b-dropdown-item v-on:click="setItemSize(24)">24</b-dropdown-item>
                <b-dropdown-item v-on:click="setItemSize(30)">30</b-dropdown-item>
            </b-dropdown>

            <b-button disabled>{{page}}</b-button>

            <b-button v-on:click="increment()" :disabled=isEnde>
                <font-awesome-icon icon="angle-right"></font-awesome-icon>
            </b-button>
            <b-button v-on:click="sendtoLast()" :disabled=isEnde>
                <font-awesome-icon icon="angle-double-right"></font-awesome-icon>
            </b-button>
        </div>

        <!---------------------------------------------------------

                                   Modals

        ---------------------------------------------------------->
        <div>

            <!---------------------------------------------------------

                                      AddAuthor

            ---------------------------------------------------------->

            <b-modal id="AddAuthor" scrollable ref="modal" centered title="Autor erstellen"
                     @ok="saveAdd(name)">
                <form ref="form">
                    <b-form-group
                            label="Name"
                            label-for="title"
                            invalid-feedback="Name is required"
                    >

                        <b-form-input
                                id="name-input"
                                v-model="name"
                                required
                                v-on:keyup.enter="saveAdd(name)"
                        ></b-form-input>
                    </b-form-group>
                </form>
            </b-modal>

            <!---------------------------------------------------------

                                    EditAuthor

            ---------------------------------------------------------->

            <b-modal id="EditAuthor" centered title="Autor ändern"
                     @ok="saveEdit(name)">
                <form ref="form">
                    <b-form-group
                            label="Name"
                            label-for="title"
                            invalid-feedback="Name is required"
                    >

                        <b-form-input
                                id="name-input"
                                v-model="name"
                                required
                                v-on:keyup.enter="saveEdit(name)"
                        ></b-form-input>
                    </b-form-group>
                </form>
            </b-modal>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "AuthorList",
        data() {
            return {
                page: 1,
                notFound: false,
                isAdmin: this.$store.state.isAdmin,
                isLoggedIn: this.$store.state.isLoggedIn,
                liste: {
                    data: {
                        data: ""
                    }
                },
                firstPage: 1,
                lastPage: 0,
                autor: "",
                search: "",
                isAnfang: false,
                isEnde: false,
                platzhalter: false,
                name: "",
                id: null,
                item_size: 6
            };
        },
        mounted() {
            this.isAnfang = true;
            this.isEnde = true;
            this.$store.state.warenkorb = false;
            this.$store.state.warenkorbCheckout = false;
            if (!this.$store.state.isAdmin || !this.$store.state.isLoggedIn) {
                this.$router.push({path: '/login'})
            } else {
                this.ausgabe();
            }
        },
        methods: {
            ausgabe: function () {
                if (this.search === "") {
                    axios.post('/author/json?page=' + this.page, {
                        item_size: this.item_size
                    })
                        .then(response => {
                                if (response.data.length === 0) {
                                    this.notFound = true;
                                } else {
                                    this.notFound = false;
                                    this.platzhalter = response.data.data.length % 2 !== 0;
                                    this.liste.data.data = response.data.data;
                                    this.lastPage = response.data.last_page;
                                    this.isAnfangfind();
                                    this.isEndefind();
                                }
                            }
                        );
                } else {
                    axios.post('/author/search?page=' + this.page, {
                        search: this.search,
                        item_size: this.item_size
                    })
                        .then(response => {
                                if (response.data.length !== 0) {
                                    this.notFound = true;
                                } else {
                                    this.notFound = false;
                                    this.platzhalter = response.data.data.length % 2 !== 0;
                                    this.liste.data.data = response.data.data;
                                    this.lastPage = response.data.last_page;
                                    this.isAnfangfind();
                                    this.isEndefind();
                                }
                            }
                        );
                }
            },
            isAnfangfind: function () {
                this.isAnfang = this.page === 1;
            },
            isEndefind: function () {
                this.isEnde = this.page === this.lastPage;
            },
            increment: function () {
                this.isAnfang = true;
                this.isEnde = true;
                this.page++;
                this.ausgabe();
            },
            decrement: function () {
                this.isAnfang = true;
                this.isEnde = true;
                this.page--;
                this.ausgabe();
            },
            sendtoFirst: function () {
                this.isAnfang = true;
                this.isEnde = true;
                this.page = 1;
                this.ausgabe();
            },
            sendtoLast: function () {
                this.isAnfang = true;
                this.isEnde = true;
                this.page = this.lastPage;
                this.ausgabe();
            },
            deleteAuthor: function (id) {
                axios.post('/author/delete', {
                    id: id
                }).then(response => {
                    this.ausgabe();
                });
            },
            editAuthor: function (name, id) {
                this.name = name;
                this.id = id;
            },
            addAuthor: function () {
                this.name = "";
            },
            saveEdit: function (name) {
                axios.post('/author/edit', {
                    name: name,
                    id: this.id
                }).then(response => {
                    this.ausgabe();
                });
            },
            saveAdd: function (name) {
                axios.post('/author/create', {
                    name: name
                }).then(response => {
                    console.log(response);
                });
            },
            setItemSize: function (size) {
                this.item_size = size;
                this.page = 1;
                this.ausgabe();
            }
        }
    }
</script>

<style scoped>
    .notFound {
        text-align: center;
        padding: 8em;
    }

    .list {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: right;
        padding-top: 4em;
        padding-left: 8em;
        padding-right: 4em;
    }

    .list > * {
        flex-basis: 30%;
        flex-grow: 1;
        flex-shrink: 1;
    }

    .listitem {
        margin: 2em;
        border: 1px black solid;
    }

    .card_flex {
        display: flex;
        flex-direction: row;
        align-items: center;
    }

    .page_buttons {
        text-align: center;
        padding: 2em;
        color: white;
    }

    .addButton {
        float: right;
        margin: 1em;
    }

    .searchBar {
        width: 50em;
        vertical-align: top;
    }

    .searchBox {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 2em;
        width: 100%;
        background-image: url('../../img/bg_hp.jpg');
    }

    .bildbruh {
        background-image: url("../../img/Author.png");
        width: 220px;
        height: 167px;
    }

    .book_title {
        font-family: "Nunito", sans-serif;
        font-size: 1.2em;
        font-weight: bold;
    }

    .text {
        display: flex;
        height: 7em;
        margin: 1.5em;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    .buttons {
        display: flex;
        flex-direction: row;
        width: 10em;
        justify-content: space-between;
    }
</style>