<template>
    <div>
        <div id="app">
            <div id="parentx">
                <vs-sidebar :reduce="reduce" :reduce-not-hover-expand="true"
                            color="danger" class="sidebarx" spacer v-model="active" parent="body">

                    <div style="cursor:pointer;" class="header-sidebar" slot="header" v-on:click="regular_navigation()">
                        <font-awesome-icon v-if="reduce" icon="arrow-right"></font-awesome-icon>
                        <font-awesome-icon v-if="!reduce" icon="arrow-left"></font-awesome-icon>
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

        <b-button class="warenkorb" v-if="loggedIn && !isAdmin && $store.state.warenkorb"
                  to="/warenkorb" variant="light">
            <font-awesome-icon icon="shopping-cart" class="fa-lg"></font-awesome-icon>
            <b-badge variant="transparent">{{$store.state.cart_count}}</b-badge>
        </b-button>
        <router-view></router-view>
    </div>
</template>

<script>
    import axios from "axios";


    export default {
        mounted() {
            axios.post('/cart/json', {
                id: this.$store.state.userID
            }).then(response => {
                    this.cart_count = response.data.length;
                }
            );
        },
        data() {
            return {
                loggedIn: null,
                username: this.$store.state.username,
                isAdmin: this.$store.state.isAdmin,
                reduce: true,
                active: true
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
            },
            checkout: function () {
                axios.get('/cart/checkout')
                    .then(
                        response => {
                            console.log(response);
                            this.$router.push({path: '/list'});
                        }
                    )
            },
            regular_navigation: function () {
                this.reduce = !this.reduce;

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

    .warenkorb {
        cursor: pointer;
        position: absolute;
        z-index: 1000;
        top: 0;
        right: 0;
        margin: 1em;
    }

    .warenkorb_checkout {
        position: absolute;
        z-index: 1000;
        top: 5em;
        right: 1.3em;
        margin: 0.8em;
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
        border: 0px solid rgba(0, 0, 0, 0) !important;
        border-left: 1px solid rgba(0, 0, 0, .07) !important;
        border-radius: 0px !important;
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
</style>