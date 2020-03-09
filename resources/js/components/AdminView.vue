<template>
    <div id="body">
        <div class="col-8" style="margin: auto; text-align: center; color: black;">
            <h1 style="color: black;">Ausgeborgte Buecher</h1>
            <b-table striped hover :fields="fields" :items="userdata">
                <template v-slot:cell(index)="data">
                    {{ data.index + 1 }}
                </template>
                <template v-slot:cell(abgebenBtn)="data">
                    <b-button size="sm" @click="returnBook(data.item.id)" class="mr-2">
                        Zurückgeben
                    </b-button>
                </template>

            </b-table>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import Swal from "sweetalert2";

    export default {
        name: "AdminView",
        data() {
            return {
                isAdmin: this.$store.state.isAdmin,
                isLoggedIn: this.$store.state.isLoggedIn,
                userdata: null,
                fields: [
                    'index',
                    {
                    key: 'name',
                    label: 'Ausgeborgt von:',
                    sortable: true
                },{
                    key: 'id',
                    label: 'Buchnummer',
                    sortable: true
                },{
                    key: 'title',
                    label: 'Buchtitel'
                },{
                    key: 'abgebenBtn',
                    label: 'Zurückgeben'
                }],
            }
        },
        mounted() {

            //Authentification
            if (!this.$store.state.isAdmin || !this.$store.state.isLoggedIn) {
                this.$router.push({path: '/login'})
            } else {
                //API-call for Book Items
                this.getBorrowed();
            }
        },

        methods: {
            getBorrowed: function () {
                this.isLoggedInCheck();
                axios.get('/getBorrowings').then(response => {
                    console.log(response);
                    this.userdata = response.data;
                })
            },
            isLoggedInCheck: function(){
                axios.get('/session')
                .then(response => {
                        this.$store.state.isLoggedIn = response.data;
                        if(response.data) {
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
                        } else {
                            Swal.fire({
                                title: 'Fehler!',
                                text: 'Versuchen Sie es später nochmal!',
                                icon: 'error'
                            });
                        }
                    }
                )
            }
        }
    }
</script>

<style>

</style>