<template>
    <div>
        <div id="app">
            <div id="parentx">
                <vs-sidebar :reduce="reduce" reduce-not-rebound color="danger" class="sidebarx" spacer v-model="active"
                            parent="body">

                    <div class="header-sidebar" slot="header">
                        <img src="../../img/logo.png" class="img-fluid">
                    </div>

                    <vs-sidebar-item index="1" icon="home" to="/home">
                        Home
                    </vs-sidebar-item>

                    <br>

                    <vs-sidebar-item index="5" icon="menu_book" to="/list">
                        Bücherliste
                    </vs-sidebar-item>

                    <br v-if="isAdmin & loggedIn">

                    <vs-sidebar-item v-if="isAdmin & loggedIn" index="20" icon="supervisor_account" to="/authorlist">
                        Autorliste
                    </vs-sidebar-item>

                    <br v-if="isAdmin & loggedIn">

                    <vs-sidebar-item v-if="isAdmin & loggedIn" index="15" icon="dashboard" to="/admin">
                        Admin-Dashboard
                    </vs-sidebar-item>

                    <br v-if="!isAdmin & loggedIn">

                    <vs-sidebar-item v-if="!isAdmin & loggedIn" index=10 icon="bookmarks" to="/myBooks">
                        Meine Bücher
                    </vs-sidebar-item>

                    <br v-if="loggedIn">

                    <vs-sidebar-item v-if="loggedIn" index=6 icon="account_box" to="/profil">
                        Profil
                    </vs-sidebar-item>

                    <br v-if="!loggedIn">

                    <vs-sidebar-item index="7" v-if="!loggedIn" icon="person_add" to="/login">
                        Login
                    </vs-sidebar-item>

                    <br v-if="loggedIn">

                    <vs-sidebar-item index="9" v-if="loggedIn" icon="exit_to_app" v-on:click="logout()">
                        Logout
                    </vs-sidebar-item>

                </vs-sidebar>
            </div>
        </div>
        <router-view></router-view>
    </div>
</template>

<script>
    import axios from "axios";


    export default {
        mounted() {
        },
        data() {
            return {
                loggedIn: null,
                username: this.$store.state.username,
                isAdmin: this.$store.state.isAdmin,
                reduce: true,
                active: true,
                cart_count: 0
            }
        },
        watch: {
            '$store.state.isLoggedIn': {
                handler() {
                    this.loggedIn = this.$store.state.isLoggedIn;
                },
                immediate: true
            },
            '$store.state.isAdmin': {
                handler() {
                    this.isAdmin = this.$store.state.isAdmin;
                },
                immediate: true
            },
        },
        methods: {
            logout: function () {
                axios.get('/logout/json')
                    .then(response => {
                        this.$store.commit('UsernotLoggedIn');
                        this.$store.commit('UserisnotAdmin');
                        window.location.href = "/login";
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
    a, h1 {
        color: white;
        font-family: "Nunito", sans-serif;
    }

    .vs-sidebar {
        background: rgba(227, 0, 19, 1);
        color: white;
        overflow-x: hidden;
    }

    .header-sidebar {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        width: 100%;
        text-align: center;
    }

    .header-sidebar > button {
        margin-left: 10px;
    }

    .footer-sidebar > button {
        border: 0 solid rgba(0, 0, 0, 0) !important;
        border-left: 1px solid rgba(0, 0, 0, .07) !important;
        border-radius: 0 !important;
    }

    .vs-sidebar--background {
        display: none;
    }

    .fa-stack[data-count]:after {
        position: absolute;
        right: 0;
        top: 0;
        content: attr(data-count);
        font-size: 40%;
        padding: .6em;
        border-radius: 999px;
        line-height: .75em;
        color: #000000;
        text-align: center;
        min-width: 2em;
        font-weight: bold;
        background: white;
        border-style: solid;
    }

    .navigation_arrow_right {
        position: absolute;
        z-index: 1000;
        top: 2em;
        left: 5em;
    }

    .navigation_arrow_left {
        position: absolute;
        z-index: 1000;
        top: 2em;
        left: 18em;
    }

    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.5);
    }

    .test {
        display: flex;
        align-items: center;
        background-image: url("../../img/bg_hp.jpg");
        background-size: cover;
        height: calc(100vh);
    }

    .form_div {
        display: flex;
        background-color: white;
        opacity: 90%;
        margin-left: auto;
        margin-right: auto;
        width: 50%;
        min-width: 40%;
        height: 70%;
        min-height: 435px;
        border-radius: 15px;
        align-items: center;
        flex-direction: column;
    }


    .short_navbar {
        display: flex;
        justify-content: flex-start;
        width: 100%;
        border-radius: 15px;
        color: #e30013;
    }

    .navbar_btn {
        background-color: white;
        color: red;
        border-color: white;
        margin-right: 0.5em;
    }

    .error {
        font-size: 0.75rem;
        line-height: 1;
        margin-left: 5px;
        margin-top: -1rem;
        margin-bottom: 0.9375rem;
        color: red;
    }

    .form-group {
        border-color: red;
    }

    a:hover {
        color: #666666;
        text-decoration: none;
    }

    .inputs {
        margin-top: 1em;
    }
</style>