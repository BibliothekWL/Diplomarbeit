<template>
    <div class="body">
        <div class="UserViewBody">
            <div class="searchBox">
                <div class="page_title">
                    <h1 style="color: white; text-shadow: 3px 3px 0 black; padding: 1em">Einkaufswagen</h1>
                </div>
            </div>

            <h4 class="notFound" v-if="notFound">Ihr Einkaufswagen ist leer!</h4>

            <div class="list" v-if="!notFound">
                <b-button class="warenkorb_checkout" v-if="isLoggedIn & !isAdmin" v-on:click="checkout()"
                          v-b-popover.hover.left="''" title="Alle Bücher ausborgen">
                    <font-awesome-icon icon="cart-arrow-down"></font-awesome-icon>
                </b-button>

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

                        <div class="info entfernen" v-on:click="remove(book.id)" v-b-popover.hover.top="''"
                             title="Buch entfernen">
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

    </div>
</template>

<script>
    import axios from 'axios';
    import Swal from 'sweetalert2';
    import GoTop from '@inotom/vue-go-top';

    export default {
        name: "Cart",
        components: {
            GoTop
        },
        data() {
            return {
                isAdmin: this.$store.state.isAdmin,
                isLoggedIn: this.$store.state.isLoggedIn,
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
                        Swal.fire({
                            title: 'Erfolg!',
                            text: 'Das Buch befindet sich nun nicht mehr im Warenkorb!',
                            icon: 'success'
                        });
                    } else {
                        Swal.fire({
                            title: 'Fehler!',
                            text: 'Das Buch konnte nicht entfernt werden!',
                            icon: 'error'
                        });
                    }
                })
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
            ausgabe: function () {
                this.isLoggedInCheck();
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
                                this.saveContent(response.data);
                                this.saveTitle(response.data);
                            }
                        }
                    );
                }
            },
            checkout: function () {
                axios.get('/cart/checkout')
                    .then(
                        response => {
                            if (response.data.status === 200) {
                                this.$store.state.latestCartCount--;
                                this.$store.commit('setCartCount');
                                this.$store.state.latestCartCount = 0;
                                this.$store.commit('setCartCount');
                                this.ausgabe();
                                Swal.fire({
                                    title: 'Erfolg!',
                                    text: 'Die Bücher wurden erfolgreich ausgeborgt!',
                                    icon: 'success'
                                });
                                this.$router.push({path: '/list'});
                            } else {
                                Swal.fire({
                                    title: 'Fehler!',
                                    text: 'Die Bücher konnten nicht ausgeborgt werden!',
                                    icon: 'error'
                                });
                            }
                        }
                    )
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

    .warenkorb_checkout {
        position: fixed;
        z-index: 1000;
        top: 10em;
        right: 1.3em;
        margin: 0.8em;
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
</style>