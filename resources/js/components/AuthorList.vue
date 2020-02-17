<template>
    <div id="body">

        <!---------------------------------------------------------

                                  Search

        ---------------------------------------------------------->

        <div class="searchBox">
            <div class="page_title"><h1 style="color: white; text-shadow: 3px 3px 0px black; padding: 1em">
                Autorenliste</h1>
            </div>

            <b-input-group class="searchBar">

                <b-input class="search" placeholder="Nach Büchern stöbern" type="search" v-model="search"
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
            <b-button v-if="isAdmin" type="light" class="addButton" pill v-b-modal.AddItem
                      v-on:click="addItem(liste.length)">
                <font-awesome-icon icon="plus"/>
            </b-button>

            <div class="list">
                <div v-for="author in liste.data.data" class="listitem">
                    <div class="card_flex">
                        <div class="bildbruh">&#160;</div>

                        <div class="text">
                            <div class="book_title">
                                {{author.firstname}}
                                {{author.surname}}
                            </div>
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
                platzhalter: false
            };
        },
        mounted() {
            this.ausgabe();
        },
        methods: {
            ausgabe: function () {
                if (this.search === "") {
                    axios.get('/author/json?page=' + this.page)
                        .then(response => {
                                this.lastPage = response.data.last_page;
                                if (response.data.data.length % 2 === 0) {
                                    this.platzhalter = false;
                                } else {
                                    this.platzhalter = true;
                                }
                                if (response.data.data.length < 3) {
                                    document.getElementById("body").id = "bodyset";
                                }
                                this.liste.data.data = response.data.data;
                                this.lastPage = response.data.last_page;
                                this.isAnfangfind();
                                this.isEndefind();
                            }
                        );
                } else {
                    axios.post('/author/search?page=' + this.page, {
                        search: this.search
                    })
                        .then(response => {
                                if (response.data.data.length % 2 === 0) {
                                    this.platzhalter = false;
                                } else {
                                    this.platzhalter = true;
                                }
                                if (response.data.data.length < 3) {
                                    document.getElementById("body").id = "bodyset";
                                }
                                this.liste.data.data = response.data.data;
                                this.lastPage = response.data.last_page;
                                this.isAnfangfind();
                                this.isEndefind();
                            }
                        );
                }
            },
            isAnfangfind: function () {
                if (this.page === 1) {
                    this.isAnfang = true;
                } else {
                    this.isAnfang = false;
                }
            },
            isEndefind: function () {
                if (this.page === this.lastPage) {
                    this.isEnde = true;
                } else {
                    this.isEnde = false;
                }
            },
            increment: function () {
                this.page++;
                this.ausgabe();
            },
            decrement: function () {
                this.page--;
                this.ausgabe();
            },
            sendtoFirst: function () {
                this.page = 1;
                this.ausgabe();
            },
            sendtoLast: function () {
                this.page = this.lastPage;
                this.ausgabe();
            },
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

    .listitem:hover {
        cursor: pointer;
    }

    .beschreibung {
        font-size: 14px;
        width: 13em;
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
        background-image: url("../../img/Author.png");
        width: 200px;
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
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    .btn {
        background-color: midnightblue;
    }
</style>