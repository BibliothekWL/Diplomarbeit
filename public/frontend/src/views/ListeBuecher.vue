<template>
    <v-dialog v-model="dialog" persistent max-width="600px">
        <template v-slot:activator="{ on }" xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
            <v-container>
                <v-btn class="mx-2; float-right;" fab dark small color="primary">
                    <v-icon v-on:click="addItem(liste.data.length)" dark v-on="on">mdi-plus</v-icon>
                </v-btn>

                <h2>Bücherliste</h2>
                <div v-for="n in liste.data" class="list">
                    <div class="listItem">
                        <div>
                            <h2>{{n.id}} {{n.title}}</h2><br> {{content_short[n.id - 1]}}
                        </div>
                        <div>
                            <v-btn v-on:click="deleteItem(n.id)" class="mx-2" fab dark small color="primary">
                                <v-icon dark>mdi-delete</v-icon>
                            </v-btn>
                            <v-btn v-on:click="editItem(n.id)" v-on="on" dark class="mx-2" fab dark small
                                   color="primary">
                                <v-icon dark>mdi-pencil</v-icon>
                            </v-btn>
                        </div>
                    </div>
                </div>
            </v-container>
        </template>
        <v-card>
            <v-card-title>
                <span class="headline">{{title}}</span>
            </v-card-title>
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="12" sm="6" md="4">
                            <v-text-field label="Title*" required v-model="title"></v-text-field>
                        </v-col>

                        <v-col cols="24" sm="6" md="8">
                            <v-text-field label="Content*" required v-model="content_full"></v-text-field>
                        </v-col>
                    </v-row>
                </v-container>
                <small>*indicates required field</small>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="dialog = false">Close</v-btn>
                <v-btn color="blue darken-1" text @click="dialog = false, saveEdit(id, title, content_full)">Save
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "ListeBuecher",
        data() {
            return {
                liste: [],
                dialog: false,
                id: "",
                title: "",
                content_full: [],
                content_short: []
            };
        },
        mounted() {
            axios.get('http://localhost:8000/books/json')
                .then(response =>
                    (
                        this.liste = response.data,
                            this.saveContent(response.data.data)
                    )
                )
        },
        methods: {
            deleteItem: function (id) {
                axios.get('http://localhost:8000/books/delete', {
                    params: {
                        id: id
                    }
                }).then(response => (
                        console.log(response)
                    )
                )
            },
            editItem: function (id) {
                this.title = this.liste.data[id].title;
                this.content_full = this.liste.data[id].content;
            },
            addItem: function (maxId) {
                this.title = "";
                this.content_full = "";
                this.id = maxId + 1;

            },
            saveEdit: function (id, title, content) {
                if (id > this.liste.data.length) {
                    console.log("add");
                    axios.get('http://localhost:8000/books/add', {
                        params: {
                            title: title,
                            content: content
                        }
                    }).then(response => (
                            console.log(response)
                        )
                    )
                } else {
                    console.log("edit");
                    axios.get('http://localhost:8000/books/edit', {
                        params: {
                            id: id,
                            title: title,
                            content: content
                        }
                    }).then(response => (
                            console.log(response)
                        )
                    )

                }
            },
            saveContent: function (content) {
                for (let i = 0; i < content.length; i++) {
                    this.content_full[i] = content[i].content;
                    let content_words = content[i].content.split(" ");
                    if (content_words.length >= 15) {
                        this.content_short[i] = "";
                        for (let j = 0; j < 15; j++) {
                            this.content_short[i] += content_words[j] + " ";
                        }
                        this.content_short[i] += "...";
                    } else {
                        this.content_short[i] = content[i].content;
                    }
                }
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
        align-content: center;
    }
</style>