<template>
    <div class="test">
        <div class="form_div">
            <b-navbar class="short_navbar" type="light" variant="danger">
                <router-link disabled to="/landing">Login</router-link>
                <&nbsp;>
                <router-link class-active="active" to="/register">Register</router-link>
            </b-navbar>
            <div class="form_div">
                <b-form-input class="inputs" v-model="email" type="email" placeholder="Enter Email"></b-form-input>
                <b-form-input class="inputs" v-model="password" type="password" placeholder="Enter Password"></b-form-input>
                <b-button v-on:click="login()">Login</b-button>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "Landing",
        data() {
            return {
                email: "",
                password: ""
            }
        },
        mounted() {

        },
        methods: {
            login: function () {
                axios.post('/login/json', {
                    email: this.email,
                    password: this.password
                })
                    .then(response => {
                        console.log(response)
                    }).catch(error => {
                    console.log(error.message)
                })
            },
            logout() {
                axios.get('/logout/json', {

                })
                    .then(response => {
                        console.log(response)
                    }).catch(error => {
                    console.log(error.message)
                });
            }
        }
    }
</script>

<style scoped>
    .test{
        background-image: url("../../img/library.jpg");
        height: 92.5vh;
        position: relative;
    }

    .form_div{
        background-color: white;
        opacity: 85%;
        margin-left: auto;
        margin-right: auto;
        width: 40%;
        height: 60%;
        border-radius: 15px;
        text-align: center;
    }

    .short_navbar{
        width: 40%;
        border-radius: 15px;
    }
</style>

<!--<template>
    <div class="login_form">
        <b-form-input class="inputs" v-model="email" type="email" placeholder="Enter your Email"></b-form-input>
        <b-form-input class="inputs" v-model="password" type="password" placeholder="Enter your Password"></b-form-input>
        <b-button v-on:click="login()">Login</b-button>
        <b-button v-on:click="logout()">Logout</b-button>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "Login",
        data() {
            return {
                email: "",
                password: ""
            }
        },
        mounted() {

        },
        methods: {
            login: function () {
                axios.post('http://localhost:8000/login/json', {
                        email: this.email,
                        password: this.password
                })
                    .then(response => {
                        console.log(response);
                        this.$store.state.isAdmin = response.data.isAdmin;
                        this.$store.state.isLoggedIn = response.data.isLoggedIn;
                        // window.location.href = "/list";

                    }).catch(error => {
                    console.log(error.message)
                })
            },
            logout() {
                axios.get('/logout/json')
                    .then(response => {
                        console.log(response);
                        this.$store.commit("UserisnotAdmin");
                    }).catch(error => {
                        console.log(error.message)
                });
            }
        }
    }
</script>

<style scoped>
    .login_form{
        display: flex;
        flex-wrap: nowrap;
        align-items: flex-start;
        justify-content: space-around;
        margin-top: 3em;
        margin-left: 1em;
        margin-right: 1em;
    }

    .inputs{

    }
</style>

-->