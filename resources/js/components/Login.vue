<template>
    <div class="test">
        <div class="form_div">
            <b-navbar class="short_navbar" type="light" variant="danger">
                <b-button class="navbar_btn">Login</b-button>
                <b-button class="navbar_btn" to="/register">Register</b-button>
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
                        console.log(response);
                        if(response.data.status !== '200'){
                            console.log('Status: ' + response.data.status +'; Error Messasge: ' + response.data.statusMsg);
                        }
                        else {
                            this.$store.commit('UserLoggedIn');
                            if(response.data.isAdmin === true) {
                                this.$store.commit('UserisAdmin');
                            } else {
                                this.$store.commit('UserisnotAdmin');
                            }
                            this.$router.push({ path: '/list' });
                        }
                    }).catch(error => {
                    console.log(error.message)
                })
            }
        }
    }
</script>

<style scoped>
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
        min-width: 30%;
        height: 60%;
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
</style>