<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <div>
        <b-navbar v-if="loggedIn" type="light" variant="danger">
            <router-link class="link" to="/">Home</router-link>
            <router-link class="link" to="/list">Liste</router-link>

            <!-- Right aligned nav items -->
            <b-navbar-nav class="ml-auto">

                <!--   <b-nav-item-dropdown text="Lang" right>
                       <b-dropdown-item href="#">EN</b-dropdown-item>
                       <b-dropdown-item href="#">ES</b-dropdown-item>
                       <b-dropdown-item href="#">RU</b-dropdown-item>
                       <b-dropdown-item href="#">FA</b-dropdown-item>
                   </b-nav-item-dropdown>
                   -->

                <b-nav-item-dropdown right>
                    <!-- Using 'button-content' slot -->
                    <template v-slot:button-content>
                        <em>User</em>
                    </template>
                    <b-dropdown-item href="#">Profile</b-dropdown-item>
<<<<<<< HEAD
                    <b-dropdown-item href="/logout">Logout</b-dropdown-item>
=======
                    <b-dropdown-item href="/login" v-on:click="">Logout</b-dropdown-item>
>>>>>>> 692d902d1ed1d91d2c233c8373c0f9a4add6c0bc
                </b-nav-item-dropdown>
            </b-navbar-nav>
        </b-navbar>

        <b-navbar v-else type="light" variant="danger">
            <router-link class="link" to="/">Home</router-link>
            <router-link class="link" to="/list">Liste</router-link>

            <!-- Right aligned nav items -->
            <b-navbar-nav class="ml-auto">

                <!--   <b-nav-item-dropdown text="Lang" right>
                       <b-dropdown-item href="#">EN</b-dropdown-item>
                       <b-dropdown-item href="#">ES</b-dropdown-item>
                       <b-dropdown-item href="#">RU</b-dropdown-item>
                       <b-dropdown-item href="#">FA</b-dropdown-item>
                   </b-nav-item-dropdown>
                   -->

                <b-nav-item-dropdown right>
                    <!-- Using 'button-content' slot -->
                    <template v-slot:button-content>
                        <em>User</em>
                    </template>
                    <b-dropdown-item href="#">Profile</b-dropdown-item>
                    <b-dropdown-item href="/login">Login</b-dropdown-item>
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
                loggedIn: false
            }
        },
        methods:
            {
                logout: function () {
                    axios.get('/logout/json', {})
                        .then(response => {
                            console.log(response)
                        }).catch(error => {
                        console.log(error.message)
                    });
                }
            }
>>>>>>> 692d902d1ed1d91d2c233c8373c0f9a4add6c0bc
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