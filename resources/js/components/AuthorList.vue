<template>
    <div id="body">

        <!---------------------------------------------------------

                                  Search

        ---------------------------------------------------------->

        <div class="searchBox">
            <div class="page_title"><h1 style="color: white; text-shadow: 3px 3px 0px black; padding: 1em">Bibliothek
                Wiener Linien</h1>
            </div>

            <b-input-group class="searchBar">
                <b-input-group-append>
                    <b-button v-show="showalpha" v-on:click="showalphaChange()">
                        <font-awesome-icon icon="sort-alpha-down-alt"></font-awesome-icon>
                    </b-button>

                    <b-button v-show="!showalpha" v-on:click="showalphaChange()">
                        <font-awesome-icon icon="sort-alpha-down"></font-awesome-icon>
                    </b-button>
                </b-input-group-append>

                <b-input class="search" placeholder="Nach Büchern stöbern" type="search" v-model="search"
                         v-on:keyup.enter="ausgabe()">
                </b-input>

                <b-input-group-append>
                    <b-button v-on:click="ausgabe()">
                        <font-awesome-icon icon="search"></font-awesome-icon>
                    </b-button>

                    <b-button v-b-modal.Filter>
                        <font-awesome-icon icon="filter"></font-awesome-icon>
                    </b-button>
                </b-input-group-append>
            </b-input-group>
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
                <div v-for="book in liste.data.data" class="listitem"
                     v-on:click="buecherInformationen(book.id, book.title, book.systematik, book.medium, book.content, book.BNR)"
                     v-b-modal.BookInformation>
                    <div class="card_flex">
                        <div class="bildbruh">&#160;</div>

                        <div class="text">
                            <div class="book_title">
                                {{book.title}}
                            </div>

                            <div class="beschreibung">
                                {{content_short[book.id]}}
                            </div>
                        </div>

                        <div v-if="book.borrowed === 0" class="info frei">
                            Frei
                        </div>

                        <div v-if="book.borrowed === 1" class="info borrowed">
                            Ausgeborgt
                        </div>
                    </div>
                </div>

                <div v-if="platzhalter" class="listitem" style="cursor: auto; border: 0px black solid"></div>
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

            <b-button disabled>{{page}}</b-button>

            <b-button v-on:click="increment()" :disabled=isEnde>
                <font-awesome-icon icon="angle-right"></font-awesome-icon>
            </b-button>
            <b-button v-on:click="sendtoLast()" :disabled=isEnde>
                <font-awesome-icon icon="angle-double-right"></font-awesome-icon>
            </b-button>
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
                titleState: null,
                title_1: "",
                systematik: "",
                medium: "",
                BNR: "",
                autor: "",
                autoren: [],
                content_full: [],
                content_short: [],
                dialog_title: "",
                search: this.$store.state.search,
                isAnfang: false,
                isEnde: false,
                show: true,
                reserviert: false,
                platzhalter: false,
                systematiken: [],
                medien: [],
                showalpha: this.$store.state.showalpha,
                filter_medium: this.$store.state.filter_medium,
                filter_systematik: this.$store.state.filter_systematik,
                key: 0
            };
        },
        mounted() {
            this.ausgabe();
        },
        methods: {
            ausgabe: function () {
                this.$store.state.search = this.search;
                if (this.$store.state.search === "") {
                    axios.post('/author/json?page=' + this.page, {
                        sortDirection: this.showalpha,
                        medium: this.filter_medium,
                        systematik: this.filter_systematik,
                        author: null,
                        isBorrowed: null,
                        isNotBorrowed: null
                    })
                        .then(response => {
                                this.lastPage = response.data.last_page;
                                if (response.data.data.length === 0) {
                                    this.page = 1;
                                    this.notFound = true;
                                    this.isAnfang = true;
                                    this.isEnde = true;
                                } else {
                                    if (response.data.data.length % 2 === 0) {
                                        this.platzhalter = false;
                                    } else {
                                        this.platzhalter = true;
                                    }
                                    if (response.data.data.length < 3) {
                                        console.log(response.data.data.length);
                                        document.getElementById("body").id = "bodyset";
                                    }
                                    this.notFound = false;
                                    this.liste.data.data = response.data.data;
                                    this.lastPage = response.data.last_page;
                                    this.isLoggedInCheck();
                                    this.saveContent(response.data.data);
                                    this.isAnfangfind();
                                    this.isEndefind();
                                    this.getSystematik();
                                    this.getMedium();
                                    this.getAutor();
                                }
                            }
                        );
                } else {
                    axios.post('/books/search?page=' + this.page, {
                        search: this.search,
                        sortDirection: this.showalpha,
                        medium: this.filter_medium,
                        systematik: this.filter_systematik,
                        author: null,
                        isBorrowed: null,
                        isNotBorrowed: null
                    })
                        .then(response => {
                                if (response.data.data.length === 0) {
                                    this.notFound = true;
                                    this.isAnfang = true;
                                    this.isEnde = true;
                                } else {
                                    if (response.data.data.length % 2 === 0) {
                                        this.platzhalter = false;
                                    } else {
                                        this.platzhalter = true;
                                    }
                                    if (response.data.data.length < 3) {
                                        document.getElementById("body").id = "bodyset";
                                    }
                                    this.notFound = false;
                                    this.liste.data.data = response.data.data;
                                    this.lastPage = response.data.last_page;
                                    this.$store.state.lastPage = this.lastPage;
                                    this.isLoggedInCheck();
                                    this.saveContent(response.data.data);
                                    this.isAnfangfind();
                                    this.isEndefind();
                                    this.getSystematik();
                                    this.getMedium();
                                    this.getAutor();
                                }
                            }
                        );
                }
            }
        }
    }
</script>

<style scoped>

</style>