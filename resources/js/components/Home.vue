<template>
    <div class="body">
        <div class="searchBox">
            <div class="page_title"><h1 style="color: white; text-shadow: 3px 3px 0 black; padding: 1em">Bibliothek
                Wiener Linien</h1>
            </div>

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

        <div class="books">
            <div>
                <h2>Top-Bücher</h2>
            </div>
            <div style="border: black solid 1px">
                <h2>Neuerscheinungen</h2>
            </div>
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
            this.$store.state.warenkorb = false;
            this.search = "";
        },
        methods: {
            ausgabe: function () {
                if (this.search !== "") {
                    this.$store.state.latestSearch = this.search;
                    this.$store.commit("setSearch");
                    this.$store.commit("isFirstPage");
                    this.$router.push({path: "/list"});
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
        align-items: flex-start;
        justify-content: center;
        background-size: cover;
        flex-wrap: wrap;
        height: calc(100vh);
    }

    .searchBar {
        width: 50em;
        vertical-align: top;
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

    .books {
        display: flex;
        height: 200px;
        flex-direction: column;
        justify-content: center;
    }

    .btn {
        background-color: rgb(30, 30, 133);
        border-color: rgb(30, 30, 133);
    }
</style>