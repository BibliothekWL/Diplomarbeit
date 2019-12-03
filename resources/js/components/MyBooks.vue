<template>

</template>

<script>
    import axios from 'axios';

    export default {
        name: "MyBooks",
        data() {
            return {
                notFound: "",
                liste: {
                    data: {
                        data: ""
                    }
                },

            }
        },
        mounted() {
            this.page = this.$store.state.count;
            axios.get('/books/json?page=' + this.page)
                .then(response => {
                        if (response.data.data.length === 0) {
                            this.notFound = true;
                        } else {
                            this.notFound = false;
                            this.liste.data.data = response.data.data;
                            this.isLoggedInCheck();
                            this.saveContent(response.data.data);
                        }
                    }
                );

        }
    }
</script>

<style scoped>

</style>