<template>
    <div>
        <h1> User Profile </h1>
        <div>
            <b-form-input class="inputs" v-model="name" type="email"></b-form-input>
            <b-button value="Edit">Edit</b-button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Profile",
        data(){
            return {
                name: "Mariano Italiano"
            }
        }
    }
</script>

<style scoped>

</style>