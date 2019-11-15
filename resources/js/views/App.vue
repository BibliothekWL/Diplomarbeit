<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <div>
        <b-navbar type="light" variant="danger">
            <router-link class="link" to="/">Home</router-link>
            <router-link class="link" to="/list">Liste</router-link>

            <!-- Right aligned nav items -->
            <b-navbar-nav class="ml-auto">
                <b-button v-show="loggedIn" v-model="loggedIn" v-on:change="checkOnValuechange()" v-on:click="logout()" href="/login" right>Logout</b-button>
                <b-button v-show="!loggedIn" v-model="loggedIn" v-on:change="checkOnValuechange()" href="/login" right>Login</b-button>
                <b-nav-item-dropdown right>
                    <!-- Using 'button-content' slot -->
                    <template v-slot:button-content>
                        <em>User</em>
                    </template>
                    <b-dropdown-item href="#">Profile</b-dropdown-item>
                </b-nav-item-dropdown>
            </b-navbar-nav>
        </b-navbar>
        <router-view></router-view>
    </div>
</template>

<script>
    import axios from "axios";
    export default {
        data() {
            return {
                loggedIn: this.$store.state.isLoggedIn
            }
        },
        mounted() {
            this.loggedIn = this.$store.state.isLoggedIn;
            console.log('Is Logged In? ' + this.$store.state.isLoggedIn);
        },
        methods:{
        checkOnValuechange: function() {
            if(this.loggedIn===false) {
                this.$router.go();
            }else{

            }
        },

                logout: function () {
                    axios.get('/logout/json', {})
                        .then(response => {
                            console.log(response);
                            return this.loggedIn = false;
                        }).catch(error => {
                        console.log(error.message)
                    });
                }
            }
    }
</script>

<style>
    a {
        color: white;
        font-family: "Nunito", sans-serif;
    }

    .link {
        margin-right: 1em;
    }
</style>