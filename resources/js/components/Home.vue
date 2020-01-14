<template>
    <div class="body">
        <div class="searchBox">
            <b-input-group class="searchBar">
                <b-input class="search" placeholder="Nach Büchern stöbern" type="search"
                         v-model="search" v-on:keyup.enter="ausgabe()"></b-input>
                <b-input-group-append>
                    <b-button v-on:click="ausgabe()">
                        <font-awesome-icon icon="search"></font-awesome-icon>
                    </b-button>
                    <b-button>
                        <font-awesome-icon icon="filter"></font-awesome-icon>
                    </b-button>
                </b-input-group-append>
            </b-input-group>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Home",
        data() {
            return {
                search: this.$store.state.search,
            }
        },
        mounted() {
            this.$store.commit("UserisNotInCart_2");
            this.$store.commit("UserisInCart_2");
            this.search = "";
        },
        methods: {
            ausgabe: function () {
                if (this.search === "") {

                } else {
                    this.$store.state.latestSearch = this.search;
                    this.$store.commit("setSearch");
                    this.$store.commit("isFirstPage");
                    window.location.href = "/list";
                }
            }
        }
    }
</script>

<style scoped>
    html {
        overflow: hidden;
    }

    .body {
        display: flex;
        align-items: center;
        justify-content: center;
        background-image: url("../../img/bg_hp.jpg");
        background-size: cover;
        height: calc(100vh);
    }

    .searchBar {
        width: 50em;
        vertical-align: center;
    }

    .searchBox {
        display: flex;
        justify-content: center;
        padding: 2em;
    }
</style>