<template>
    <div class="test">
        <div class="form_div">
            <b-navbar class="short_navbar" type="light" variant="danger">
                <b-button class="navbar_btn">Login</b-button>
                <b-button class="navbar_btn" to="/register">Register</b-button>
            </b-navbar>
            <div class="form_div">
                <img src="../../img/logo.png" class="img-fluid">
                <b-form-input v-on:keyup.enter="login()" class="inputs" v-model="email" type="email"
                              placeholder="Enter Email"></b-form-input>
                <b-form-input v-on:keyup.enter="login()" class="inputs" v-model="password" type="password"
                              placeholder="Enter Password"></b-form-input>
                <b-button class="inputs" v-on:click="login()" href>Login</b-button>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import Swal from 'sweetalert2';


    export default {
        name: "Landing",
        data() {
            return {
                email: "",
                password: ""
            }
        },
        watch: {
            '$store.state.isLoggedIn': {
                handler() {
                    this.loggedIn = this.$store.state.isLoggedIn;
                },
                immediate: true
            },
        },
        mounted() {
            console.log(this.$store.state.isLoggedIn);
        },
        methods: {
            login: function () {
                axios.post('http://localhost:8000/login/json', {
                    email: this.email,
                    password: this.password
                })
                    .then(response => {
                        if (response.data.status !== '200') {
                            Swal.fire({title: 'Oops!', text: response.data.statusMsg, icon: 'error'});
                            console.log(response);
                        } else {
                            this.$store.state.latestUsername = response.data.username;
                            this.$store.commit("setUsername");
                            this.$store.state.latestUserID = response.data.userID;
                            this.$store.commit("setUserID");
                            this.$store.commit('UserLoggedIn');
                            if (response.data.isAdmin === true) {
                                this.$store.commit('UserisAdmin');
                            } else {
                                this.$store.commit('UserisnotAdmin');
                            }
                            this.$forceUpdate();
                            this.$router.push({path: '/home'}
                            )
                        }
                    }).catch(error => {
                    console.log();
                    Swal.fire({title: 'Oops!', text: 'Something went wrong, try to refresh the site or try it later!', icon: 'error'});
                    console.log(error);
                })
            }
        }
    }
</script>

<style scoped>

</style>