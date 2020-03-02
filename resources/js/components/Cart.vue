<template>
    <div class="body">
        <div class="UserViewBody">
            <div class="searchBox">
                <div class="page_title">
                    <h1 style="color: white; text-shadow: 3px 3px 0px black; padding: 1em">Einkaufswagen</h1>
                </div>
            </div>

            <h4 class="notFound" v-if="notFound">Ihr Einkaufswagen ist leer!</h4>

            <div class="list" v-if="!notFound">
                <div v-for="book in liste.data.data" class="listitem">
                    <div class="card_flex">
                        <div class="bildbruh"
                             v-on:click="buecherInformationen(book.id, book.title, book.systematik, book.medium, book.content, book.BNR)"
                             v-b-modal.BookInformation>&#160;
                        </div>

                        <div class="text"
                             v-on:click="buecherInformationen(book.id, book.title, book.systematik, book.medium, book.content, book.BNR)"
                             v-b-modal.BookInformation>
                            <div class="book_title">
                                {{title_short[book.id]}}
                            </div>

                            <div class="beschreibung">
                                {{content_short[book.id]}}
                            </div>
                        </div>

                        <div class="info entfernen" v-on:click="remove(book.id)">
                            Entfernen
                        </div>
                    </div>
                </div>

                <div v-if="platzhalter" class="listitem" style="cursor: auto; border: 0 black solid"></div>
            </div>
        </div>

        <b-modal id="BookInformation" size="l" centered title="Information">
            <div>
                {{ content }}
            </div>
        </b-modal>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "Cart",
        data() {
            return {
                isAdmin: this.$store.state.isAdmin,
                isLoggedIn: false,
                liste: {
                    data: {
                        data: ""
                    }
                },
                content: [],
                content_short: [],
                notFound: false,
                reserviert: false,
                platzhalter: false,
                title: [],
                title_short: []
            }
        },
        mounted() {
            this.$store.state.warenkorb = false;
            this.$store.state.warenkorbCheckout = true;
            if (this.$store.state.isAdmin) {
                this.$router.push({path: '/login'})
            } else {
                this.ausgabe();
            }
        },
        methods: {
            isLoggedInCheck: function () {
                axios.get('/session')
                    .then(response => {
                            this.isLoggedIn = response.data;
                        }
                    )
            },
            saveContent: function (content) {
                for (let i = 0; i < content.length; i++) {
                    this.content[content[i].id] = content[i].content;
                    let content_words = content[i].content.split(" ");
                    if (content_words.length >= 10) {
                        this.content_short[content[i].id] = "";
                        for (let j = 0; j < 10; j++) {
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
                this.content = content;
                this.systematik = systematik;
                this.medium = medium;
                this.content = content;
                this.BNR = BNR;
            },
            remove: function (id) {
                axios.post("/cart/delete", {
                    id: id
                }).then(response => {
                    if (response.data.status === 200) {
                        this.$store.state.latestCartCount--;
                        this.$store.commit('setCartCount');
                        this.ausgabe();
                    } else {
                        console.log("error");
                    }
                })
            },
            saveTitle: function (title) {
                console.log(title.length);
                for (let i = 0; i < title.length; i++) {
                    this.title[title[i].id] = title[i].title;
                    let title_words = title[i].title.split(" ");
                    if (title_words.length >= 8) {
                        this.title_short[title[i].id] = "";
                        for (let j = 0; j < 8; j++) {
                            this.title_short[title[i].id] += title_words[j] + " ";
                        }
                        this.title_short[title[i].id] += "...";
                    } else {
                        this.title_short[title[i].id] = title[i].title;
                    }
                }
            },
            ausgabe: function () {
                if (this.$store.state.isLoggedIn === false || this.$store.state.isAdmin === true) {
                    window.location.href = "/login"
                } else {
                    axios.post('/cart/json', {
                        id: this.$store.state.userdata.id
                    }).then(response => {
                            if (response.data.length === 0) {
                                this.notFound = true;
                            } else {
                                this.platzhalter = response.data.length % 2 !== 0;
                                this.notFound = false;
                                this.liste.data.data = response.data;
                                this.isLoggedInCheck();
                                this.saveContent(response.data);
                                this.saveTitle(response.data);
                            }
                        }
                    );
                }
            }
        }
    }
</script>

<style scoped>
    html {
        overflow: hidden;
    }

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
        border-radius: 15px;
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

    .entfernen {
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
        border-radius: 15px;
    }

    .book_title {
        font-family: "Nunito", sans-serif;
        font-size: 1.2em;
        font-weight: bold;
    }

    .text {
        height: 7em;
        width: 14em;
        margin: 1.5em;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
    }

    .btn {
        background-color: rgb(30, 30, 133);
        border-color: rgb(30, 30, 133);
    }

    .btn-secondary {
        background-color: rgb(30, 30, 133);
        border-color: rgb(30, 30, 133);
    }
</style>