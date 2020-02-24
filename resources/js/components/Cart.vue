<template>
    <div class="body">
        <div class="UserViewBody">
            <div class="searchBox">
                <div class="page_title">
                    <h1 style="color: white; text-shadow: 3px 3px 0px black; padding: 1em">Einkaufswagen</h1>
                </div>
            </div>

            <h4 class="notFound" v-if="notFound">Ihr Einkaufswagen ist leer!</h4>

            <div class="list">
                <div v-for="book in liste.data.data" class="listitem">
                    <div class="card_flex">
                        <div class="bildbruh" v-on:click="buecherInformationen(book.id, book.title, book.systematik, book.medium, book.content, book.BNR)"
                             v-b-modal.BookInformation>&#160;</div>

                        <div class="text" v-on:click="buecherInformationen(book.id, book.title, book.systematik, book.medium, book.content, book.BNR)"
                             v-b-modal.BookInformation>
                            <div class="book_title">
                                {{book.title}}
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
                {{ content_full }}
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
                content_full: [],
                content_short: [],
                notFound: false,
                reserviert: false,
                platzhalter: false
            }
        },
        mounted() {
            this.$store.state.warenkorb = false;
            this.$store.state.warenkorbCheckout = true;
            if(this.$store.state.isAdmin) {
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
                    this.content_full[content[i].id] = content[i].content;
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
                this.content_full = content;
                this.systematik = systematik;
                this.medium = medium;
                this.content = content;
                this.BNR = BNR;
            },
            remove: function (id) {
                axios.post("/cart/destroy/json", {
                    id: id
                }).then(response => {
                    console.log(response);
                })
            },
            ausgabe: function () {
                if (this.$store.state.isLoggedIn === false || this.$store.state.isAdmin === true) {
                    window.location.href = "/login"
                } else {
                    axios.post('/cart/json', {
                        id: this.$store.state.userID
                    }).then(response => {
                            console.log(response);
                            if (response.data.length === 0) {
                                this.notFound = true;
                            } else {
                                this.platzhalter = response.data.length % 2 !== 0;
                                this.notFound = false;
                                this.liste.data.data = response.data;
                                this.isLoggedInCheck();
                                this.saveContent(response.data);
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

    .UserViewBody {
        display: flex;
        flex-direction: column;
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
        cursor: pointer;
    }

    .notFound {
        text-align: center;
        padding-top: 6em;
        font-size: 2em;
    }

    .text {
        height: 7em;
        width: 18em;
        margin: 1.8em;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
    }

    .beschreibung {
        font-size: 14px;
        width: 20em;
    }

    .card_flex {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }

    .entfernen {
        z-index: 1000;
        border: 1px red solid;
        border-radius: 10px;
        color: red;
        width: 5em;
        padding: 0.25em;
        margin: 1em;
        text-align: center;
        cursor: pointer;
    }

    .bildbruh {
        background-image: url("../../img/default_cover.jpg");
        width: 125px;
        height: 167px;
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
</style>