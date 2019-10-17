<template>
    <span>
        <v-app-bar app color="blue darken-4" dark>
            <v-spacer class="hidden-md-and-up"></v-spacer>
            <router-link to="/">
                <v-toolbar-title to="/">{{appTitle}}</v-toolbar-title>
            </router-link>
            <v-btn text class="hidden-sm-and-down" to="/liste">Liste</v-btn>
            <v-spacer class="hidden-sm-and-down"></v-spacer>
            <v-btn text class="hidden-sm-and-down" to="/register">Register</v-btn>
            <v-btn color="blue" class="hidden-sm-and-down" to="/login">Login</v-btn>
        </v-app-bar>
    </span>
</template>
<script>
    export default {
        name: 'AppNavigation',
        data() {
            return {
                appTitle: 'BWL',
                drawer: false,
                items: [
                    {title: 'Liste'},
                    {title: 'Profile'},
                    {title: 'Register'},
                    {title: 'Login'}
                ]
            };
        }
    };
</script>
<style scoped>
    a {
        color: white;
        text-decoration: none;
    }
</style>