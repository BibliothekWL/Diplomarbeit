<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <div>
        <div v-if="!notFound">

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
        </div>

        <h4 class="notFound" v-if="notFound">Leider nichts gefunden! Sie haben noch nichts reserviert!</h4>

        <b-modal id="BookInformation" centered title="Information">
            <div>
                {{ content_full }}
            </div>

            <template v-slot:modal-footer="{cancel}">
                <div>
                    <b-button size="sm" variant="success" @click="cancel()">
                        Close
                    </b-button>
                </div>
            </template>
        </b-modal>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "MyBooks",
        data() {
            return {
                notFound: "",
                liste: {
                    data: {
                        data: ""
                    }
                },
                isAdmin: this.$store.state.isAdmin,
                isLoggedIn: false,
                content_full: [],
                content_short: [],
            }
        },
        mounted() {
            if (this.$store.state.isLoggedIn === false || this.$store.state.isAdmin === true) {
                window.location.href = "/login"
            } else {
                axios.get('/books/mybooks/json')
                    .then(response => {
                            console.log(response);
                            if (response.data.data.length === 0) {
                                this.notFound = true;
                            } else {
                                this.notFound = false;
                                this.liste.data.data = response.data.data;
                                this.isLoggedInCheck();
                                this.saveContent(response.data.data);
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

        }
    }
</script>

<style scoped>
    .notFound {
        text-align: center;
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
</style>