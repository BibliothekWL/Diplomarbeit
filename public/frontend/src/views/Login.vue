<template>
    <v-container fluid>
        <v-layout>
            <div class="col-5 lg">
                <v-text-field label="Login" v-model="id" required>Hello</v-text-field>
                <v-text-field label="Passwort" v-model="password" required>Hello</v-text-field>
            </div>
            <div>
                <v-btn absolute right v-on:click="login">Login</v-btn>
            </div>
        </v-layout>
    </v-container>
</template>

<script>
    import axios from "axios";

    export default {
        name: "Login",
        data() {
            return {
                id: "",
                password: ""
            }
        },
        mounted(){
            axios.get('/api/login', {
                params: {
                    id: this.id,
                    pw: this.password
                }
            })
                .then(response =>{
                console.log(response.data)
            }).catch(error =>{
                console.log(error.message)
            })
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