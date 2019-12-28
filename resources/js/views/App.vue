<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <div>
        <b-navbar type="light" variant="danger">
            <div id="app">
                <Push class="bm-menu">
                    <a href="/list">Bücherliste</a>
                    <a href="/home">Home</a>
                </Push>
                <main id="page-wrap">
                </main>
            </div>
            <h4 class="site_title">Bibliothek Wiener Linien</h4>
            <!-- Right aligned nav items -->
            <b-navbar-nav class="ml-auto">
                <b-button v-if="loggedIn & !isAdmin" href="/warenkorb" variant="transparent">
                    <font-awesome-icon icon="shopping-cart"></font-awesome-icon>
                </b-button>
                <b-button class="navbar_btn" v-show="!loggedIn" v-model="loggedIn" href="/login" right>Login
                </b-button>
                <b-nav-item-dropdown class="dropdownBtn" v-show="loggedIn" right>
                    <!-- Using 'button-content' slot -->
                    <template v-slot:button-content>
                        <em>{{username}}</em>
                    </template>
                    <b-dropdown-item href="#">Profil</b-dropdown-item>
                    <b-dropdown-item href="/myBooks" v-if="!isAdmin">Meine Bücher</b-dropdown-item>
                    <b-dropdown-item v-on:click="logout()">Logout</b-dropdown-item>
                </b-nav-item-dropdown>
            </b-navbar-nav>
        </b-navbar>
        <router-view></router-view>
    </div>
</template>

<script>
    import axios from "axios";
    import {Push} from 'vue-burger-menu';

    export default {
        components: {
            Push // Burger-Knopf Initlialisierung
        },
        data() {
            return {
                loggedIn: null,
                username: this.$store.state.username,
                isAdmin: this.$store.state.isAdmin
            }
        },
        //Schaut auf die Statevariable für mögliche Änderungen
        watch: {
            '$store.state.isLoggedIn': {
                handler() {
                    this.loggedIn = this.$store.state.isLoggedIn;
                },
                immediate: true
            },
        },
        methods: {
            logout: function () {
                axios.get('/logout/json', {})
                    .then(response => {
                        this.$store.commit('UsernotLoggedIn');
                        this.$store.commit('UserisnotAdmin');
                        this.$store.commit('setSearchEmpty');
                        this.$store.commit('isFirstPage');
                        this.$router.push({path: '/login'});
                        window.location.reload();
                    }).catch(error => {
                    console.log(error.message)
                });
            },
            isLoggedInCheck: function () {
                axios.get('/session')
                    .then(response => {
                            this.loggedIn = response.data;
                        }
                    )
            }
        }
    }
</script>

<style>
    a {
        color: white;
        font-family: "Nunito", sans-serif;
    }

    a#__BVID__9__BV_button_{
        color:white;
    }

    .dropdownBtn{
        color: #ffffff;
    }

    .site_title {
        color: white;
        font-family: "Nunito", sans-serif;
        margin-left: 2em;
        position: fixed;
    }

    .link {
        margin-left: 3em;
    }

    .link:nth-child(1) {
        margin-left: 7em;
    }


    .bm-burger-button {
        position: fixed;
        width: 20px;
        height: 20px;
        left: 20px;
        top: 20px;
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
        z-index: 1000; /* Stay on top */
        top: 0;
        left: 0;
        background-color: rgb(63, 63, 65); /* Black*/
        overflow-x: hidden; /* Disable horizontal scroll */
        padding-top: 60px; /* Place content 60px from the top */
        transition: 0.5s; /*0.5 second transition effect to slide in the sidenav*/
    }

    .navbar_btn {
        background-color: white;
        color: red;
        border-color: white;

    }

    #ex4 .p1[data-count]:after {
        position: absolute;
        right: 10%;
        top: 8%;
        content: attr(data-count);
        font-size: 40%;
        padding: .2em;
        border-radius: 50%;
        line-height: 1em;
        color: white;
        background: rgba(255, 0, 0, .85);
        text-align: center;
        min-width: 1em;
    }


    #ex3 .fa-stack[data-count]:after {
        position: absolute;
        right: 0%;
        top: 1%;
        content: attr(data-count);
        font-size: 30%;
        padding: .6em;
        border-radius: 50%;
        line-height: .8em;
        color: white;
        background: rgba(255, 0, 0, .85);
        text-align: center;
        min-width: 1em;
        font-weight: bold;
    }


    /* on ex2 if you include bootstrap v3 css the number is a rounded circle with the .has-badge class */
    #ex2 .fa-stack[data-count]:after {
        position: absolute;
        right: 0%;
        top: 1%;
        content: attr(data-count);
        font-size: 30%;
        padding: .6em;
        border-radius: 999px;
        line-height: .75em;
        color: white;
        background: rgba(255, 0, 0, .85);
        text-align: center;
        min-width: 2em;
        font-weight: bold;
    }

    #ex1 .icon-grey {
        color: grey
    }

    #ex1 i {
        width: 100px;
        text-align: center;
        vertical-align: middle;
        position: relative;
    }

    #ex1 .badge:after {
        content: "100";
        position: absolute;
        background: rgba(0, 0, 255, 1);
        height: 2rem;
        top: 1rem;
        right: 1.5rem;
        width: 2rem;
        text-align: center;
        line-height: 2rem;;
        font-size: 1rem;
        border-radius: 50%;
        color: white;
        border: 1px solid blue;
    }
</style>