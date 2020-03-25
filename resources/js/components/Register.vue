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
                                      placeholder="Enter Name and Surname"></b-form-input>
                    </div>
                    <div class="error" v-if="$v.name.$error">Field is required</div>

                    <!-- ID -->
                    <div class="form-group" :class="{ 'form-group--error': id.$error }">
                        <b-form-input type="number" v-model.trim="$v.id.$model" placeholder="Enter ID"></b-form-input>
                    </div>
                    <div class="error" v-if="$v.id.$error">Field is required</div>

                    <!-- EMAIL -->
                    <div class="form-group" :class="{ 'form-group--error': email.$error }">
                        <b-form-input type="text" v-model.trim="$v.email.$model"
                                      placeholder="Enter E-Mail"></b-form-input>
                    </div>
                    <div class="error" v-if="$v.email.$error">Field is required</div>
                    <div class="error" v-if="!$v.email.email">Email has to be correct</div>


                    <!-- PW -->
                    <div class="form-group" :class="{ 'form-group--error': password.$error }">
                        <b-form-input type="password" v-model.trim="$v.password.$model"
                                      placeholder="Enter Password"></b-form-input>
                    </div>
                    <div class="error" v-if="$v.password.$error">Field is required</div>
                    <div class="error" v-if="!$v.password.minLength">Must be longer than 8 Chars</div>


                    <!-- PWREPEAT -->
                    <div class="form-group" :class="{ 'form-group--error': passwordRepeat.$error}">
                        <b-form-input type="password" v-model.trim="$v.passwordRepeat.$model"
                                      placeholder="Repeat Password"></b-form-input>
                    </div>
                    <div class="error" v-if="!$v.passwordRepeat.sameAsPassword">Passwords must be the same</div>
                </form>
            </div>
            <b-button class="inputs" v-on:click="register()" :disabled="$v.$invalid">Register</b-button>
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
                            Swal.fire({title: 'User successfully created!', icon: 'success'})
                        }).catch(error => {
                        console.log(error.message);
                        Swal.fire({title: 'Duplicate Email! Use a different Email-Address!', icon: 'error'})
                    })
                }
            }
        }
    }
</script>

<style scoped>

</style>