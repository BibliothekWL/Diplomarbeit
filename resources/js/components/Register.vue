<template>
    <div class="test">
        <div class="form_div">
            <b-navbar class="short_navbar" type="light" variant="danger">
                <b-button class="navbar_btn" to="/login">Login</b-button>
                <b-button class="navbar_btn">Register</b-button>
            </b-navbar>
            <div class="col-6" style="margin-top: 1em">
                <form>
                    <!-- NAME -->
                    <div class="form-group" :class="{ 'form-group--error': name.$error }">
                        <b-form-input type="text" v-model.trim="$v.name.$model"
                                      placeholder="Name und Nachname"></b-form-input>
                    </div>
                    <div class="error" v-if="$v.name.$error">Dieses Feld ist erforderlich!</div>

                    <!-- ID -->
                    <div class="form-group" :class="{ 'form-group--error': id.$error }">
                        <b-form-input type="number" v-model.trim="$v.id.$model" placeholder="Mitarbeiter-ID"></b-form-input>
                    </div>
                    <div class="error" v-if="$v.id.$error">Dieses Feld ist erforderlich!</div>

                    <!-- EMAIL -->
                    <div class="form-group" :class="{ 'form-group--error': email.$error }">
                        <b-form-input type="text" v-model.trim="$v.email.$model"
                                      placeholder="Email Adresse"></b-form-input>
                    </div>
                    <div class="error" v-if="$v.email.$error">Dieses Feld ist erforderlich!</div>
                    <div class="error" v-if="!$v.email.email">Das E-ail Format stimmt nicht!</div>


                    <!-- PW -->
                    <div class="form-group" :class="{ 'form-group--error': password.$error }">
                        <b-form-input type="password" v-model.trim="$v.password.$model"
                                      placeholder="Enter Password"></b-form-input>
                    </div>
                    <div class="error" v-if="$v.password.$error">Dieses Feld ist erforderlich!</div>
                    <div class="error" v-if="!$v.password.minLength">Passwort muss mindestens 8 Symbole lang sein!</div>


                    <!-- PWREPEAT -->
                    <div class="form-group" :class="{ 'form-group--error': passwordRepeat.$error}">
                        <b-form-input type="password" v-model.trim="$v.passwordRepeat.$model"
                                      placeholder="Repeat Password"></b-form-input>
                    </div>
                    <div class="error" v-if="!$v.passwordRepeat.sameAsPassword">Die Passwörter stimmen nicht überein!</div>
                </form>
            </div>
            <b-button class="inputs" v-on:click="register()" :disabled="$v.$invalid">Registrieren</b-button>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import Swal from 'sweetalert2';
    import {required, sameAs, minLength, email} from 'vuelidate/lib/validators'

    export default {
        data() {
            return {
                name: "",
                email: "",
                id: "",
                password: "",
                passwordRepeat: ""
            }
        },
        mounted() {
            this.isLoggedInCheck();
            this.$store.state.warenkorb = false;
            this.$store.state.warenkorbCheckout = false;
            if (this.$store.state.isLoggedIn) {
                this.$router.push({path: '/'})
            }
        }, watch: {
            '$store.state.isLoggedIn': {
                handler() {
                    this.loggedIn = this.$store.state.isLoggedIn;
                },
                immediate: true
            },
        },
        validations: {
            name: {
                required
            },
            id: {
                required
            },
            email: {
                required,
                email
            },
            password: {
                required,
                minLength: minLength(8)
            },
            passwordRepeat: {
                sameAsPassword: sameAs('password')
            }
        },
        methods: {
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
            register: function () {
                if (this.password === this.passwordRepeat) {
                    Swal.showLoading();
                    axios.post('user/register', {
                        name: this.name,
                        id: this.id,
                        email: this.email,
                        password: this.password
                    })
                        .then(response => {
                            console.log(response);
                            this.$router.push({path: '/login'});
                            Swal.close()
                            Swal.fire({title: 'Benutzer wurde erfolgreich erstellt!', icon: 'success'})
                        }).catch(error => {
                        console.log(error.message);
                        Swal.fire({title: 'Email wird bereits verwendet!', icon: 'error'})
                    })
                }
            }
        }
    }
</script>

<style scoped>
    .form-group{
        margin-bottom: 1.25em;
    }
</style>