<template>
    <div class="body">
        <div class="searchBox">
            <div class="page_title">
                <h1 style="color: white; text-shadow: 3px 3px 0px black; padding: 1em">Profil</h1>
            </div>
        </div>

        <div class="content">

            <h4 class="col-6">E-Mail: {{userdata.email}}</h4>

            <div class="content_item col-12">
                <h4 class="col-4">Username</h4>
                <b-input class="search col-8" placeholder="Username" v-model="userdata.name">
                </b-input>
            </div>

            <div class="content_item col-12">
                <h4 class="col-4">Password:</h4>
                <b-input class="search col-8" type="password" placeholder="••••••••" v-model="userdata.password">
                </b-input>
            </div>

            <b-button class="col-2" style="width: 10em;" variant="outline-dark" v-on:click="changeCredentials()" v-on:keyup.enter="changeCredentials()">Change Credentials</b-button>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import Swal from 'sweetalert2';
    export default {
        name: "Profile",
        data(){
            return {
                userdata: ""
            }
        },
        mounted() {
            this.$store.commit("UserisNotInCart");
            this.$store.commit("UserisNotInCart_2");
            axios.post('userdata/json', {
                id: this.$store.state.userID
            })
                .then(response => {
                        console.log(response);
                        this.userdata = response.data;
                    }
                );
        },
        methods: {
            changeCredentials(){
                axios.post("http://localhost:8000/userdata/json", {
                    name: this.userdata.name
                })
                    .then(response =>{
                        console.log(response)
                }).catch(error =>{
                    Swal.fire({title: 'Oops!', text: 'Username already exists!', icon: 'error'})
                })
            }
        }
    }
</script>

<style scoped>

    .center {
        text-align: center;
    }


    .content {
        padding: 5em;
        display: flex;
        align-items: center;
        flex-direction: column;
    }

    .content_item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 47em;
        padding: 2em;
    }

    .searchBox {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 2em;
        width: 100%;
        background-image: url('../../img/bg_hp.jpg');
    }

</style>