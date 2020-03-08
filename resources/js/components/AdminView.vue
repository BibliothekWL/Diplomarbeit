<template>
    <div id="body">
        <div class="col-8" style="margin: auto; text-align: center; color: black;">
            <h1 style="color: black;">Ausgeborgte Buecher</h1>
            <table>
                <thead>
                    <tr>
                        <th scope="col">Transakionsnummer</th>
                        <th scope="col">Nutzer</th>
                        <th scope="col">Nutzer-ID</th>
                        <th scope="col">Buchtitel</th>
                        <th scope="col">Buch-ID</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="book in userdata">
                        <td>{{book.id}}</td>
                        <td>ExampleName</td>
                        <td>{{book.user_id}}</td>
                        <td>ExampleBook</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "AdminView",
        data() {
            return {
                userdata: null
            }
        },
        methods: {
            getBorrowed: function () {
                axios.get('/getBorrowings').then(response => {
                    console.log(response);
                    this.userdata = response.data;
                })
            },
            getUsername: function (id) {
                axios.post('/userdata/json', {id: id}).then(response => {
                    return response.data.name
                })
            },
            getBookTitle: function (id) {
                axios.post('/getBook', {id: id}).then(response => {
                    return response.data.title;
                })
            },
            mounted() {
                this.getBorrowed();
            }
        }
    }
</script>

<style>

</style>