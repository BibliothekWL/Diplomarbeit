<template>
    
</template>

<script>
    import axios from "axios";

    export default {
        name: "Register",
        data() {
            return {
                id: "",
                password: ""
            }
        },
        mounted() {

        },
        methods: {
            register: function () {
                console.log("WORKS");
                axios.post('http://localhost:8000/login/json', {
                    parameter: {
                        id: this.id,
                        pw: this.password
                    }
                })
                    .then(response => {
                        console.log(response.data)
                    }).catch(error => {
                    console.log(error.message)
                })
            }
        }
    }
</script>

<style scoped>

</style>