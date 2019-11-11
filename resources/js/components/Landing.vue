<template>
    <div class="test">
        <div class="form_div">
            <b-navbar class="short_navbar" type="light" variant="danger">
                <router-link class="link" to="/landing">Login</router-link>
                <router-link class="link" to="/register">Register</router-link>
            </b-navbar>
            <div class="form_div">
            <b-form-input class="inputs" v-model="id" type="email" placeholder="Enter Email"></b-form-input>
            <b-form-input class="inputs" v-model="password" type="password" placeholder="Enter Password"></b-form-input>
            <b-button v-on:click="">Login</b-button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Landing",
        data(){
            return {
                id: "",
                password: ""
            }
        }
    }
</script>

<style scoped>
    .test{
        background-image: url("../../img/library.jpg");
        height: 92.5vh;
        position: relative;
    }

.form_div{
    background-color: white;
    opacity: 85%;
    margin-left: auto;
    margin-right: auto;
    width: 40%;
    height: 60%;
    border-radius: 15px;
    text-align: center;
}

.short_navbar{
    width: 40%;
    border-radius: 15px;
}
</style>