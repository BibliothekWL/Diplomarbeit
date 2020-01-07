<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <div>
        <div id="app">
            <div class="upper-bm-menu">
                <Push class="bm-menu">
                    <p v-if="loggedIn">Hallo {{username}}!</p>
                    <p v-if="!loggedIn"></p>
                    <div class="menu">
                        <a style="cursor: pointer; padding-bottom: 1em;" href="/list">Bücherliste</a>
                        <a style="cursor: pointer;" href="/home">Home</a>
                    </div>
                    <div class="menu1">
                        <a style="cursor: pointer;" v-if="loggedIn & !isAdmin" href="/profil">Profil</a>
                        <a style="cursor: pointer; padding-top: 1em;" v-if="!loggedIn" href="/login">Login</a>
                        <a style="cursor: pointer; padding-top: 1em;" v-if="loggedIn" href="#" v-on:click="logout()">Logout</a>
                    </div>
                </Push>
            </div>
            <main id="page-wrap">
            </main>
        </div>

        <b-button class="warenkorb" v-if="loggedIn & !isAdmin & notcart" href="/warenkorb" variant="transparent">
            <span class="fa-stack fa-2x has-badge" data-count="5">
                <i class="fa fa-circle"></i>
                <font-awesome-icon icon="shopping-cart"></font-awesome-icon>
            </span>
        </b-button>

        <b-button class="warenkorb_checkout" v-if="cart & loggedIn" v-on:click="checkout()">
            Ausborgen
        </b-button>

        <!--<h4 class="site_title">Bibliothek Wiener Linien</h4>-->
        <!---->
        <!--</b-button>-->

        <!--<b-nav-item-dropdown class="dropdownBtn" v-show="loggedIn" right>-->
        <!--&lt;!&ndash; Using 'button-content' slot &ndash;&gt;-->
        <!--<template v-slot:button-content>-->
        <!--<em></em>-->
        <!--</template>-->
        <!--<b-dropdown-item href="#">Profil</b-dropdown-item>-->
        <!--<b-dropdown-item href="/myBooks" v-if="!isAdmin">Meine Bücher</b-dropdown-item>-->
        <!--<b-dropdown-item v-on:click="logout()">Logout</b-dropdown-item>-->
        <!--</b-nav-item-dropdown>-->
        <router-view></router-view>
    </div>
</template>

<script>
    import axios from "axios";
    import {Push} from 'vue-burger-menu';

    export default {
        components: {
            Push
        },
        data() {
            return {
                loggedIn: null,
                username: this.$store.state.username,
                isAdmin: this.$store.state.isAdmin,
                cart: this.$store.state.warenkorb,
                notcart: this.$store.state.nichtwarenkorb
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
    a {
        color: white;
        font-family: "Nunito", sans-serif;
    }

    a#__BVID__9__BV_button_ {
        color: white;
    }

    .dropdownBtn {
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
        width: 25px;
        height: 25px;
        left: 15px;
        top: 15px;
        cursor: pointer;
        margin-right: 2em;
    }

    .bm-burger-bars {
        background-color: #ffffff;
        border: 0.7px black solid;
    }

    /*.upper-bm-menu {*/
    /*background-color: rgba(217,83,79,0.4);*/
    /*top: 0;*/
    /*left: 0;*/
    /*margin: 0.9em;*/
    /*width: 2em;*/
    /*height: 2em;*/
    /*position: fixed;*/
    /*}*/

    .bm-menu {
        height: 100vh;
        position: fixed;
        z-index: 1000;
        top: 0;
        right: 0;
        background-color: rgba(217, 83, 79, 1);
        overflow: hidden;
        transition: 0.5s;
    }

    .bm-menu a:hover {
        color: white;
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

    .menu {
        display: flex;
        flex-direction: column;
        padding-bottom: 17.5em;
    }

    .menu1 {
        display: flex;
        flex-direction: column;
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

    .warenkorb {
        position: absolute;
        z-index: 1000;
        top: 0;
        right: 0;
        margin: 0.2em;
    }

    .warenkorb_checkout {
        position: absolute;
        z-index: 1000;
        top: 5em;
        right: 1.3em;
        margin: 0.8em;
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

    .fa-circle {
        color: #000000;
    }
</style>