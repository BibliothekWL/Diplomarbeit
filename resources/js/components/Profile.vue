<template>
    <div class="body">
        <div class="searchBox">
            <div class="page_title">
                <h1 style="color: white; text-shadow: 3px 3px 0px black; padding: 1em">Profil</h1>
            </div>
        </div>

        <div class="content">

            <h4 class="col-6">E-Mail: {{userdata.email}}</h4>

            <div class="content_item col-12">
                <h4 class="col-4">Username</h4>
                <b-input class="search col-8" placeholder="Username" v-model="userdata.name">
                </b-input>
                <b-button class="col-2" style="width: 10em;" v-b-modal.ChangeUsername variant="outline-dark">
                    Change Credentials
                </b-button>
            </div>

            <div class="content_item col-12">
                <h4 class="col-4">Password:</h4>
                <b-input class="search col-8" type="password" placeholder="••••••••" v-model="userdata.password">
                </b-input>

                <b-button class="col-2" style="width: 10em;" v-b-modal.ChangePassword variant="outline-dark">
                    Change Credentials
                </b-button>
            </div>


            <!-- Username Change Modal (Popup) -->

            <b-modal id="ChangeUsername" scrollable ref="modal" centered title="Benutzername ändern"
                     @ok="changeCredentials(true)">
                <form ref="form">
                    <b-form-group
                            label="Username"
                            label-for="title"
                            invalid-feedback="Vorname is required"
                    >

                        <b-form-input
                                id="name-input"
                                v-model="userdata.name"
                                required
                        ></b-form-input>
                    </b-form-group>
                </form>
            </b-modal>

            <b-modal id="ChangePassword" scrollable ref="modal" centered title="Passwort ändern"
                     @ok="">
                <form ref="form">
                    <b-form-group
                            label="Password"
                            label-for="title"
                            invalid-feedback="Password is required"
                    >

                        <b-form-input
                                id="pw-input"
                                v-model="userdata.password"
                                required
                        ></b-form-input>
                    </b-form-group>
                </form>
            </b-modal>


        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import Swal from 'sweetalert2';

    export default {
        name: "Profile",
        data() {
            return {
                userdata: ""
            }
        },
        mounted() {
            this.$store.state.warenkorb = false;
            if (!this.$store.state.isLoggedIn) {
                this.$router.push({path: '/login'})
            } else {
                this.ausgabe();
            }
        },
        methods: {
            ausgabe: function () {
                axios.post('userdata/json', {
                    id: this.$store.state.userID
                })
                    .then(response => {
                            console.log(response);
                            this.userdata = response.data;
                        }
                    );
            },
            //if true => username, else => password
            changeCredentials(type) {
                if (type) {
                    axios.post("/userdata/json", {
                        name: this.userdata.name
                    })
                        .then(response => {
                            console.log(response);
                            Swal.fire({title: 'Success!', text: 'Username successfully changed!', icon: 'success'})
                        }).catch(error => {
                        Swal.fire({title: 'Oops!', text: 'Username already exists!', icon: 'error'})
                    })
                } else {
                    axios.post("/userdata/json", {
                        password: this.userdata.password
                    })
                        .then(response => {
                            console.log(response);
                            Swal.fire({title: 'Success!', text: 'Password successfully changed!', icon: 'success'})
                        }).catch(error => {
                        console.log('error pw');
                    })
                }
            }
        }
    }
</script>

<style scoped>

    .center {
        text-align: center;
    }


    .content {
        padding: 5em;
        display: flex;
        align-items: center;
        flex-direction: column;
    }

    .content_item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 47em;
        padding: 2em;
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

</style>