<template>
    <div class="body">
        <p class="title center">Profil</p>

        <div class="content">
            <div class="content_item">
                <h4>Username: {{userdata.name}}</h4>
                <b-button style="width: 10em;" variant="outline-dark">Change Username</b-button>
            </div>

            <div class="content_item">
                <h4>E-Mail: {{userdata.email}}</h4>
                <div></div>
            </div>

            <div class="content_item">
                <h4>Password: ••••••••</h4>
                <b-button style="width: 10em;" variant="outline-dark">Change Password</b-button>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
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
        methods: {}
    }
</script>

<style scoped>

    .center {
        text-align: center;
    }

    .title {
        font-size: 3em;
        padding-top: 1em;
    }

    .body {
        background: linear-gradient(to bottom, rgba(217, 83, 79, 0.9), rgba(211, 211, 211, 1));
        height: 100vh;
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

</style>