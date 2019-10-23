<template>
    <v-container fluid>
        <v-layout>
            <div class="col-5 lg">
                <v-text-field label="Login">Hello</v-text-field>
                <v-text-field label="Passwort">Hello</v-text-field>
            </div>
            <div>
                <v-btn absolute="true" right="true">Login</v-btn>
            </div>
        </v-layout>
    </v-container>
</template>

<script>
    export default {
        name: "Login"
    }
</script>

<style scoped>

</style>