<template>
    <div class="body">
        <div v-if="!notFound" class="UserViewBody">
            <div v-if="!notFound" class="title_div">
                <p class="title center">Einkaufswagen</p>
            </div>

            <h4 class="notFound" v-if="notFound">Ihr Einkaufswagen ist leer!</h4>

            <div class="list" v-if="!notFound">
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

                        <div v-on:click="entfernen" class="info entfernen">
                            Entfernen
                        </div>
                    </div>
                </b-card>
                <div v-if="platzhalter" class="listitem" style="width: 15em;"></div>
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
                notFound: "",
                reserviert: false,
                platzhalter: false
            }
        },
        mounted() {
            this.$store.commit("UserisInCart");
            this.$store.commit("UserisInCart_2");
            if (this.$store.state.isLoggedIn === false || this.$store.state.isAdmin === true) {
                window.location.href = "/login"
            } else {
                axios.post('/cart/json', {
                    id: this.$store.state.userID
                }).then(response => {
                        console.log(response);
                        if (response.length === 0) {
                            this.notFound = true;
                        } else {
                            if (response.data.length % 2 === 0) {
                                this.platzhalter = false;
                            } else {
                                this.platzhalter = true;
                            }
                            this.notFound = false;
                            this.liste.data.data = response.data;
                            this.isLoggedInCheck();
                            this.saveContent(response.data);
                        }
                    }
                );
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
            checkout: function () {
                axios.get('/cart/checkout')
                    .then(
                        response => {
                            console.log(response);
                            window.location.href = "/list";
                        }
                    )
            },
            entfernen: function () {
                console.log('entfernt');
            }
        }
    }
</script>

<style scoped>

    .center {
        text-align: center;
    }

    .notFound {
        text-align: center;
    }

    .list {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
    }

    .list > * {
        flex-basis: 30%;
        flex-grow: 1;
        flex-shrink: 1;
    }

    .listitem {
        padding: 1em;
        margin: 2em;
    }

    .listitem:hover {
        cursor: pointer;
    }

    .beschreibung {
        font-size: 14px;
        width: 20em;
    }

    .notFound {
        font-size: 3em;
        padding: 1em 0 0 0.6em;
    }

    .body {
        background: linear-gradient(to bottom, rgba(217, 83, 79, 0.9), rgba(211, 211, 211, 0.2));
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

    .title {
        font-size: 3em;
        padding-top: 1em;
    }
</style>