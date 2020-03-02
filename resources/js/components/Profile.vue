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
                <b-input class="search col-8" v-bind:value="userdata.name" disabled>
                </b-input>
                <b-button class="col-2" style="width: 10em;" v-b-modal.ChangeUsername variant="outline-dark">
                    Username Ändern
                </b-button>
            </div>

            <div class="content_item col-12">
                <h4 class="col-4">Password:</h4>
                <b-input class="search col-8" type="password" placeholder="••••••••" disabled>
                </b-input>

                <b-button class="col-2" style="width: 10em;" v-b-modal.ChangePassword variant="outline-dark">
                    Passwort Ändern
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
                                value="userdata.name"
                                id="name-input"
                                v-model="userdata.name"
                                required
                        ></b-form-input>
                    </b-form-group>

                </form>
            </b-modal>

            <b-modal id="ChangePassword" scrollable ref="modal" centered title="Passwort ändern"
                     hide-footer>
                <form ref="form">
                    <b-form-group
                            label="Aktuelles Password"
                            label-for="title"
                            invalid-feedback="Passwort muss angegeben werden"
                    >

                        <b-form-input
                                id="pwold-input"
                                v-model="pw"
                                type="password"
                                required
                        ></b-form-input>
                    </b-form-group>

                    <!-- Password 2x -->
                    <b-form-group
                            label="Neues Password"
                            label-for="title"
                            invalid-feedback="Passwort muss angegeben werden"
                    >

                        <b-form-input
                                id="pwnew-input"
                                v-model="userdata.password"
                                type="password"
                                required
                        ></b-form-input>
                    </b-form-group>
                    <b-form-group
                            label="Neues Password wiederholen"
                            label-for="title"
                            invalid-feedback="Passwörter müssen übereinstimmen"
                    >

                        <b-form-input
                                id="pwnew-input"
                                v-model="pwRepeat"
                                type="password"
                                required
                        ></b-form-input>
                    </b-form-group>
                    <div>
                        <b-button :disabled="userdata.password !== pwRepeat||userdata.password.length<8||pwRepeat.length<8" @click="changeCredentials(false) ">Passwort ändern</b-button>
                        <b-button variant="danger" @click="hideModal()">Cancel</b-button>
                    </div>
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
                userdata: "",
                pw: "",
                pwRepeat: ""
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
                this.userdata = this.$store.state.userdata;
            },
            //if true => username, else => password
            changeCredentials(type) {
                if (type) {
                    axios.post('/user/editName', {
                        name: this.userdata.name
                    })
                        .then(response => {
                            console.log(response);
                            Swal.fire({title: 'Success!', text: 'Username successfully changed!', icon: 'success'})
                        }).catch(error => {
                        Swal.fire({title: 'Oops!', text: 'Username already exists!', icon: 'error'})
                    })
                } else {
                    axios.post('/user/editPassword', {
                        oldPw: this.pw,
                        newPw: this.userdata.password
                    })
                        .then(response => {
                            if (response.status === '200') {
                                Swal.fire({
                                    title: 'Erfolg!',
                                    text: 'Passwort wurde erfolgreich aktualisiert!',
                                    icon: 'success'
                                })
                                this.$router.push({path: '/logout'});
                            } else {
                                Swal.fire({
                                    title: 'Fehler!',
                                    text: 'Passwort konnte nicht geändert werden! Bitte versuchen Sie es erneut!',
                                    icon: 'error'
                                })
                            }
                        }).catch(error => {
                        console.log('error pw');
                    })
                }
            },
            hideModal() {
                this.userdata.password = "";
                this.pw = "";
                this.pwRepeat = "";
                this.$refs['modal'].hide()
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