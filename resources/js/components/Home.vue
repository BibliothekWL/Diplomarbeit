<template>
    <div class="body">
        <div class="searchBox">
            <div class="page_title"><h1 style="color: white; text-shadow: 3px 3px 0 black; padding: 1em">Bibliothek
                Wiener Linien</h1>
            </div>

            <b-input-group class="searchBar">
                <b-input class="search" placeholder="Nach Büchern stöbern" type="search"
                         v-model="search" v-on:keyup.enter="ausgabe()"></b-input>
                <b-input-group-append>
                    <b-button v-on:click="ausgabe()">
                        <font-awesome-icon icon="search"></font-awesome-icon>
                    </b-button>
                </b-input-group-append>
            </b-input-group>
        </div>

        <div class="homepage_content">
            <div class="books col-12">
                <h2>Top-Bücher</h2>
                <div class="list">
                    <div class="listitem">
                        <div class="listitem">
                            <div class="card_flex">
                                <div class="bildbruh">&#160;</div>

                                <div class="text">
                                    <div class="book_title">
                                            {{topBooks.title}}
                                    </div>
                                    <!--
                                    <div class="book_author">
                                        {{topBooks.author}}
                                    </div>
                                    -->

                                    <div class="beschreibung">
                                            Bereits {{topBooks.borrowCounter}}-mal ausgeborgt!
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="books col-12">
                <h2>Neuerscheinungen</h2>
                <div class="list">
                    <div class="listitem">
                        <div class="listitem">
                            <div class="card_flex">
                                <div class="bildbruh2">&#160;</div>

                                <div class="text">
                                    <div class="book_title">
                                        {{newestBooks.title}}
                                    </div>

                                    <!--<div class="book_author">
                                        {{newestBooks.author}}
                                    </div>
                                    -->

                                    <div class="beschreibung">
                                        Am {{newestBooks.created_at.split(" ")[0]}} hinzugefügt!
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "Home",
        data() {
            return {
                search: this.$store.state.search,
                topBooks: {},
                newestBooks: {},

            }
        },
        mounted() {
            this.$store.state.warenkorb = false;
            this.$store.state.warenkorbCheckout = false;
            this.search = "";
            this.newestbooks();
            this.topbooks();
        },
        methods: {
            ausgabe: function () {
                if (this.search !== "") {
                    this.$store.state.search = this.search;
                    this.$router.push({path: "/list"});
                }
            },
            topbooks: function () {
                axios.post('/books/top').then(response =>{
                    this.topBooks = response.data;
                })
            },
            newestbooks: function () {
                axios.post('/books/newest').then(response =>{
                    console.log(response.data.created_at);
                    this.newestBooks = response.data;
                })
            }
        },
    }
</script>

<style scoped>
    html {
        overflow: hidden;
    }
    .body {
        display: flex;
        align-items: flex-start;
        justify-content: center;
        background-size: cover;
        flex-wrap: wrap;
        height: calc(100vh);
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

    .homepage_content {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }

    .books{
        height: auto;
        min-height: 200px;
        padding: 1em;
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
        padding-left: 8em;
        padding-right: 4em;
    }

    .list > * {
        flex-basis: 30%;
        flex-grow: 1;
        flex-shrink: 1;
    }

    .listitem {
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

    .bildbruh{
        background-image: url("../../img/bookcover1.jpg");
        width: 125px;
        height: 167px;
        border-radius: 15px;
    }

    .bildbruh2{
        background-image: url("../../img/bookcover2.jpg");
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

</style>