<template>
    <div class="test">
        <div class="form_div">
            <b-navbar class="short_navbar" type="light" variant="danger">
                <b-button disabled >Login</b-button>
                <b-button class-active="active" to="/register">Register</b-button>
            </b-navbar>
            <div class="form_div">
                <b-form-input class="inputs" v-model="email" type="email" placeholder="Enter Email"></b-form-input>
                <b-form-input class="password" v-model="password" type="password" placeholder="Enter Password"></b-form-input>
                <b-button v-on:click="login()" href>Login</b-button>
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
                axios.post('http://localhost:8000/login/json', {
                    email: this.email,
                    password: this.password
                })
                    .then(response => {
                            this.$store.commit('UserLoggedIn');
                            this.$router.push({ path: '/home' });
                    }).catch(error => {
                    console.log(error.message)
                })
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

    .disabled {
        cursor: not-allowed;
        color: gray
    }

</style>