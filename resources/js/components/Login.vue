<template>
    <div class="test">
        <div class="form_div">
                <b-navbar class="short_navbar" type="light" variant="danger">
                    <b-button class="navbar_btn">Login</b-button>
                    <b-button class="navbar_btn" to="/register">Register</b-button>
                </b-navbar>
                    <img src="../../img/logo.png" width="300" height="200" class="logo">
                <div class="col-6">
                    <form>
                        <!-- NAME -->
                        <b-form-input v-on:keyup.enter="login()" class="inputs" v-model="email" type="email" placeholder="Enter Email"></b-form-input>
                        <b-form-input v-on:keyup.enter="login()" class="inputs" v-model="password" type="password" placeholder="Enter Password"></b-form-input>
                    </form>
                </div>
                    <b-button class="login-btn" type="submit" v-on:click="login()" href>Login</b-button>
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
                            this.$store.state.latestUsername = response.data.username;
                            this.$store.commit("setUsername");
                            this.$store.state.latestUserID = response.data.userID;
                            this.$store.commit("setUserID");
                            this.$store.commit('UserLoggedIn');
                            if(response.data.isAdmin === true) {
                                this.$store.commit('UserisAdmin');
                            } else {
                                this.$store.commit('UserisnotAdmin');
                            }
                            window.location.href = "/list";
                        }
                    }).catch(error => {
                    console.log(error.message)
                })
            }
        }
    }
</script>

<style>

</style>