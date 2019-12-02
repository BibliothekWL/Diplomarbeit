<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <div class="parent">
        <b-navbar type="light" variant="danger" class="shadow main_navbar">
                <Slide class="sidebar">
                        <a href="/list">Bücherliste</a>
                        <a href="/home">Home</a>
                </Slide>
            <h4 class="site_title">Bibliothek Wiener Linien</h4>
            <!-- Right aligned nav items -->
            <b-navbar-nav class="ml-auto">
                <b-button color="rgba(255,255,255,0)" v-show="!loggedIn" v-model="loggedIn" to="/login" right>Login</b-button>
                <b-nav-item-dropdown v-show="loggedIn" right>
                    <!-- Using 'button-content' slot -->
                    <template v-slot:button-content>
                        <em>User</em>
                    </template>
                    <b-dropdown-item href="#">Profile</b-dropdown-item>
                    <b-dropdown-item v-on:click="logout()">Logout</b-dropdown-item>
                </b-nav-item-dropdown>
            </b-navbar-nav>
        </b-navbar>
        <div class="content">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import { Slide } from 'vue-burger-menu';

    export default {
        components: {
            Slide // Burger-Knopf Initlialisierung
        },
        data() {
            return {
                loggedIn: null
            }
        },
        //Schaut auf die Statevariable für mögliche Änderungen
        watch: {
            '$store.state.isLoggedIn': {
                handler(){
                    this.loggedIn = this.$store.state.isLoggedIn;
                },
                immediate: true
            },
        },
        methods:{
                logout: function () {
                    axios.get('/logout/json', {})
                        .then(response => {
                            this.$store.commit('UsernotLoggedIn');

                            location.reload();
                            this.$router('/login');
                        }).catch(error => {
                        console.log(error.message)
                    });
                },
            }
    }
</script>

<style>
    html, body{
        height: 100%;
        margin: 0;
    }

    .parent{
        display: flex;
        flex-flow: column;
        height: 100%;
    }

    .content{
        flex: 1 1 auto;
    }

    a, em{
        color: white;
        font-family: "Nunito", sans-serif;
        margin-right: 1em;
    }


    .site_title{
        color: white;
        font-family: "Nunito", sans-serif;
        margin-left: 2em;
        position: absolute;
    }

    .bm-burger-button {
        position: absolute;
        width: 25px;
        height: 20px;
        left: 17px;
        top: 17px;
        cursor: pointer;
        margin-right: 2em;
    }

    .bm-burger-bars {
        background-color: #ffffff;
    }

    .bm-menu {
        height: 100%; /* 100% Full-height */
        width: 0; /* 0 width - change this with JavaScript */
        position: fixed; /* Stay in place */
        z-index: 1000;
        top: 56px;
        left: 0;
        background-color: rgb(220, 53, 69); /* RED*/
        overflow-x: hidden; /* Disable horizontal scroll */
        padding-top: 60px; /* Place content 60px from the top */
        transition: 0.5s; /*0.5 second transition effect to slide in the sidenav*/
        opacity: 80%;
    }
</style>