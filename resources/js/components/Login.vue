<template>
    <div class="login_form">
        <b-form-input class="inputs" v-model="id" type="email" placeholder="Enter your Email"></b-form-input>
        <b-form-input class="inputs" v-model="password" type="password" placeholder="Enter your Password"></b-form-input>
        <b-button v-on:click="login()">Login</b-button>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "Login",
        data() {
            return {
                id: "",
                password: ""
            }
        },
        mounted() {

        },
        methods: {
            login: function () {
                console.log("WORKS");
                axios.post('http://localhost:8000/login/json', {
                    parameter: {
                        id: this.id,
                        pw: this.password
                    }
                })
                    .then(response => {
                        console.log(response.data)
                    }).catch(error => {
                    console.log(error.message)
                })
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