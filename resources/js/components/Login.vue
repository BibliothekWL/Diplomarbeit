<template>
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
                        this.$store.state.isAdmin = response.isAdmin;
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