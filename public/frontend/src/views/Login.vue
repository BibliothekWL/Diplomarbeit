<template>
    <v-container fluid>
        <v-layout>
            <input v-model="id" placeholder="ID">
            <input v-model="password" placeholder="password">
            <v-btn v-on:click="login">Login</v-btn>
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