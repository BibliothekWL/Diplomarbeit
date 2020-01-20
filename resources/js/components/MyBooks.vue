<template>
    <div class="body">
        <div class="UserViewBody">
            <div v-if="!notFound" class="title_div">
                <p class="title center">Meine Bücher</p>
            </div>

            <h6 class="notFound" v-if="notFound">Sie haben noch keine Bücher ausgeborgt!</h6>

            <div class="list" v-if="!notFound">
                <b-card v-for="book in liste.data.data" type="light" variant="danger" v-bind:key="book.id"
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
                platzhalter: false
            }
        },
        mounted() {
            this.$store.commit("UserisInCart_2");
            this.$store.commit("UserisNotInCart_2");
            if (this.$store.state.isLoggedIn === false || this.$store.state.isAdmin === true) {
                window.location.href = "/login"
            } else {
                axios.get('/books/mybooks/json')
                    .then(response => {
                            console.log(response);
                            if (response.data.data.length === 0) {
                                this.notFound = true;
                            } else {
                                if (response.data.data.length % 2 === 0) {
                                    this.platzhalter = false;
                                } else {
                                    this.platzhalter = true;
                                }
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
        padding-left: 4em;
    }

    .listitem {
        padding: 1em;
        margin: 2em;
    }

    .list > * {
        flex-basis: 30%;
        flex-grow: 1;
        flex-shrink: 1;
    }

    .listitem:hover {
        cursor: pointer;
    }

    .beschreibung {
        font-size: 14px;
        width: 20em;
    }

    .body {
        background: linear-gradient(to bottom, rgba(227, 0, 19, 0.9), rgba(244, 244, 244, 1));
    }

    .card_flex {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-around;
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

    .notFound {
        font-size: 2em;
        padding: 10.5em;
    }
</style>