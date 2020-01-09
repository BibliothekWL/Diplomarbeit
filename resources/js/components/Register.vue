<template>
    <div class="test">
        <div class="form_div">
            <b-navbar class="short_navbar" type="light" variant="danger">
                <b-button class="navbar_btn" to="/login">Login</b-button>
                <b-button class="navbar_btn">Register</b-button>
            </b-navbar>
            <div class="form_div">
                <b-form-input class="inputs" v-model="name" type="text" placeholder="Enter Name" required></b-form-input>
                <b-form-input class="inputs" v-model="id" type="text" placeholder="Enter ID" required></b-form-input>
                <b-form-input class="inputs" v-model="email" type="email" placeholder="Enter Email" required></b-form-input>
                <b-form-input class="inputs" v-model="password" type="password" placeholder="Enter Password" required></b-form-input>
                <b-form-input class="inputs" v-model="passwordRepeat" type="password" placeholder="Repeat Password" required></b-form-input>
                <b-button class="inputs" v-on:click="register()">Register</b-button>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    // import Swal from 'sweetalert2';

    export default {
        data() {
            return {
                name: "",
                email: "",
                id: "",
                password: "",
                passwordRepeat: ""
            }
        },
        mounted() {
        },
        methods: {
            register: function () {
                if (this.password === this.passwordRepeat) {
                    axios.post('http://localhost:8000/user/register', {
                        name: this.name,
                        id: this.id,
                        email: this.email,
                        password: this.password
                    })
                        .then(response => {
                            console.log(response);
                            this.$router.push({ path: '/login' });
                        }).catch(error => {
                        console.log(error.message);
                    })
                } else {
                    Swal.fire({
                        title: 'Passwords do not match! / Account with following Email already exists!',
                        icon: 'error',
                        confirmButtonText: 'Retry'});
                    console.log("Passwords do not match! / Account with following Email already exists!")
                }
            }
        }
    }
</script>

<style >
    .test{
        display: flex;
        align-items: center;
        background-image: url("../../img/library.jpg");
        background-size: cover;
        height: calc(100vh);
    }

    .form_div{
        display: flex;
        background-color: white;
        opacity: 90%;
        margin-left: auto;
        margin-right: auto;
        width: 50%;
        min-width: 40%;
        height: 65%;
        min-height: 60%;
        border-radius: 15px;
        align-items: center;
        flex-direction: column;
    }


    .short_navbar{
        display: flex;
        justify-content: flex-start;
        width: 100%;
        border-radius: 15px;
        color: #e30013;
    }

    .inputs{
        margin-top: 10px;
    }

    .navbar_btn{
        background-color: white;
        color: red;
        border-color: white;
        margin-right: 0.5em;
    }

</style>