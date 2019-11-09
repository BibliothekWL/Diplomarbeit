<template>
    <div class="container">
        <b-button pill v-b-modal.AddItem v-on:click="addItem(liste.data.length)">
            <font-awesome-icon icon="plus"/>
        </b-button>

        <h2>Bücherliste</h2>

        <div v-for="n in liste.data" class="list">
            <div class="listItem">
                <div v-on:click="buecherInformationen(n.content)" v-b-modal.BookInformation>
                    <h2>{{n.id}} {{n.title}}</h2><br> <h5>{{n.content}}</h5>
                </div>
                <div>
                    <b-button pill v-on:click="deleteItem(n.id)">
                        <font-awesome-icon icon="trash"></font-awesome-icon>
                    </b-button>

                    <b-button v-b-modal.EditItem pill
                              v-on:click="editItem(n.id, n.title, n.systematik, n.medium, n.content, n.BNR)">
                        <font-awesome-icon icon="pen"></font-awesome-icon>
                    </b-button>
                </div>
            </div>
        </div>

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
</template>

<script>
    import axios from 'axios';
    export default {
        name: "BookList",
        data() {
            return {
                liste: [],
                id: "",
                title: "",
                systematik: "",
                medium: "",
                BNR: "",
                content_full: [],
                content_short: [],
                dialog_title: "",
            };
        },
        mounted() {
            axios.get('/books/json')
                .then(response =>
                    (
                        this.liste = response.data,
                            console.log(this.liste)
                    )
                );
        },
        methods: {
            deleteItem: function (id) {
                axios.post('/books/delete/json', {
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
                    .then(response => (
                            console.log(response),
                                this.reloadSite(response.status)
                        )
                    )
            },
            saveContent: function (content) {
                for (let i = 0; i < content.length; i++) {
                    this.content_full[i] = content[i].content;
                    let content_words = content[i].content.split(" ");
                    if (content_words.length >= 12) {
                        this.content_short[i] = "";
                        for (let j = 0; j < 12; j++) {
                            this.content_short[i] += content_words[j] + " ";
                        }
                        this.content_short[i] += "...";
                    } else {
                        this.content_short[i] = content[i].content;
                    }
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
            getBooks: function () {

            }
        }
    }
</script>

<style scoped>
    .list {
        padding: 2em;
    }
    .listItem {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 1px solid black;
    }
    .listItem:hover {
        cursor: pointer;
    }
</style>