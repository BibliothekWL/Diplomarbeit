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
                    <b-button>
                        <font-awesome-icon icon="filter"></font-awesome-icon>
                    </b-button>
                </b-input-group-append>
            </b-input-group>
        </div>

        <div class="homepage_content">
            <div v-if="topBooks != null" class="books col-12" style="border: black solid 1px">
                <h2>Top-Bücher</h2>
                <div class="list">
                    <div v-for="book in topBooks" class="listitem">
                        <div class="listitem">
                            <div class="card_flex">
                                <div class="bildbruh">&#160;</div>

                                <div class="text">
                                    <div class="book_title">
                                            {{book.id}}
                                    </div>

                                    <div class="beschreibung">
                                            {{book.title}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="newestBooks != null" class="books col-12" style="border: black solid 1px">
                        <h2>Neuerscheinungen</h2>
                <div class="list">
                    <div v-for="book in newestBooks" class="listitem">
                        <div class="listitem">
                            <div class="card_flex">
                                <div class="bildbruh">&#160;</div>

                                <div class="text">
                                    <div class="book_title">
                                        {{book.id}}
                                    </div>

                                    <div class="beschreibung">
                                        {{book.title}}
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
                topBooks: [],
                newestBooks: []
            }
        },
        methods: {
            ausgabe: function () {
                if (this.search !== "") {
                    this.$store.state.latestSearch = this.search;
                    this.$store.commit("setSearch");
                    this.$store.commit("isFirstPage");
                    this.$router.push({path: "/list"});
                }
            },
            topbooks: function () {
                axios.post('/books/newest').then(response =>{
                    console.log(response);
                    this.topBooks = response.data;
                    console.log(this.topBooks.id);
                })
            },
            newestbooks: function () {
                axios.post('/books/top').then(response =>{
                    console.log(response);
                    this.newestBooks = response.data;
                })
            }
        },
        mounted() {
            this.$store.state.warenkorb = false;
            this.$store.state.warenkorbCheckout = false;
            this.search = "";
            this.newestbooks();
            this.topbooks();
        }
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
        height: 300px;
        min-height: 200px;
        width: 100%;
    }
</style>