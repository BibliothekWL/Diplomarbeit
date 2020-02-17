<template>
    <div class="body">
        <div class="searchBox">
            <div class="page_title">
                <h1 style="color: white; text-shadow: 3px 3px 0px black; padding: 1em">Profil</h1>
            </div>
        </div>

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