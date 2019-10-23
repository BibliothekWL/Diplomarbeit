<template>
    <v-container fluid>
        <v-layout>
<<<<<<< HEAD
            <div class="col-5 lg">
                <v-text-field label="Login">Hello</v-text-field>
                <v-text-field label="Passwort">Hello</v-text-field>
            </div>
            <div>
                <v-btn absolute="true" right="true">Login</v-btn>
            </div>
=======
            <input v-model="id" placeholder="ID">
            <input v-model="password" placeholder="password">
            <v-btn v-on:click="login">Login</v-btn>
>>>>>>> 75514d4ad58bd7f4844d304681ca7ffe04b3cb26
        </v-layout>
    </v-container>
</template>

<script>
    export default {
        name: "Login",
        data() {
            return {
                id: "",
                password: ""
            }
        },
        methods: {
            login: function () {
                console.log(this.id, this.password);
            }
        }
    }
</script>

<style scoped>

</style>