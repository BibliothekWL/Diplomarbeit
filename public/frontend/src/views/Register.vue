<template>
    <v-container fluid>
        <v-layout>
            <input v-model="firstname" placeholder="Vorname">
            <input v-model="lastname" placeholder="Nachname">
            <input v-model="id" placeholder="ID">
            <input v-model="password" placeholder="password">
            <v-btn v-on:click="register">Register</v-btn>
        </v-layout>
    </v-container>
</template>

<script>
    export default {
        name: "Register",
        data() {
            return {
                firstname: "",
                lastname: "",
                id: "",
                password: ""
            }
        },
        methods: {
            register: function () {
                console.log(this.firstname, this.lastname, this.id, this.password);
            }
        }
    }
</script>

<style scoped>

</style>