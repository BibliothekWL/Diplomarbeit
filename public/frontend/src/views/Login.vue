<template>
    <v-container fluid>
        <v-layout>
            <input v-model="id" placeholder="ID">
            <input v-model="password" placeholder="password">
        </v-layout>
    </v-container>
</template>

<script>
    export default {
        name: "Login",
        id: "",
        password: ""
    }
</script>

<style scoped>

</style>