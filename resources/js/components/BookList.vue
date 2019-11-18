<template>
    <div>
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

            <div v-for="book in liste.data.data" class="list">
                <div>
                    <b-card img-left
                            img-alt="Image"
                            style="width: 15em;">
                        <b-card-title>
                            {{book.title}}
                        </b-card-title>
                        <b-card-text class="beschreibung">
                            {{content_short[book.id]}}

                        </b-card-text>
                    </b-card>
                </div>
            </div>

            <div class="page_buttons">
                <b-button v-on:click="sendtoFirst()" :disabled=isAnfang>Penis</b-button>
                <b-button v-on:click="decrement()" :disabled=isAnfang>></b-button>

                <b-button disabled>{{page}}</b-button>

                <b-button v-on:click="increment()" :disabled=isEnde>></b-button>
                <b-button v-on:click="sendtoLast()" :disabled=isEnde>>></b-button>

            </div>


            <!--<div v-for="n in liste.data" class="list">-->
            <!--<div class="listItem">-->
            <!--<div v-on:click="buecherInformationen(n.content)" v-b-modal.BookInformation>-->
            <!--<h2>{{n.id}} {{n.title}}</h2><br> <h5>{{n.content}}</h5>-->
            <!--</div>-->
            <!--<div>-->
            <!--<b-button pill v-on:click="deleteItem(n.id)">-->
            <!--<font-awesome-icon icon="trash"></font-awesome-icon>-->
            <!--</b-button>-->

            <!--<b-button v-b-modal.EditItem pill-->
            <!--v-on:click="editItem(n.id, n.title, n.systematik, n.medium, n.content, n.BNR)">-->
            <!--<font-awesome-icon icon="pen"></font-awesome-icon>-->
            <!--</b-button>-->
            <!--</div>-->
            <!--</div>-->
            <!--</div>-->

        </div>

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

            <div v-for="book in liste" class="list">
                <div>
                    <b-card img-left
                            img-alt="Image"
                            style="width: 15em;">
                        <b-card-title>
                            {{book.title}}
                        </b-card-title>
                        <b-card-text class="beschreibung">
                            {{content_short[book.id]}}
                        </b-card-text>
                    </b-card>
                </div>
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

        <div>
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
            </div>

            <div>
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
            </div>

            <div>
                <b-modal id="BookInformation" centered title="Information">
                    <div>
                        {{ content_full }}
                    </div>
                </b-modal>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "BookList",
        data() {
            return {
                page: this.$store.state.count,
                isAdmin: this.$store.state.isAdmin,
                liste: [],
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
            axios.get('/books/json?page=' + this.page)
                .then(response => {
                        this.liste = response.data.data;
                        this.lastPage = response.data.last_page;
                        this.saveContent(response.data.data);
                        this.isAnfangfind();
                        this.isEndefind();
                        console.log(response);
                        this.$store.state.lastPage = this.lastPage;
                        this.saveContent(response.data.data);
                        this.isAnfangfind();
                        this.isEndefind();
                        console.log(this.lastPage);
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
                }).then(response => (
                        console.log(response)
                    )
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
                            console.log(response);
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
                    console.log(content.length);
                }
            },
            buecherInformationen: function (content) {
                this.content_full = content;
            },
            borrowBook: function (id) {
                axios.get('/books/borrow', {
                    params: {
                        id: id
                    }
                }).then(response => (
                        console.log(response)
                    )
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
                console.log(this.lastPage);
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
                axios.get('/books/json?page=' + this.firstPage)
                    .then(response => {
                            this.liste = response.data.data;
                            this.lastPage = response.data.last_page;
                            this.$store.state.lastPage = this.lastPage;
                            this.saveContent(response.data.data);
                            this.isAnfangfind();
                            this.isEndefind();
                            window.location.reload();
                        }
                    );
            },
            sendtoLast: function () {
                this.$store.commit("isLastPage");
                axios.get('/books/json?page=' + this.lastPage)
                    .then(response => {
                            this.liste = response.data.data;
                            this.lastPage = response.data.last_page;
                            this.$store.state.lastPage = this.lastPage;
                            this.saveContent(response.data.data);
                            this.isAnfangfind();
                            this.isEndefind();
                            window.location.reload();
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
        flex-direction: column;
        padding: 2em;
    }

    .beschreibung {
        font-size: 12px;
    }

    .page_buttons {
        text-align: center;
        padding: 2em;
    }

</style>