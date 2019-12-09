<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <div>

        <!---------------------------------------------------------

                                User-View

        ---------------------------------------------------------->

        <!---------------------------------------------------------

                                  Search

        ---------------------------------------------------------->

        <h1 class="suche_title">Suche</h1>

        <div class="searchBox">
            <b-input-group class="searchBar">
                <b-input placeholder="Nach Büchern stöbern" type="text" class="search"
                         v-model="search" v-on:keyup.enter="ausgabe()"></b-input>
                <b-input-group-append>
                    <b-button variant="outline-dark" v-if='search != ""' v-on:click='clearSearch()'>
                        <font-awesome-icon icon="times"></font-awesome-icon>
                    </b-button>
                    <b-button variant="outline-dark" v-on:click="ausgabe()">
                        <font-awesome-icon icon="search"></font-awesome-icon>
                    </b-button>
                </b-input-group-append>
            </b-input-group>

            <b-button variant="outline-white">
                <font-awesome-icon icon="filter"></font-awesome-icon>
            </b-button>
        </div>

        <!---------------------------------------------------------

                                View-Body

        ---------------------------------------------------------->

        <div v-if="!notFound" class="UserViewBody">
            <b-button v-if="isAdmin" type="light" variant="danger" class="addButton" pill v-b-modal.AddItem
                      v-on:click="addItem(liste.length)">
                <font-awesome-icon icon="plus"/>
            </b-button>

            <div class="list">
                <b-card v-for="book in liste.data.data" type="light" variant="danger" v-bind:key="book.id"
                        style="width: 15em;"
                        class="listitem"
                        v-on:click="buecherInformationen(book.id, book.title, book.systematik, book.medium, book.content, book.BNR)"
                        v-b-modal.BookInformation>
                    <div class="card_flex">
                        <div class="bildbruh">&#160;</div>
                        <div>
                             <b-card-title>
                                {{book.title}}
                            </b-card-title>

                            <b-card-text class="beschreibung">
                                {{content_short[book.id]}}
                            </b-card-text>
                        </div>

                        <div v-if="book.borrowed === 0" class="info frei">
                            Frei
                        </div>

                        <div v-if="book.borrowed === 1" class="info borrowed">
                            Ausgeborgt
                        </div>

                        <div v-if="reserviert" class="info reserved">
                            Reserviert
                        </div>
                    </div>
                </b-card>
            </div>
        </div>

        <!---------------------------------------------------------

                                NoBooksFound

        ---------------------------------------------------------->

        <h4 class="notFound" v-if="notFound">Leider nichts gefunden! Bitte suchen Sie einen anderen Begriff oder
            versuchen Sie es später noch einmal.</h4>

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
            <b-modal id="AddItem" centered title="Create Book"
                     @ok="saveAdd(title, systematik, medium, content_full, BNR)">
                <b-form-group
                        label="Title"
                        label-for="title"
                        invalid-feedback="Title is required"
                >

                    <b-form-input
                            id="name-input"
                            v-model="title"
                            required
                            v-on:keyup.enter="saveAdd(title, systematik, medium, content_full, BNR)"
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                        label="Systematik"
                        label-for="title"
                        invalid-feedback="Systematik is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="systematik"
                            required
                            v-on:keyup.enter="saveAdd(title, systematik, medium, content_full, BNR)"
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                        label="Medium"
                        label-for="title"
                        invalid-feedback="Medium is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="medium"
                            required
                            v-on:keyup.enter="saveAdd(title, systematik, medium, content_full, BNR)"
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                        label="Content"
                        label-for="title"
                        invalid-feedback="Content is required"
                >
                    <b-form-textarea
                            id="name-input"
                            v-model="content_full"
                            required
                            v-on:keyup.enter="saveAdd(title, systematik, medium, content_full, BNR)"
                    ></b-form-textarea>
                </b-form-group>

                <b-form-group
                        label="BNR"
                        label-for="title"
                        invalid-feedback="BNR is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="BNR"
                            required
                            v-on:keyup.enter="saveAdd(title, systematik, medium, content_full, BNR)"
                    ></b-form-input>
                </b-form-group>
            </b-modal>

            <b-modal id="EditItem" centered title="Edit Book"
                     @ok="saveEdit(id, title, systematik, medium, content_full, BNR)">
                <b-form-group
                        label="Title"
                        label-for="title"
                        invalid-feedback="Title is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="title"
                            required
                            v-on:keyup.enter="saveAdd(title, systematik, medium, content_full, BNR)"
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                        label="Systematik"
                        label-for="title"
                        invalid-feedback="Systematik is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="systematik"
                            required
                            v-on:keyup.enter="saveAdd(title, systematik, medium, content_full, BNR)"
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                        label="Medium"
                        label-for="title"
                        invalid-feedback="Medium is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="medium"
                            required
                            v-on:keyup.enter="saveAdd(title, systematik, medium, content_full, BNR)"
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                        label="Content"
                        label-for="title"
                        invalid-feedback="Content is required"
                >
                    <b-form-textarea
                            id="name-input"
                            v-model="content_full"
                            required
                            v-on:keyup.enter="saveAdd(title, systematik, medium, content_full, BNR)"
                    ></b-form-textarea>
                </b-form-group>

                <b-form-group
                        label="BNR"
                        label-for="title"
                        invalid-feedback="BNR is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="BNR"
                            required
                            v-on:keyup.enter="saveAdd(title, systematik, medium, content_full, BNR)"
                    ></b-form-input>
                </b-form-group>
            </b-modal>

            <b-modal id="BookInformation" centered title="Information">
                <div>
                    {{ content_full }}
                </div>

                <template v-slot:modal-footer="{cancel}">
                    <div v-if="isLoggedIn">
                        <div v-if="isAdmin">
                            <b-button size="sm" variant="success" @click="cancel()">
                                Close
                            </b-button>

                            <b-button pill v-on:click="deleteItem(id)">
                                <font-awesome-icon icon="trash"></font-awesome-icon>
                            </b-button>

                            <b-button v-b-modal.EditItem pill
                                      v-on:click="editItem(id)">
                                <font-awesome-icon icon="pen"></font-awesome-icon>
                            </b-button>

                            <b-button :disabled="!isBorrowed[id]" pill v-on:click="returnBook(id)">
                                <font-awesome-icon icon="level-up-alt" class="fa-rotate-270"></font-awesome-icon>
                            </b-button>
                        </div>

                        <div v-if="!isAdmin">
                            <b-button size="sm" variant="success" @click="cancel()">
                                Close
                            </b-button>

                            <b-button :disabled="isBorrowed[id]" pill v-on:click="putIntoCart(id)">
                                <font-awesome-icon icon="cart-plus"></font-awesome-icon>
                            </b-button>
                        </div>
                    </div>

                    <div v-if="!isLoggedIn">
                        <b-button size="sm" variant="success" @click="cancel()">
                            Close
                        </b-button>
                    </div>
                </template>
            </b-modal>
        </div>

    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "BookList",
        data() {
            return {
                page: "",
                notFound: false,
                isAdmin: this.$store.state.isAdmin,
                isLoggedIn: false,
                isBorrowed: "",
                liste: {
                    data: {
                        data: ""
                    }
                },
                firstPage: 1,
                lastPage: 0,
                id: "",
                title: "",
                title_1: "",
                systematik: "",
                medium: "",
                BNR: "",
                content_full: [],
                content_short: [],
                dialog_title: "",
                search: this.$store.state.search,
                isAnfang: false,
                isEnde: false,
                show: true,
                reserviert: false
            };
        },
        mounted() {
            this.page = this.$store.state.page;
            if (this.$store.state.search === "") {
                axios.get('/books/json?page=' + this.page)
                    .then(response => {
                            if (response.data.data.length === 0) {
                                this.notFound = true;
                                this.isAnfang = true;
                                this.isEnde = true;
                            } else {
                                this.notFound = false;
                                this.liste.data.data = response.data.data;
                                this.lastPage = response.data.last_page;
                                this.isLoggedInCheck();
                                this.saveContent(response.data.data);
                                this.isAnfangfind();
                                this.isEndefind();
                            }
                        }
                    );
            } else {
                axios.post('/books/search?page=' + this.page, {
                    search: this.search
                })
                    .then(response => {
                            if (response.data.data.length === 0) {
                                this.notFound = true;
                                this.isAnfang = true;
                                this.isEnde = true;
                            } else {
                                this.notFound = false;
                                this.liste.data.data = response.data.data;
                                this.lastPage = response.data.last_page;
                                this.$store.state.lastPage = this.lastPage;
                                this.isLoggedInCheck();
                                this.saveContent(response.data.data);
                                this.isAnfangfind();
                                this.isEndefind();
                            }
                        }
                    );
            }
        },
        methods: {
            deleteItem: function (id) {
                axios.post('/books/delete/json/', {
                    id: id
                }).then(response => {
                        this.reloadSite(response.data.status + "")
                    }
                )
            },
            editItem: function (id) {
                this.id = id;
                axios.post('/getBook', {
                    id: id
                }).then(response => {
                        this.title = response.data.title;
                        this.content_full = response.data.content;
                        this.systematik = response.data.systematik;
                        this.medium = response.data.medium;
                        this.BNR = response.data.BNR;
                    }
                );
            },
            addItem: function () {
                this.title = "";
                this.content_full = "";
                this.systematik = "";
                this.medium = "";
                this.BNR = "";
            },
            saveAdd: function (title, systematik, medium, content, BNR) {
                axios.post('/books/create/json/', {
                    title: title,
                    systematik: systematik,
                    medium: medium,
                    content: content,
                    BNR: BNR,
                    authorname: 'Kevin'
                }).then(response => {
                        this.reloadSite(response.data.status + "");
                        this.id = "";
                        this.title = "";
                        this.title_1 = "";
                        this.content_full = "";
                        this.systematik = "";
                        this.medium = "";
                        this.BNR = "";
                    }
                )
            },
            saveEdit: function (id, title, systematik, medium, content, BNR) {
                axios.post('/books/edit/json/', {
                    id: id,
                    title: title,
                    systematik: systematik,
                    medium: medium,
                    content: content,
                    BNR: BNR
                })
                    .then(response => {
                            this.reloadSite(response.data.status + "")
                        }
                    )
            },
            saveContent: function (content) {
                for (let i = 0; i < content.length; i++) {
                    this.content_full[content[i].id] = content[i].content;
                    let content_words = content[i].content.split(" ");
                    if (content_words.length >= 8) {
                        this.content_short[content[i].id] = "";
                        for (let j = 0; j < 8; j++) {
                            this.content_short[content[i].id] += content_words[j] + " ";
                        }
                        this.content_short[content[i].id] += "...";
                    } else {
                        this.content_short[content[i].id] = content[i].content;
                    }
                }
            },
            buecherInformationen: function (id, title, systematik, medium, content, BNR) {
                this.id = id;
                this.content_full = content;
                this.systematik = systematik;
                this.medium = medium;
                this.content = content;
                this.BNR = BNR;

                axios.post('/books/borrowed', {
                    id: id
                }).then(response => {
                        this.isBorrowed = response.data;
                    }
                );
            },
            reloadSite: function (status) {
                if (status === "200") {
                    window.location.reload();
                } else {
                    console.log("error");
                }
            },
            ausgabe: function () {
                this.$store.state.latestSearch = this.search;
                this.$store.commit("setSearch");
                window.location.reload();
            },
            isAnfangfind: function () {
                if (this.$store.state.page === this.firstPage) {
                    this.isAnfang = true;
                }
            },
            isEndefind: function () {
                if (this.$store.state.page === this.lastPage) {
                    this.isEnde = true;
                }
            },
            increment: function () {
                this.$store.commit("increment");
                this.isAnfang = true;
                this.isEnde = true;
                window.location.reload();
            },
            decrement: function () {
                this.$store.commit("decrement");
                this.isAnfang = true;
                this.isEnde = true;
                window.location.reload();
            },
            sendtoFirst: function () {
                this.$store.commit("isFirstPage");
                this.isAnfang = true;
                this.isEnde = true;
                window.location.reload();

            },
            sendtoLast: function () {
                this.$store.state.lastPage = this.lastPage;
                this.$store.commit("isLastPage");
                this.isAnfang = false;
                this.isEnde = true;
                window.location.reload();
            },
            isLoggedInCheck: function () {
                axios.get('/session')
                    .then(response => {
                            this.isLoggedIn = response.data;
                        }
                    )
            },
            returnBook: function (id) {
                axios.post('/returnBooks', {
                    id: id
                }).then(response => {
                        this.reloadSite(response.data.status + "")
                    }
                )
            },
            putIntoCart: function (id) {
                axios.post('/books/borrow', {
                    id: id,
                    userID: this.$store.state.userID
                })
                    .then(response => {
                            console.log(response);
                            window.location.reload();
                        }
                    )
            },
            clearSearch: function () {
                this.search = "";
                this.$store.state.search = "";
                this.ausgabe();
            }
        }
    }

</script>

<style scoped>

    .notFound {
        text-align: center;
    }

    .suche_title {
        text-align: center;
        padding-top: 1em;
    }

    .list {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        padding-top: 4em;
    }

    .list > * {
        flex-basis: 30%;
        flex-grow: 1;
        flex-shrink: 1;
    }

    .listitem {
        margin: 2em;
        padding: 1em;
    }

    .card_flex {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }

    .listitem:hover {
        cursor: pointer;
    }

    .beschreibung {
        font-size: 14px;
        width: 20em;
    }

    .page_buttons {
        text-align: center;
        padding: 2em;
    }

    .addButton {
        float: right;
        margin: 1em;
    }

    .searchBar {
        width: 50em;
        vertical-align: center;
    }

    .searchBox {
        display: flex;
        justify-content: center;
        padding: 2em;
    }

    .frei {
        border: 1px green solid;
        border-radius: 10px;
        color: green;
        width: 3em;
        padding: 0.25em;
        margin: 1em;
        text-align: center;
    }

    .borrowed {
        border: 1px red solid;
        border-radius: 10px;
        color: red;
        width: 6em;
        padding: 0.25em;
        margin: 1em;
        text-align: center;
    }

    .bildbruh {
        background-image: url("../../img/default_cover.jpg");
        width: 125px;
        height: 167px;
    }
</style>