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
                <a class="hyperlink" v-on:click="">Passwort vergessen?</a>
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
            this.$store.state.warenkorb = false;
            this.$store.state.warenkorbCheckout = false;
            if (this.$store.state.isLoggedIn) {
                this.$router.push({path: '/'})
            }
        },
        methods: {
            login: function () {
                axios.post('/login/json', {
                    email: this.email,
                    password: this.password
                })
                    .then(response => {
                        if (response.data.status !== '200') {
                            Swal.fire({title: 'Oops!', text: response.data.statusMsg, icon: 'error'});
                            console.log(response);
                        } else {
                            this.$store.commit('UserLoggedIn');
                            console.log(response.data.isAdmin);
                            if (response.data.isAdmin === true) {
                                console.log("sadas");
                                this.$store.state.isAdmin = true;
                                this.$store.commit('UserisAdmin');
                            } else {
                                this.$store.state.isAdmin = false;
                                this.$store.commit('UserisnotAdmin');
                            }

                            axios.post('/userdata/json', {
                                id: response.data.userID
                            }).then(response => {
                                this.$store.state.userdata_login = response.data;
                                this.$store.commit('setUserdata');
                            });
                            this.$router.push({path: '/'}
                            )
                        }
                    }).catch(error => {
                    Swal.fire({
                        title: 'Oops!',
                        text: 'Something went wrong, try to refresh the site or try it later!',
                        icon: 'error'
                    });
                    console.log(error);
                })
            }
        }
    }
</script>

<style scoped>
    .hyperlink{
        color: rgba(63,142,191,1);
        text-decoration: none;
        font-size: 11pt;
    }

</style>