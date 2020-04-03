<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <div id="body">
        <div class="col-8" style="margin: auto; text-align: center; color: black;">
            <h1 style="color: black;">Ausgeborgte Buecher</h1>
            <b-input placeholder="Nach Schlüsselwortern filtern.." id="filter" v-model="filter"/>
            <b-table striped hover :filter="filter" :fields="fields" :items="userdata">
                <template v-slot:cell(#)="data">
                    {{ data.index + 1 }}
                </template>
                <template v-slot:cell(abgebenBtn)="data">
                    <b-button size="sm" @click="returnBook(data.item.id)" class="mr-2">
                        Zurückgeben
                    </b-button>
                </template>

            </b-table>
        </div>
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
    import axios from "axios";
    import Swal from "sweetalert2";
    import GoTop from '@inotom/vue-go-top';

    export default {
        name: "AdminView",
        components: {
            GoTop
        },
        data() {
            return {
                sortBy: 'name',
                isAdmin: this.$store.state.isAdmin,
                isLoggedIn: this.$store.state.isLoggedIn,
                filter: "",
                userdata: null,
                fields: [
                    {
                        key: '#',
                        sortable: true
                    },{
                    key: 'name',
                    label: 'Ausgeborgt von:',
                    sortable: true
                },{
                    key: 'id',
                    label: 'Buchnummer',
                    sortable: false
                },{
                    key: 'title',
                    label: 'Buchtitel',
                        sortable: false
                },{
                    key: 'abgebenBtn',
                    label: 'Zurückgeben',
                        sortable: false
                    }],
            }
        },
        mounted() {
            this.ausgabe();
            this.isLoggedInCheck();
        },

        methods: {
            getBorrowed: function () {
                this.isLoggedInCheck();
                axios.get('/getBorrowings').then(response => {
                    console.log(response);
                    this.userdata = response.data;
                })
            },
            isLoggedInCheck: function () {
                axios.get('/session')
                    .then(response => {
                            this.$store.state.isLoggedIn = response.data;
                            if (response.data) {
                                this.$store.commit('UserLoggedIn');
                            } else {
                                this.$store.commit('UsernotLoggedIn');
                            }
                        }
                    )
            },
            returnBook: function (id) {
                axios.post('/returnBooks', {
                    id: id
                }).then(response => {
                    console.log(id);
                        if (response.data.status === 200) {
                            Swal.fire({
                                title: 'Erfolg!',
                                text: 'Das ausgewählte Buch wurde erfolgreich zurückgegeben!',
                                icon: 'success'
                            });
                            this.ausgabe();
                        } else {
                            Swal.fire({
                                title: 'Fehler!',
                                text: 'Versuchen Sie es später nochmal!',
                                icon: 'error'
                            });
                        }
                    }
                )
            },
            ausgabe: function () {
                //Authentification
                if (!this.$store.state.isAdmin || !this.$store.state.isLoggedIn) {
                    this.$router.push({path: '/login'})
                } else {
                    //API-call for Book Items
                    this.getBorrowed();
                }
            }
        }
    }
</script>

<style>

</style>