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

                <b-input class="search" placeholder="Nach Büchern suchen" type="search" v-model="search" debounce="20"
                         v-on:keyup="ausgabe()">
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
                      v-on:click="addAuthor(liste.length)" v-b-popover.hover.bottom="''" title="Autor erstellen">
                <font-awesome-icon icon="plus"/>
            </b-button>

            <b-dropdown id="dropdown-dropdown" v-model="item_size" class="BooksCount" :text="item_size"
                        v-b-popover.hover.left="''" title="Bücher pro Seite">
                <b-dropdown-item v-on:click="setItemSize(6)">6</b-dropdown-item>
                <b-dropdown-item v-on:click="setItemSize(12)">12</b-dropdown-item>
                <b-dropdown-item v-on:click="setItemSize(18)">18</b-dropdown-item>
                <b-dropdown-item v-on:click="setItemSize(24)">24</b-dropdown-item>
                <b-dropdown-item v-on:click="setItemSize(30)">30</b-dropdown-item>
            </b-dropdown>

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
                            <b-button pill v-on:click="deleteAuthor(author.id)" v-b-popover.hover.top="''"
                                      title="Autor löschen">
                                <font-awesome-icon icon="trash"></font-awesome-icon>
                            </b-button>

                            <b-button v-b-modal.EditAuthor pill v-on:click="editAuthor(author.name, author.id)"
                                      v-b-popover.hover.top="''" title="Autor bearbeiten">
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
            <div class="paging_buttons">
                <b-button v-on:click="sendtoFirst()" :disabled=isAnfang id="popover-manual-1" title="Erste Seite">
                    <font-awesome-icon icon="angle-double-left"></font-awesome-icon>
                </b-button>

                <b-popover target="popover-manual-1" placement="top" triggers="hover" :show.sync="pop1"
                           title="Erste Seite">
                </b-popover>
            </div>

            <div class="paging_buttons">
                <b-button v-on:click="decrement()" :disabled=isAnfang id="popover-manual-2"
                          title="Eine Seite zurück">
                    <font-awesome-icon icon="angle-left"></font-awesome-icon>
                </b-button>

                <b-popover target="popover-manual-2" placement="top" triggers="hover" :show.sync="pop2"
                           title="Eine Seite zurück">
                </b-popover>
            </div>

            <b-button class="paging_buttons" disabled>{{page}}</b-button>

            <div class="paging_buttons">
                <b-button v-on:click="increment()" :disabled=isEnde id="popover-manual-3"
                          title="Eine Seite weiter">
                    <font-awesome-icon icon="angle-right"></font-awesome-icon>
                </b-button>

                <b-popover target="popover-manual-3" placement="top" triggers="hover" :show.sync="pop3"
                           title="Eine Seite weiter">
                </b-popover>
            </div>

            <div class="paging_buttons">
                <b-button v-on:click="sendtoLast()" :disabled=isEnde id="popover-manual-4" title="Letzte Seite">
                    <font-awesome-icon class="secondary" icon="angle-double-right"></font-awesome-icon>
                </b-button>

                <b-popover target="popover-manual-4" placement="top" triggers="hover" :show.sync="pop4"
                           title="Letzte Seite">
                </b-popover>
            </div>
        </div>


        <!---------------------------------------------------------

                                  NachOben

        ---------------------------------------------------------->

        <go-top
                :size="45"
                :right="30"
                :bottom="30"
                bg-color="#6C747F"
                v-b-popover.hover.left="''"
                title="Nach Oben">
        </go-top>

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
    import Swal from 'sweetalert2';
    import GoTop from '@inotom/vue-go-top';

    export default {
        name: "AuthorList",
        components: {
            GoTop
        },
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
                item_size: '6',
                pop1: false,
                pop2: false,
                pop3: false,
                pop4: false
            };
        },
        mounted() {
            this.isAnfang = true;
            this.isEnde = true;
            this.$store.state.warenkorb = false;
            this.$store.state.warenkorbCheckout = false;
            this.isLoggedInCheck();
            if (!this.$store.state.isAdmin || !this.$store.state.isLoggedIn) {
                this.$router.push({path: '/login'})
            } else {
                this.ausgabe();
            }
        },
        methods: {
            isLoggedInCheck: function () {
                axios.get('/session')
                    .then(response => {
                            this.$store.state.isLoggedIn = response.data;
                            if (response.data) {
                                this.$store.commit('UserLoggedIn');
                            } else {
                                this.$store.commit('UsernotLoggedIn');
                            }
                        }
                    )
            },
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
                    this.page = 1;
                    axios.post('/author/search?page=' + this.page, {
                        search: this.search,
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
                    if (response.data.status === 200) {
                        this.ausgabe();
                        Swal.fire({
                            title: 'Erfolg!',
                            text: 'Der ausgewählte Autor wurde erfolgreich gelöscht!',
                            icon: 'success'
                        });
                    } else {
                        Swal.fire({
                            title: 'Fehler!',
                            text: 'Der ausgewählte Autor konnte nicht gelöscht werden!',
                            icon: 'error'
                        });
                    }
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
                    if (response.data.status === 200) {
                        this.ausgabe();
                        Swal.fire({
                            title: 'Erfolg!',
                            text: 'Der ausgewählte Autor wurde erfolgreich bearbeitet!',
                            icon: 'success'
                        });
                    } else {
                        Swal.fire({
                            title: 'Fehler!',
                            text: 'Der ausgewählte Autor konnte nicht bearbeitet werden!',
                            icon: 'error'
                        });
                    }
                });
            },
            saveAdd: function (name) {
                axios.post('/author/create', {
                    name: name
                }).then(response => {
                    if (response.data.status === 200) {
                        this.ausgabe();
                        Swal.fire({
                            title: 'Erfolg!',
                            text: 'Der ausgewählte Autor wurde erfolgreich erstellt!',
                            icon: 'success'
                        });
                    } else {
                        Swal.fire({
                            title: 'Fehler!',
                            text: 'Der ausgewählte Autor konnte nicht erstellt werden!',
                            icon: 'error'
                        });
                    }
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

    .page_buttons {
        width: 100%;
        display: flex;
        justify-content: center;
        text-align: center;
        padding: 2em;
        color: white;
    }

    .paging_buttons {
        margin: .1em;
    }

    .buttons {
        display: flex;
        flex-direction: row;
        width: 10em;
        justify-content: space-between;
    }

    .BooksCount {
        position: absolute;
        right: 11em;
        top: 16em;
    }
</style>