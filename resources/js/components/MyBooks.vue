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
                <div v-for="book in liste.data.data" class="listitem" v-on:click="buecherInformationen(book.id, book.title, book.systematik, book.medium, book.content,
                    book.BNR, book.author_id)"
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
                    </div>
                </div>

                <div v-if="platzhalter" class="listitem" style="cursor: auto; border: 0 black solid"></div>
            </div>
        </div>

        <h4 v-if="notFound" class="notFound">
            Sie haben keine Bücher ausgeborgt!
        </h4>

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
                content: [],
                content_short: [],
                platzhalter: false
            }
        },
        mounted() {
            this.$store.state.warenkorb = false;
            this.$store.state.warenkorbCheckout = false;
            if (this.$store.state.isAdmin) {
                this.$router.push({path: '/login'})
            } else {
                this.ausgabe();
            }
        },
        methods: {
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
                this.content_full = content;
                this.systematik = systematik;
                this.medium = medium;
                this.content = content;
                this.BNR = BNR;
            },
            ausgabe: function () {
                axios.get('/books/mybooks/json')
                    .then(response => {
                            if (response.data.data.length === 0) {
                                this.notFound = true;
                            } else {
                                this.platzhalter = response.data.data.length % 2 !== 0;
                                this.notFound = false;
                                this.liste.data.data = response.data.data;
                                this.saveContent(response.data.data);
                            }
                        }
                    );
            }
        }
    }
</script>

<style scoped>

    .notFound {
        text-align: center;
        padding: 6em;
        font-size: 2em;
    }

    .list {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: right;
        padding-top: 6em;
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
        justify-content: space-between;
    }

    .listitem:hover {
        cursor: pointer;
    }

    .beschreibung {
        font-size: 14px;
        width: 17em;
    }

    .addButton {
        float: right;
        margin: 1em;
    }

    .bildbruh {
        background-image: url("../../img/default_cover.jpg");
        width: 125px;
        height: 167px;
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