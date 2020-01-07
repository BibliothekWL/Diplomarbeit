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
                                <b-form-input type="text" v-model.trim="$v.name.$model" placeholder="Max Mustermann"></b-form-input>
                            </div>
                            <div class="error" v-if="$v.name.$error">Field is required</div>

                            <!-- ID -->
                            <div class="form-group" :class="{ 'form-group--error': id.$error }">
                                <b-form-input type="text" v-model.trim="$v.id.$model" placeholder="Enter ID"></b-form-input>
                            </div>
                            <div class="error" v-if="$v.id.$error">Field is required</div>

                            <!-- EMAIL -->
                            <div class="form-group" :class="{ 'form-group--error': email.$error }">
                                <b-form-input type="text" v-model.trim="$v.email.$model" placeholder="Enter E-Mail"></b-form-input>
                            </div>
                            <div class="error" v-if="$v.email.$error">Field is required</div>
                            <div class="error" v-if="!$v.email.email">Email has to be correct</div>


                            <!-- PW -->
                            <div class="form-group" :class="{ 'form-group--error': password.$error }">
                                <b-form-input type="text" v-model.trim="$v.password.$model" placeholder="Enter Password"></b-form-input>
                            </div>
                            <div class="error" v-if="$v.password.$error">Field is required</div>
                            <div class="error" v-if="!$v.password.minLength">Must be longer than 8 Chars</div>


                            <!-- PWREPEAT -->
                            <div class="form-group" :class="{ 'form-group--error': passwordRepeat.$error}">
                                <b-form-input type="text" v-model.trim="$v.passwordRepeat.$model" placeholder="Repeat Password"></b-form-input>
                            </div>
                            <div class="error" v-if="!$v.passwordRepeat.sameAsPassword">Passwords must be the same</div>
                        </form>
                    </div>
                <!--<div class="error" v-if="name.required">Field is required</div>
                <b-form-input class="inputs" v-model="name" type="text" placeholder="Enter Name" required></b-form-input>
                <b-form-input class="inputs" v-model="id" type="text" placeholder="Enter ID" required></b-form-input>
                <b-form-input class="inputs" v-model="email" type="email" placeholder="Enter Email" required></b-form-input>
                <b-form-input class="inputs" v-model="password" type="password" placeholder="Enter Password" required></b-form-input>
                <b-form-input class="inputs" v-model="passwordRepeat" type="password" placeholder="Repeat Password" required></b-form-input>
                -->
                <b-button class="inputs" v-on:click="register()" :disabled="$v.$invalid">Register</b-button>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import Vue from 'vue';
    import { required, sameAs, minLength, email } from 'vuelidate/lib/validators'

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
        },
        validations: {
            name: {
                required
            },
            id:{
                required
            },
            email:{
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
            register: function () {
                if (this.password === this.passwordRepeat) {
                    axios.post('http://localhost:8000/user/register', {
                        name: this.name,
                        id: this.id,
                        email: this.email,
                        password: this.password
                    })
                        .then(response => {
                            console.log(response);
                            this.$router.push({ path: '/login' });
                        }).catch(error => {
                        console.log(error.message);
                    })
                }
            }
        }
    }
</script>

<style >
    .test{
        display: flex;
        align-items: center;
        background-image: url("../../img/library.jpg");
        background-size: cover;
        height: calc(100vh - 54px);
    }

    .form_div{
        display: flex;
        background-color: white;
        opacity: 90%;
        margin-left: auto;
        margin-right: auto;
        width: 50%;
        min-width: 40%;
        height: 65%;
        min-height: 415px;
        border-radius: 15px;
        align-items: center;
        flex-direction: column;
    }


    .short_navbar{
        display: flex;
        justify-content: flex-start;
        width: 100%;
        border-radius: 15px;
        color: #e30013;
    }

    .navbar_btn{
        background-color: white;
        color: red;
        border-color: white;
        margin-right: 0.5em;
    }

    .error {
        font-size: 0.75rem;
        line-height: 1;
        margin-left: 5px;
        margin-top: -1rem;
        margin-bottom: 0.9375rem;
        color: red;}

    .form-group--error {
        border-color: red;
    }

    a:hover{
        color: #666666;
        text-decoration: none;
    }

</style>