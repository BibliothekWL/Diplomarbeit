<template>
    <v-dialog v-model="dialog" persistent max-width="600px">
        <template v-slot:activator="{ on }" xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
            <v-container>
                <v-btn class="mx-2; float-right;" fab dark small color="primary">
                    <v-icon v-on:click="editItem('new')" dark v-on="on">mdi-plus</v-icon>
                </v-btn>
                <div v-for="n in liste">
                    <h1>{{n.chartName}}</h1>
                    <div v-for="n1 in n.bpi"
                         style="display: flex; justify-content: space-between; padding: 2em">
                        <div>
                            {{n1.code}}<br>{{n1.rate}}<br>{{n1.description}}
                        </div>

                        <div>
                            <v-btn v-on:click="deleteItem(n1.code)" class="mx-2" fab dark small color="primary">
                                <v-icon dark>mdi-delete</v-icon>
                            </v-btn>
                            <v-btn v-on:click="editItem(n1.code)" v-on="on" dark class="mx-2" fab dark small
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
                <span class="headline">{{code}}</span>
            </v-card-title>
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="12" sm="6" md="4">
                            <v-text-field label="Code" required v-model="code"></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="6" md="4">
                            <v-text-field label="Rate" required v-model="rate"></v-text-field>
                        </v-col>

                        <v-col cols="12" sm="6" md="4">
                            <v-text-field label="Description" required v-model="description"></v-text-field>
                        </v-col>
                    </v-row>
                </v-container>
                <small>*indicates required field</small>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="dialog = false">Close</v-btn>
                <v-btn color="blue darken-1" text @click="dialog = false, saveEdit(id, code, rate, description)">Save
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
                code: "",
                rate: "",
                description: "",
                id: ""
            };
        },
        mounted() {
            axios.get('http://localhost:8000/books/json')
                .then(response =>
                    (
                        console.log(response)
                    )
                )
        },
        methods: {
            deleteItem: function (id) {
                delete this.liste.data.bpi[id];
                console.log(this.liste);
            },
            editItem: function (id) {
                this.code = this.liste.data.bpi[id].code;
                this.rate = this.liste.data.bpi[id].rate;
                this.description = this.liste.data.bpi[id].description;
                this.id = id;
            },
            saveEdit: function (id, code, rate, description) {
                this.liste.data.bpi[id].rate = rate;
                this.liste.data.bpi[id].description = description;
                this.liste.data.bpi[id].code = code;
                console.log(this.liste);
            }
        }
    }

</script>

<style scoped>

</style>