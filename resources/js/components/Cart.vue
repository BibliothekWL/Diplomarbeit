<template>
    <div>
        <div class="list">
            <b-card v-for="book in liste.data.data" type="light" variant="danger" v-bind:key="book.id"
                    img-left
                    img-alt="Image"
                    style="width: 15em;" class="listitem"
                    v-on:click="buecherInformationen(book.id, book.title, book.systematik, book.medium, book.content, book.BNR)"
                    v-b-modal.BookInformation>
                <b-card-title>
                    {{book.title}}
                </b-card-title>
                <b-card-text class="beschreibung">
                    {{content_short[book.id]}}
                </b-card-text>
            </b-card>
        </div>

        <h4 class="notFound" v-if="notFound">Leider nichts gefunden! Sie haben noch nichts ausgeborgt!</h4>

        <b-button v-if="!notFound" class="center" v-on:click="checkout()">
            Ausborgen
        </b-button>
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
                content_short: [],
                notFound: ""
            }
        },
        mounted() {
            if (this.$store.state.isLoggedIn === false || this.$store.state.isAdmin === true) {
                window.location.href = "/login"
            } else {
                axios.post('/cart/json', {
                    id: this.$store.state.userID
                })
                    .then(response => {
                            console.log(response.data.data);
                            if (response.length === 0) {
                                this.notFound = true;
                            } else {
                                this.notFound = false;
                                this.liste.data.data = response.data.data;
                                this.isLoggedInCheck();
                                this.saveContent(response);
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
                    if (content_words.length >= 12) {
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
                        }
                    )
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
        padding: 1em;
        margin: 2em;
    }

    .listitem:hover {
        cursor: pointer;
    }

    .beschreibung {
        font-size: 12px;
    }

    .notFound {
        padding: 2em;
    }
</style>