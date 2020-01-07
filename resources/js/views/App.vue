<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <div>
        <b-navbar type="light" variant="danger">
            <div id="app">
                <div>
                    <Slide class="bm-menu">
                        <a href="/home">Home</a>
                        <a href="/list">Bücherliste</a>
                    </Slide>
                </div>
            </div>

            <h4 class="site_title">Bibliothek Wiener Linien</h4>
            <!-- Right aligned nav items -->
            <b-navbar-nav class="ml-auto">
                <b-button class="navbar_btn" v-show="!loggedIn" v-model="loggedIn" href="/login" right>Login
                </b-button>
                <b-nav-item-dropdown class="dropdownBtn" v-show="loggedIn" right>
                    <!-- Using 'button-content' slot -->
                    <template v-slot:button-content>
                        <em>{{ username }}</em>
                    </template>
                    <b-dropdown-item href="/profile">Profil</b-dropdown-item>
                    <b-dropdown-item href="/myBooks">Meine Bücher</b-dropdown-item>
                    <b-dropdown-item v-on:click="logout()">Logout</b-dropdown-item>
                </b-nav-item-dropdown>
            </b-navbar-nav>
        </b-navbar>
        <router-view></router-view>
    </div>
</template>

<script>
    import axios from "axios";
    import {Slide} from 'vue-burger-menu';


    export default {
        components: {
            Slide // Burger-Knopf Initlialisierung
        },
        data() {
            return {
                loggedIn: null,
                username: this.$store.state.username
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
                        this.$router.push({ path: '/login' });
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
        background-color: #dc3546; /* Black*/
        overflow-x: hidden; /* Disable horizontal scroll */
        padding-top: 60px; /* Place content 60px from the top */
        transition: 0.5s; /*0.5 second transition effect to slide in the sidenav*/
    }
    .navbar_btn{
        background-color: white;
        color: red;
        border-color: white;

    }
</style>