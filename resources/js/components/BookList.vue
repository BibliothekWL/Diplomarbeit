<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <div>

        <div v-if="isLoggedIn">

            <!---------------------------------------------------------

                                    Admin-View

            ---------------------------------------------------------->

            <div v-if="isAdmin">
                <h1 class="suche_title">Suche</h1>

                <b-input-group>
                    <b-input-group-append>
                        <b-button variant="outline-dark" disabled>
                            <font-awesome-icon icon="search"></font-awesome-icon>
                        </b-button>
                    </b-input-group-append>
                    <b-input placeholder="Nach Büchern stöbern" type="text" class="search" v-on="ausgabe()"
                             v-model="search"></b-input>
                </b-input-group>

                <div class="list">
                    <b-card v-for="book in liste.data.data" img-left
                            img-alt="Image"
                            style="width: 15em;" class="listitem"
                            v-on:click="buecherInformationen(book.content, book.id)" v-b-modal.BookInformationAdmin>
                        <b-card-title>
                            {{book.title}}
                        </b-card-title>
                        <b-card-text class="beschreibung">
                            {{content_short[book.id]}}
                        </b-card-text>
                    </b-card>
                </div>

                <b-button pill v-b-modal.AddItem v-on:click="addItem(liste.length)">
                    <font-awesome-icon icon="plus"/>
                </b-button>

                <div class="page_buttons">
                    <b-button v-on:click="sendtoFirst()" :disabled=isAnfang><<</b-button>
                    <b-button v-on:click="decrement()" :disabled=isAnfang><</b-button>

                    <b-button disabled>{{page}}</b-button>

                    <b-button v-on:click="increment()" :disabled=isEnde>></b-button>
                    <b-button v-on:click="sendtoLast()" :disabled=isEnde>>></b-button>
                </div>
            </div>

            <!---------------------------------------------------------

                                    User-View

            ---------------------------------------------------------->

            <div v-else>
                <h1 class="suche_title">Suche</h1>

                <b-input-group>
                    <b-input-group-append>
                        <b-button variant="outline-dark" disabled>
                            <font-awesome-icon icon="search"></font-awesome-icon>
                        </b-button>
                    </b-input-group-append>
                    <b-input placeholder="Nach Büchern stöbern" type="text" class="search" v-on="ausgabe()"
                             v-model="search"></b-input>
                </b-input-group>

                <div class="list">
                    <b-card v-for="book in liste.data.data" img-left
                            img-alt="Image"
                            style="width: 15em;" class="listitem"
                            v-on:click="buecherInformationen(book.content, book.id)" v-b-modal.BookInformationUser>
                        <b-card-title>
                            {{book.title}}
                        </b-card-title>
                        <b-card-text class="beschreibung">
                            {{content_short[book.id]}}
                        </b-card-text>
                    </b-card>
                </div>

                <div class="page_buttons">
                    <b-button v-on:click="sendtoFirst()" :disabled=isAnfang><<</b-button>
                    <b-button v-on:click="decrement()" :disabled=isAnfang><</b-button>

                    <b-button disabled>{{page}}</b-button>

                    <b-button v-on:click="increment()" :disabled=isEnde>></b-button>
                    <b-button v-on:click="sendtoLast()" :disabled=isEnde>>></b-button>
                </div>
            </div>

        </div>

        <!---------------------------------------------------------

                            NotLoggedIn-View

        ---------------------------------------------------------->

        <div v-else>

            <h1 class="suche_title">Suche</h1>

            <b-input-group>
                <b-input-group-append>
                    <b-button variant="outline-dark" disabled>
                        <font-awesome-icon icon="search"></font-awesome-icon>
                    </b-button>
                </b-input-group-append>
                <b-input placeholder="Nach Büchern stöbern" type="text" class="search" v-on="ausgabe()"
                         v-model="search"></b-input>
            </b-input-group>

            <div class="list">
                <b-card v-for="book in liste.data.data" img-left
                        img-alt="Image"
                        style="width: 15em;" class="listitem"
                        v-on:click="buecherInformationen(book.content, book.id)" v-b-modal.BookInformationNotLoggedIn>
                    <b-card-title>
                        {{book.title}}
                    </b-card-title>
                    <b-card-text class="beschreibung">
                        {{content_short[book.id]}}
                    </b-card-text>
                </b-card>
            </div>

            <div class="page_buttons">
                <b-button v-on:click="sendtoFirst()" :disabled=isAnfang><<</b-button>
                <b-button v-on:click="decrement()" :disabled=isAnfang><</b-button>

                <b-button disabled>{{page}}</b-button>

                <b-button v-on:click="increment()" :disabled=isEnde>></b-button>
                <b-button v-on:click="sendtoLast()" :disabled=isEnde>>></b-button>
            </div>

        </div>

        <!---------------------------------------------------------

                                   Modals

        ---------------------------------------------------------->

        <div>
            <b-modal id="AddItem" size="lg" centered title="Create Book"
                     @ok="saveAdd(title, systematik, medium, content_full, BNR)">
                <b-form-group
                        label="Title"
                        label-for="title"
                        invalid-feedback="Title is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="title"
                            required
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                        label="Systematik"
                        label-for="title"
                        invalid-feedback="Systematik is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="systematik"
                            required
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                        label="Medium"
                        label-for="title"
                        invalid-feedback="Medium is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="medium"
                            required
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                        label="Content"
                        label-for="title"
                        invalid-feedback="Content is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="content_full"
                            required
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                        label="BNR"
                        label-for="title"
                        invalid-feedback="BNR is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="BNR"
                            required
                    ></b-form-input>
                </b-form-group>
            </b-modal>
            <b-modal id="EditItem" centered title="Edit Book"
                     @ok="saveEdit(id, title, systematik, medium, content_full, BNR)">
                <b-form-group
                        label="Title"
                        label-for="title"
                        invalid-feedback="Title is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="title"
                            required
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                        label="Systematik"
                        label-for="title"
                        invalid-feedback="Systematik is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="systematik"
                            required
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                        label="Medium"
                        label-for="title"
                        invalid-feedback="Medium is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="medium"
                            required
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                        label="Content"
                        label-for="title"
                        invalid-feedback="Content is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="content_full"
                            required
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                        label="BNR"
                        label-for="title"
                        invalid-feedback="BNR is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="BNR"
                            required
                    ></b-form-input>
                </b-form-group>
            </b-modal>

            <b-modal id="BookInformationAdmin" centered title="Information">
                <div>
                    {{ content_full }}
                </div>
                <template v-slot:modal-footer="{cancel}">
                    <div>
                        <b-button size="sm" variant="success" @click="cancel()">
                            Close
                        </b-button>

                        <b-button pill v-on:click="deleteItem(n.id)">
                            <font-awesome-icon icon="trash"></font-awesome-icon>
                        </b-button>

                        <b-button v-b-modal.EditItem pill
                                  v-on:click="editItem(n.id, n.title, n.systematik, n.medium, n.content, n.BNR)">
                            <font-awesome-icon icon="pen"></font-awesome-icon>
                        </b-button>

                        <b-button v-if="isBorrowed" pill v-on:click="returnBook(id)">
                            <font-awesome-icon icon="level-up-alt" class="fa-rotate-270"></font-awesome-icon>
                        </b-button>
                    </div>
                </template>
            </b-modal>

            <b-modal id="BookInformationUser" centered title="Information">
                <div>
                    {{ content_full }}
                </div>
                <template v-slot:modal-footer="{cancel}">
                    <div>
                        <b-button size="sm" variant="success" @click="cancel()">
                            Close
                        </b-button>

                        <b-button v-if="!isBorrowed" pill>
                            <font-awesome-icon icon="cart-plus"></font-awesome-icon>
                        </b-button>
                    </div>
                </template>
            </b-modal>

            <b-modal id="BookInformationNotLoggedIn" centered title="Information">
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

    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "BookList",
        data() {
            return {
                page: "",
                isAdmin: this.$store.state.isAdmin,
                isLoggedIn: false,
                isBorrowed: false,
                liste: {
                    data: {
                        data: ""
                    }
                },
                firstPage: 1,
                lastPage: 0,
                id: "",
                title: "",
                systematik: "",
                medium: "",
                BNR: "",
                content_full: [],
                content_short: [],
                dialog_title: "",
                search: "",
                isAnfang: false,
                isEnde: false,
                show: true
            };
        },
        mounted() {
            this.page = this.$store.state.count;
            axios.get('/books/json?page=' + this.page)
                .then(response => {
                        console.log(this.isAdmin);
                        this.liste.data.data = response.data.data;
                        this.lastPage = response.data.last_page;
                        this.isLoggedInCheck();
                        this.saveContent(response.data.data);
                        this.isAnfangfind();
                        this.isEndefind();
                        console.log(response.data.data);
                        this.$store.state.lastPage = this.lastPage;
                        this.saveContent(response.data.data);
                        this.isAnfangfind();
                        this.isEndefind();
                    }
                );
        },
        methods: {
            deleteItem: function (id) {

                axios.post('/books/delete/json/', {
                    id: id
                }).then(response => (
                        this.reloadSite(response.status)
                    )
                )
            },
            editItem: function (id, title, systematik, medium, content, BNR) {
                this.id = id;
                this.title = title;
                this.content_full = content;
                this.systematik = systematik;
                this.medium = medium;
                this.BNR = BNR;
            },
            addItem: function () {
                this.title = "";
                this.content_full = "";
                this.systematik = "";
                this.medium = "";
                this.BNR = "";
            },
            saveAdd: function (title, systematik, medium, content, BNR) {
                axios.post('/books/create/json/', {
                    title: title,
                    systematik: systematik,
                    medium: medium,
                    content: content,
                    BNR: BNR,
                    authorname: 'Kevin'
                }).then(response => {
                        console.log(response);
                    }
                )
            },
            saveEdit: function (id, title, systematik, medium, content, BNR) {
                console.log(id);
                axios.post('/books/edit/json/', {
                    id: id,
                    title: title,
                    systematik: systematik,
                    medium: medium,
                    content: content,
                    BNR: BNR
                })
                    .then(response => {
                            this.reloadSite(response.status);
                        }
                    )
            },
            saveContent: function (content) {
                for (let i = 0; i < content.length; i++) {
                    this.content_full[content[i].id] = content[i].content;
                    let content_words = content[i].content.split(" ");
                    if (content_words.length >= 12) {
                        this.content_short[content[i].id] = "";
                        for (let j = 0; j < 12; j++) {
                            this.content_short[content[i].id] += content_words[j] + " ";
                        }
                        this.content_short[content[i].id] += "...";
                    } else {
                        this.content_short[content[i].id] = content[i].content;
                    }
                }
            },
            buecherInformationen: function (content, id) {
                this.content_full = content;
                this.id = id;
                axios.post('/books/borrowed', {
                    id: id
                })
                    .then(response => {
                            this.isBorrowed = response.data;
                            console.log(response);
                        }
                    );
            },
            borrowBook: function (id) {
                axios.get('/books/borrow', {
                    params: {
                        id: id
                    }
                }).then(response => {
                        console.log(response);
                    }
                )
            },
            reloadSite: function (status) {
                if (status === 200) {
                    window.location.reload();
                }
            },
            ausgabe: function () {
                if (!(this.search === "")) {
                    console.log(this.search);
                }
            },
            isAnfangfind: function () {
                if (this.$store.state.count === this.firstPage) {
                    this.isAnfang = true;
                }
            },
            isEndefind: function () {
                if (this.$store.state.count === this.lastPage) {
                    this.isEnde = true;
                }
            },
            increment: function () {
                this.$store.commit('increment');
                window.location.reload();
            },
            decrement: function () {
                this.$store.commit('decrement');
                window.location.reload();
            },
            sendtoFirst: function () {
                this.$store.commit("isFirstPage");
                window.location.reload();
            },
            sendtoLast: function () {
                this.$store.commit("isLastPage");
                window.location.reload();
            },
            isLoggedInCheck: function () {
                axios.get('/session')
                    .then(response => {
                            this.isLoggedIn = response.data;
                        }
                    )
            },
            returnBook: function (id) {
                axios.post('/books/return', {
                    id: id
                })
                    .then(response => {
                            console.log(response);
                        }
                    )
            }
        }
    }

</script>

<style scoped>
    .suche_title {
        text-align: center;
    }

    .list {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        padding-right: 20%;
        padding-left: 20%;
        padding-top: 2%;
    }

    .list > * {
        flex: 1 1 10em;
    }

    .listitem {
        margin: 5em;
    }

    .listitem:hover {
        cursor: pointer;
    }

    .beschreibung {
        font-size: 12px;
    }

    .page_buttons {
        text-align: center;
        padding: 2em;
    }

</style>