<template>
    <div class="container">
        <b-button pill v-b-modal.AddItem v-on:click="addItem(liste.data.length)">
            <font-awesome-icon icon="plus"/>
        </b-button>

        <h2>Bücherliste</h2>
        <div v-for="n in liste" class="list">
            {{n}}
            <!--<div class="listItem">-->
            <!--<div v-on:click="buecherInformationen(n['id'])" v-b-modal.BookInformation>-->
            <!--<h2>{{n.id}} {{n.title}}</h2><br> <h5>{{content_short[n.id ]}}</h5>-->
            <!--</div>-->
            <!--<div>-->
            <!--<b-button pill v-on:click="deleteItem(n.id)">-->
            <!--<font-awesome-icon icon="trash"></font-awesome-icon>-->
            <!--</b-button>-->

            <!--<b-button v-b-modal.EditItem pill v-on:click="editItem(n.id)">-->
            <!--<font-awesome-icon icon="pen"></font-awesome-icon>-->
            <!--</b-button>-->
            <!--</div>-->
            <!--</div>-->
        </div>

        <div>
            <b-modal id="AddItem" size="lg" centered title="Create Book"
                     @ok="saveAdd(id, title, systematik, medium, content_full, BNR)">
                <b-form-group
                        label="Title"
                        label-for="name-input"
                        invalid-feedback="Title is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="title"
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
                        label="Content"
                        label-for="content_full"
                        invalid-feedback="Content is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="content_full"
                            required
                    ></b-form-input>
                </b-form-group>
            </b-modal>
        </div>

        <div>
            <b-modal id="BookInformation" centered title="Information">
                <div>
                    {{ content_full[id] }}
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
                content_full: [],
                content_short: [],
                dialog_title: "",
            };
        },
        mounted() {
            axios.get('/books/json')
                .then(response =>
                    (
                        this.saveListe(response.data),
                            this.saveContent(response.data.data)
                    )
                )
        },
        methods: {
            deleteItem: function (id) {

                axios.post('/books/delete/json/' + id).then(response => (
                        this.reloadSite(response.status)
                    )
                )
            },
            editItem: function (id) {
                this.id = id;
                console.log(this.liste.data);
                this.title = this.liste.data[id].title;
                console.log(this.title);
                this.content_full = this.liste.data[id].content;
                this.systematik = this.liste.data[id].systematik;
                this.medium = this.liste.data[id].medium;
                this.BNR = this.liste.data[id].BNR;
            },
            addItem: function (maxId) {
                this.id = maxId + 1;
                this.title = "sadsad";
                this.content_full.push("");
                this.systematik = "dsa";
                this.medium = "sa";
                this.BNR = "1111";
            },
            saveAdd: function (id, title, systematik, medium, content, BNR) {
                axios.post('books/create/json', {
                    title: title,
                    systematik: systematik,
                    medium: medium,
                    content: content,
                    BNR: BNR
                }).then(response => (
                        console.log(response.data)
                    )
                )
            },
            saveEdit: function (id, title, systematik, medium, content, BNR) {
                console.log(id);
                axios.post('/books/edit/json/', {
                    params: {
                        id: id,
                        title: title,
                        systematik: systematik,
                        medium: medium,
                        content: content,
                        BNR: BNR
                    }
                }).then(response => (
                        console.log(response)
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
            buecherInformationen: function (id) {
                this.dialog1 = true;
                this.id = id;
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
                if (status == 200) {
                    window.location.reload();
                }
            },
            saveListe: function (response) {
                for (let i = 0; i < response.data.length; i++) {
                    this.liste[response.data[i].id] = response.data[i];
                }
                console.log(this.liste);
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