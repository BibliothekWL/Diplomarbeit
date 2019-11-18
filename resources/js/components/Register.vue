<template>
    <div class="test">
        <div class="form_div">
            <b-navbar class="short_navbar" type="light" variant="danger">
                <router-link to="/login">Login</router-link>
                <&nbsp;>
                <router-link class="disabled" disabled to="/register">Register</router-link>
            </b-navbar>
            <div class="form_div">
                <b-form-input class="inputs" v-model="name" type="text" placeholder="Enter Name"></b-form-input>
                <b-form-input class="inputs" v-model="email" type="email" placeholder="Enter ID"></b-form-input>
                <b-form-input class="inputs" v-model="password" type="password" placeholder="Enter Password"></b-form-input>
                <b-form-input class="inputs" v-model="passwordRepeat" type="password" placeholder="Repeat Password"></b-form-input>
                <b-button v-on:click="register()">Register</b-button>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "Register",
        data() {
            return {
                name: "",
                email: "",
                password: "",
                passwordRepeat: ""
            }
        },
        mounted() {
        },
        methods: {
            register: function () {
                if (this.password === this.passwordRepeat) {
                    axios.post('http://localhost:8000/user/register', {
                        name: this.name,
                        email: this.email,
                        password: this.password
                    })
                        .then(response => {
                            console.log(response);
                            this.$store.commit('UserLoggedIn');
                            this.$router.push({ path: '/home' });
                        }).catch(error => {
                        console.log(error.message)
                    })
                } else {
                    console.log("Wrong pw!")
                }
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
        width: 30%;
        border-radius: 15px;
    }

    .disabled {
        cursor: not-allowed;
        color: gray
    }
</style>