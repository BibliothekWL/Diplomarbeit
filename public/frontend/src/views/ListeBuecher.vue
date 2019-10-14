<template>
    <v-container fluid>
        <div v-for="n in liste">
            <h1>{{n.chartName}}</h1>
            <div v-for="n1 in n.bpi" style="display: flex; justify-content: space-between; padding: 2em">
                <div>
                    {{n1.code}}<br>{{n1.rate}}<br>{{n1.description}}
                </div>

                <div>
                    <v-btn class="mx-2" fab dark small color="primary">
                        <v-icon dark>mdi-delete</v-icon>
                    </v-btn>
                    <v-btn class="mx-2" fab dark small color="primary">
                        <v-icon dark>mdi-pencil</v-icon>
                    </v-btn>
                </div>
            </div>
        </div>
    </v-container>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "ListeBuecher",
        data() {
            return {
                liste: []
            };
        },
        mounted() {
            axios.get('https://api.coindesk.com/v1/bpi/currentprice.json')
                .then(response =>
                    (
                        console.log(response),
                            this.liste = response
                    )
                )
        }
    }

</script>

<style scoped>

</style>