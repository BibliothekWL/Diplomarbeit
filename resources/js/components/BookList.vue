<<<<<<< HEAD
<template>
    <div class="container">
        <b-button pill v-b-modal.AddItem v-on:click="addItem(liste.data.length)">
            <font-awesome-icon icon="plus"/>
        </b-button>

        <h2>Bücherliste</h2>
        <div v-for="n in liste.data" class="list">
            <div class="listItem">
                <div v-on:click="buecherInformationen(n.id)">
                    <h2>{{n.id}} {{n.title}}</h2><br> <h5>{{content_short[n.id - 1]}}</h5>
                </div>
                <div>
                    <b-button pill v-on:click="deleteItem(n.id)">
                        <font-awesome-icon icon="trash"></font-awesome-icon>
                    </b-button>

                    <b-button v-b-modal.EditItem pill v-on:click="editItem(n.id)">
                        <font-awesome-icon icon="pen"></font-awesome-icon>
                    </b-button>
                </div>
            </div>
        </div>

        <div>
            <b-modal id="AddItem" size="lg" centered title="Create Book"
                     @ok="saveAdd(id, title, systematik, medium, content_full, BNR)">
                <b-form-group
                        label="Title"
                        label-for="name-input"
                        invalid-feedback="Title is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="title"
                            required
                    ></b-form-input>
                </b-form-group>
            </b-modal>
        </div>

        <div>
            <b-modal id="EditItem" centered title="Edit Book"
                @ok="saveEdit(id, title, content_full)">
                <b-form-group
                        label="Title"
                        label-for="title"
                        invalid-feedback="Title is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="title"
                            required
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                        label="Content"
                        label-for="content_full"
                        invalid-feedback="Content is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="content_full"
                            required
                    ></b-form-input>
                </b-form-group>
            </b-modal>
        </div>

        <div>
            <b-modal id="BookInformation" centered title="Information">
                <div>
                    {{ content_full }}
                </div>
            </b-modal>
        </div>

    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "BookList",
        data() {
            return {
                liste: [],
                id: "",
                title: "",
                content_full: [],
                content_short: [],
                dialog_title: "",
            };
        },
        mounted() {
            axios.get('/books/json')
                .then(response =>
                    (
                        this.liste = response.data,
                            this.saveContent(response.data.data)
                    )
                )
        },
        methods: {
            deleteItem: function (id) {
                axios.get('/books/delete/json', {
                    params: {
                        id: id
                    }
                }).then(response => (
                        console.log(response)
                    )
                )
            },
            editItem: function (id) {
                this.title = this.liste.data[id - 1].title;
                this.content_full = this.liste.data[id - 1].content;
            },
            addItem: function (maxId) {
                this.id = maxId + 1;
                this.title = "sadsad";
                this.content_full.push("");
                this.systematik = "dsa";
                this.medium = "sa";
                this.BNR = "1111";
            },
            saveAdd: function (id, title, systematik, medium, content, BNR) {
                axios.post('books/create/', {
                    title: title,
                    systematik: systematik,
                    medium: medium,
                    content: content,
                    BNR: BNR
                }).then(response => (
                        console.log(response.data)
                    )
                )
            },
            saveEdit: function (id, title, content) {
                axios.post('books/edit', {
                    params: {
                        id: id,
                        title: title,
                        content: content
                    }
                }).then(response => (
                        console.log(response)
                    )
                )
            },
            saveContent: function (content) {
                for (let i = 0; i < content.length; i++) {
                    this.content_full[i] = content[i].content;
                    let content_words = content[i].content.split(" ");
                    if (content_words.length >= 12) {
                        this.content_short[i] = "";
                        for (let j = 0; j < 12; j++) {
                            this.content_short[i] += content_words[j] + " ";
                        }
                        this.content_short[i] += "...";
                    } else {
                        this.content_short[i] = content[i].content;
                    }
                }
            },
            buecherInformationen: function (id) {
                this.dialog1 = true;
                this.id = id;
            },
            borrowBook: function (id) {
                axios.get('/books/borrow', {
                    params: {
                        id: id
                    }
                }).then(response => (
                        console.log(response)
                    )
                )
            }
        }
    }

</script>

<style scoped>
    .list {
        padding: 2em;
    }

    .listItem {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 1px solid black;
    }

    .listItem:hover {
        cursor: pointer;
    }
</style>
||||||| merged common ancestors
=======
<template>
    <div class="container">
        <b-button pill v-b-modal.AddItem v-on:click="addItem(liste.data.length)">
            <font-awesome-icon icon="plus"/>
        </b-button>

        <h2>Bücherliste</h2>
        <div v-for="n in liste.data" class="list">
            <div class="listItem">
                <div v-on:click="buecherInformationen(n.id)" v-b-modal.BookInformation>
                    <h2>{{n.id}} {{n.title}}</h2><br> <h5>{{content_short[n.id - 1]}}</h5>
                </div>
                <div>
                    <b-button pill v-on:click="deleteItem(n.id)">
                        <font-awesome-icon icon="trash"></font-awesome-icon>
                    </b-button>

                    <b-button v-b-modal.EditItem pill v-on:click="editItem(n.id)">
                        <font-awesome-icon icon="pen"></font-awesome-icon>
                    </b-button>
                </div>
            </div>
        </div>

        <div>
            <b-modal id="AddItem" size="lg" centered title="Create Book"
                     @ok="saveAdd(id, title, systematik, medium, content_full, BNR)">
                <b-form-group
                        label="Title"
                        label-for="name-input"
                        invalid-feedback="Title is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="title"
                            required
                    ></b-form-input>
                </b-form-group>
            </b-modal>
        </div>

        <div>
            <b-modal id="EditItem" centered title="Edit Book"
                     @ok="saveEdit(id, title, content_full)">
                <b-form-group
                        label="Title"
                        label-for="title"
                        invalid-feedback="Title is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="title"
                            required
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                        label="Content"
                        label-for="content_full"
                        invalid-feedback="Content is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="content_full"
                            required
                    ></b-form-input>
                </b-form-group>
            </b-modal>
        </div>

        <div>
            <b-modal id="BookInformation" centered title="Information">
                <div>
                    {{ content_full[id] }}
                </div>
            </b-modal>
        </div>

    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "BookList",
        data() {
            return {
                liste: [],
                id: "",
                title: "",
                content_full: [],
                content_short: [],
                dialog_title: "",
            };
        },
        mounted() {
            axios.get('/books/json')
                .then(response =>
                    (
                        this.liste = response.data,
                            this.saveContent(response.data.data)
                    )
                )
        },
        methods: {
            deleteItem: function (id) {

                axios.post('/books/delete/json', {
                    id: id
                }).then(response => (
                        console.log(response)
                    )
                )
            },
            editItem: function (id) {
                this.id = id;
                this.title = this.liste.data[id - 1].title;
                this.content_full = this.liste.data[id - 1].content;
            },
            addItem: function (maxId) {
                this.id = maxId + 1;
                this.title = "sadsad";
                this.content_full.push("");
                this.systematik = "dsa";
                this.medium = "sa";
                this.BNR = "1111";
            },
            saveAdd: function (id, title, systematik, medium, content, BNR) {
                axios.post('books/create/json', {
                    title: title,
                    systematik: systematik,
                    medium: medium,
                    content: content,
                    BNR: BNR
                }).then(response => (
                        console.log(response.data)
                    )
                )
            },
            saveEdit: function (id, title, content) {
                console.log(id);
                axios.post('/books/' + id + '/edit/jsonvalidate/', {
                    title: title,
                    content: content
                }).then(response => (
                        console.log(response)
                    )
                )
            },
            saveContent: function (content) {
                for (let i = 0; i < content.length; i++) {
                    this.content_full[i] = content[i].content;
                    let content_words = content[i].content.split(" ");
                    if (content_words.length >= 12) {
                        this.content_short[i] = "";
                        for (let j = 0; j < 12; j++) {
                            this.content_short[i] += content_words[j] + " ";
                        }
                        this.content_short[i] += "...";
                    } else {
                        this.content_short[i] = content[i].content;
                    }
                }
            },
            buecherInformationen: function (id) {
                this.dialog1 = true;
                this.id = id - 1;
            },
            borrowBook: function (id) {
                axios.get('/books/borrow', {
                    params: {
                        id: id
                    }
                }).then(response => (
                        console.log(response)
                    )
                )
            }
        }
    }

</script>

<style scoped>
    .list {
        padding: 2em;
    }

    .listItem {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 1px solid black;
    }

    .listItem:hover {
        cursor: pointer;
    }
</style>
>>>>>>> 98a57a38ef34d57b6e329175058271c60e96746d
