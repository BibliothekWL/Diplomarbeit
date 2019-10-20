<template>
    <v-container fluid>
        <v-layout>
            <input v-model="firstname" placeholder="Vorname">
            <input v-model="lastname" placeholder="Nachname">
            <input v-model="id" placeholder="ID">
            <input v-model="password" placeholder="password">
        </v-layout>
    </v-container>
</template>

<script>
    export default {
        name: "Register",
        firstname: "",
        lastname: "",
        id: "",
        password: ""
    }
</script>

<style scoped>

</style>