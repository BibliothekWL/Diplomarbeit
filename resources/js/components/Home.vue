<template>
    <h1>Home</h1>
</template>

<script>
    export default {
        data() {
            return {
                name: "Home",
                loggedIn: this.$store.state.isLoggedIn
            }
        },
        mounted() {
            this.loggedIn = this.$store.state.isLoggedIn;
        }
    }
</script>

<style scoped>

</style>