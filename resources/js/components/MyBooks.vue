<template>
    <div id="body">

        <div class="searchBox">
            <div class="page_title">
                <h1 style="color: white; text-shadow: 3px 3px 0 black; padding: 1em">Meine Bücher</h1>
            </div>
        </div>

        <div v-if="!notFound" class="UserViewBody">

            <b-button v-if="isAdmin" type="light" class="addButton" pill v-b-modal.AddItem
                      v-on:click="addItem(liste.length)">
                <font-awesome-icon icon="plus"/>
            </b-button>

            <div class="list">
                <div v-for="book in liste.data.data" class="listitem"
                     v-on:click="buecherInformationen(book.id, book.title, book.systematik, book.medium, book.content,
                     book.BNR, book.author_id)" v-b-modal.BookInformation>
                    <div class="card_flex">
                        <div class="bildbruh">&#160;</div>

                        <div class="text">
                            <div class="book_title">
                                {{title_short[book.id]}}
                            </div>

                            <div class="beschreibung">
                                {{content_short[book.id]}}
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="platzhalter" class="listitem" style="cursor: auto; border: 0 black solid"></div>
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

        <h4 v-if="notFound" class="notFound">
            Sie haben keine Bücher ausgeborgt!
        </h4>

        <b-modal id="BookInformation" size="l" centered title="Information">
            <div>
                {{ content_string }}
            </div>
        </b-modal>
    </div>
</template>

<script>
    import axios from 'axios';
    import GoTop from '@inotom/vue-go-top';

    export default {
        name: "MyBooks",
        components: {
            GoTop
        },
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
                content: [],
                content_short: [],
                content_string: "",
                title_string: "",
                title: [],
                title_short: [],
                platzhalter: false
            }
        },
        mounted() {
            this.$store.state.warenkorb = false;
            this.$store.state.warenkorbCheckout = false;
            this.isLoggedInCheck();
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
                            this.$store.state.isLoggedIn = response.data;
                            if (response.data) {
                                this.$store.commit('UserLoggedIn');
                            } else {
                                this.$store.commit('UsernotLoggedIn');
                            }
                        }
                    )
            },
            saveContent: function (content) {
                for (let i = 0; i < content.length; i++) {
                    this.content[content[i].id] = content[i].content;
                    let content_words = content[i].content.split(" ");
                    if (content_words.length >= 7) {
                        this.content_short[content[i].id] = "";
                        for (let j = 0; j < 7; j++) {
                            this.content_short[content[i].id] += content_words[j] + " ";
                        }
                        this.content_short[content[i].id] += "...";
                    } else {
                        this.content_short[content[i].id] = content[i].content;
                    }
                }
            },
            saveTitle: function (title) {
                for (let i = 0; i < title.length; i++) {
                    this.title[title[i].id] = title[i].title;
                    let title_words = title[i].title.split(" ");
                    if (title_words.length >= 3) {
                        this.title_short[title[i].id] = "";
                        for (let j = 0; j < 3; j++) {
                            this.title_short[title[i].id] += title_words[j] + " ";
                        }
                        this.title_short[title[i].id] += "...";
                    } else {
                        this.title_short[title[i].id] = title[i].title;
                    }
                }
            },
            buecherInformationen: function (id, title, systematik, medium, content, BNR) {
                this.id = id;
                this.content_string = content;
                this.systematik = systematik;
                this.medium = medium;
                this.content = content;
                this.BNR = BNR;
            },
            ausgabe: function () {
                axios.get('/books/mybooks/json')
                    .then(response => {
                            if (response.data.length === 0) {
                                this.notFound = true;
                            } else {
                                this.platzhalter = response.data.length % 2 !== 0;
                                this.notFound = false;
                                this.liste.data.data = response.data;
                                this.saveContent(response.data);
                                this.saveTitle(response.data);
                            }
                        }
                    );
            }
        }
    }
</script>

<style scoped>

    .searchBox {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 2em;
        width: 100%;
        background-image: url('../../img/bg_hp.jpg');
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
        padding: 4em 4em 0 8em;
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

    .addButton {
        float: right;
        margin: 1em;
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
        width: 20em;
        margin: 1.5em;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
    }
</style>