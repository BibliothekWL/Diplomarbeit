<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <div id="body">

        <!---------------------------------------------------------

                                  Search

        ---------------------------------------------------------->

        <div class="searchBox">
            <div class="page_title"><h1 style="color: white; text-shadow: 3px 3px 0 black; padding: 1em">Bibliothek
                Wiener Linien</h1>
            </div>

            <b-input-group class="searchBar">
                <b-input-group-append>
                    <b-button v-show="showalpha" v-on:click="showalphaChange()">
                        <font-awesome-icon icon="sort-alpha-down-alt"></font-awesome-icon>
                    </b-button>

                    <b-button v-show="!showalpha" v-on:click="showalphaChange()">
                        <font-awesome-icon icon="sort-alpha-down"></font-awesome-icon>
                    </b-button>
                </b-input-group-append>

                <b-input class="search" placeholder="Nach Büchern suchen" type="search" v-model="search" debounce="20"
                         v-on:keyup="ausgabe()">
                </b-input>

                <b-input-group-append>
                    <b-button v-on:click="ausgabe()">
                        <font-awesome-icon icon="search"></font-awesome-icon>
                    </b-button>

                    <b-button v-b-modal.Filter>
                        <font-awesome-icon icon="filter"></font-awesome-icon>
                    </b-button>
                </b-input-group-append>
            </b-input-group>
        </div>

        <!---------------------------------------------------------

                                View-Body

        ---------------------------------------------------------->

        <b-button v-if="isAdmin" type="light" class="addButton" pill v-b-modal.AddItem
                  v-on:click="addItem(liste.length)"
                  v-b-popover.hover.bottom="''" title="Buch erstellen">
            <font-awesome-icon icon="plus"/>
        </b-button>

        <b-dropdown id="dropdown-dropdown" v-model="item_size" class="BooksCount" :text="item_size"
                    v-b-popover.hover.left="''" title="Bücher pro Seite">
            <b-dropdown-item v-on:click="setItemSize(6)">6</b-dropdown-item>
            <b-dropdown-item v-on:click="setItemSize(12)">12</b-dropdown-item>
            <b-dropdown-item v-on:click="setItemSize(18)">18</b-dropdown-item>
            <b-dropdown-item v-on:click="setItemSize(24)">24</b-dropdown-item>
            <b-dropdown-item v-on:click="setItemSize(30)">30</b-dropdown-item>
        </b-dropdown>

        <b-button class="warenkorb" v-if="isLoggedIn && !isAdmin" to="/warenkorb" variant="light"
                  v-b-popover.hover.left="''" title="Warenkorb">
            <font-awesome-icon icon="shopping-cart" class="fa-lg"></font-awesome-icon>
            <b-badge variant="transparent">{{cart_count}}</b-badge>
        </b-button>


        <div v-if="filter_show" id="filter" class="filter">
            <h5 class="tags">Filter: </h5>
            <h5 v-for="tag in filterList" class="tags">
                <b-badge pill variant="dark">{{tag}}</b-badge>
            </h5>
            <b-button v-on:click="clearFilter">
                <font-awesome-icon icon="trash"/>
            </b-button>
        </div>

        <div v-if="!filter_show" class="nofilter">
        </div>

        <div v-if="!notFound" class="UserViewBody">

            <div class="list">
                <div v-for="book in liste.data.data" class="listitem"
                     v-on:click="buecherInformationen(book.id, book.title, book.systematik, book.systematik_long, book.category, book.medium, book.content, book.BNR)"
                     v-b-modal.BookInformation>
                    <div class="card_flex">
                        <div class="bildbruh">&#160;</div>

                        <div class="text">
                            <div class="book_title">
                                {{title_short[book.id]}}
                            </div>

                            <div class="beschreibung">
                                {{content_short[book.id]}}
                            </div>
                        </div>

                        <div v-if="book.borrowed === 0  & !cartList.includes(book.id)" class="info frei">
                            Frei
                        </div>

                        <div v-if="book.borrowed === 0  & cartList.includes(book.id)" class="info reserviert">
                            Reserviert
                        </div>

                        <div v-if="book.borrowed === 1" class="info borrowed">
                            Ausgeborgt
                        </div>
                    </div>
                </div>

                <div v-if="platzhalter" class="listitem" style="cursor: auto; border: 0 black solid"></div>
            </div>
        </div>

        <!---------------------------------------------------------

                                NoBooksFound

        ---------------------------------------------------------->

        <h4 v-if="notFound" class="notFound">
            Leider nichts gefunden! Bitte suchen Sie einen anderen Begriff oder
            versuchen Sie es später noch einmal.
        </h4>

        <!---------------------------------------------------------

                                Pagingbuttons

        ---------------------------------------------------------->

        <div class="page_buttons">
            <div class="paging_buttons">
                <b-button v-on:click="sendtoFirst()" :disabled=isAnfang id="popover-manual-1" title="Erste Seite">
                    <font-awesome-icon icon="angle-double-left"></font-awesome-icon>
                </b-button>

                <b-popover target="popover-manual-1" placement="top" triggers="hover" :show.sync="pop1"
                           title="Erste Seite">
                </b-popover>
            </div>

            <div class="paging_buttons">
                <b-button v-on:click="decrement()" :disabled=isAnfang id="popover-manual-2"
                          title="Eine Seite zurück">
                    <font-awesome-icon icon="angle-left"></font-awesome-icon>
                </b-button>

                <b-popover target="popover-manual-2" placement="top" triggers="hover" :show.sync="pop2"
                           title="Eine Seite zurück">
                </b-popover>
            </div>

            <b-button class="paging_buttons" disabled>{{page}}</b-button>

            <div class="paging_buttons">
                <b-button v-on:click="increment()" :disabled=isEnde id="popover-manual-3"
                          title="Eine Seite weiter">
                    <font-awesome-icon icon="angle-right"></font-awesome-icon>
                </b-button>

                <b-popover target="popover-manual-3" placement="top" triggers="hover" :show.sync="pop3"
                           title="Eine Seite weiter">
                </b-popover>
            </div>

            <div class="paging_buttons">
                <b-button v-on:click="sendtoLast()" :disabled=isEnde id="popover-manual-4" title="Letzte Seite">
                    <font-awesome-icon class="secondary" icon="angle-double-right"></font-awesome-icon>
                </b-button>

                <b-popover target="popover-manual-4" placement="top" triggers="hover" :show.sync="pop4"
                           title="Letzte Seite">
                </b-popover>
            </div>
        </div>

        <!---------------------------------------------------------

                                  NachOben

        ---------------------------------------------------------->

        <go-top
                :size="45"
                :right="30"
                :bottom="30"
                bg-color="#6C747F"
                v-b-popover.hover.left="''"
                title="Nach Oben">
        </go-top>

        <!---------------------------------------------------------

                                   Modals

        ---------------------------------------------------------->

        <div>

            <!---------------------------------------------------------

                                      AddItem

            ---------------------------------------------------------->

            <b-modal id="AddItem" ref="modal" centered title="Buch erstellen"
                     @ok="saveAdd(title_string, systematik, medium, content_string, BNR, value, systematik_long, category)">
                <form ref="form">
                    <b-form-group
                            label="Title"
                            label-for="title"
                            invalid-feedback="Title is required"
                    >

                        <b-form-input
                                id="name-input"
                                v-model="title_string"
                                required
                        ></b-form-input>
                    </b-form-group>

                    <b-form-group
                            label="Systematik"
                            label-for="title"
                            invalid-feedback="Systematik is required"
                    >

                        <template>
                            <b-form-input list="systematikAdd" v-model="systematik"></b-form-input>

                            <datalist id="systematikAdd">
                                <option v-for="systematik in systematiken">{{ systematik }}</option>
                            </datalist>
                        </template>

                    </b-form-group>

                    <b-form-group
                            label="Medium"
                            label-for="title"
                            invalid-feedback="Medium is required"
                    >

                        <template>
                            <b-form-input list="medienAdd" v-model="medium"></b-form-input>

                            <datalist id="medienAdd">
                                <option v-for="medium in medien">{{ medium }}</option>
                            </datalist>
                        </template>

                    </b-form-group>

                    <b-form-group
                            label="Autoren"
                            label-for="title"
                            invalid-feedback="Autor is required"
                    >

                        <template>
                            <div>
                                <div>
                                    <multiselect v-model="value" :options="options" :multiple="true"
                                                 :allow-empty="false"
                                                 :close-on-select="false" :clear-on-select="false"
                                                 :preserve-search="true" placeholder="Wählen Sie Autoren aus"
                                                 label="name"
                                                 track-by="name">
                                    </multiselect>
                                </div>
                            </div>
                        </template>

                    </b-form-group>

                    <b-form-group
                            label="Content"
                            label-for="title"
                            invalid-feedback="Content is required"
                    >
                        <b-form-textarea
                                id="name-input"
                                v-model="content_string"
                                required
                        ></b-form-textarea>
                    </b-form-group>

                    <b-form-group
                            label="BNR"
                            label-for="title"
                            invalid-feedback="BNR is required"
                    >
                        <b-form-input
                                id="name-input"
                                v-model="BNR"
                                type="number"
                                required
                        ></b-form-input>
                    </b-form-group>
                </form>
            </b-modal>

            <!---------------------------------------------------------

                                    EditItem

            ---------------------------------------------------------->

            <b-modal id="EditItem" centered title="Edit Book"
                     @ok="saveEdit(id, title_string, systematik, medium, content_string, BNR, value, systematik_long, category)">
                <b-form-group
                        label="Title"
                        label-for="title"
                        invalid-feedback="Title is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="title_string"
                            required
                    ></b-form-input>
                </b-form-group>

                <b-form-group
                        label="Systematik"
                        label-for="title"
                        invalid-feedback="Systematik is required"
                >

                    <template>
                        <b-form-input list="systematikEdit" v-model="systematik"></b-form-input>

                        <datalist id="systematikEdit">
                            <option v-for="systematik in systematiken">{{ systematik }}</option>
                        </datalist>
                    </template>

                </b-form-group>

                <b-form-group
                        label="Medium"
                        label-for="title"
                        invalid-feedback="Medium is required"
                >

                    <template>
                        <b-form-input list="medienEdit" v-model="medium"></b-form-input>

                        <datalist id="medienEdit">
                            <option v-for="medium in medien">{{ medium }}</option>
                        </datalist>
                    </template>

                </b-form-group>

                <b-form-group
                        label="Autoren"
                        label-for="title"
                        invalid-feedback="Autor is required"
                >

                    <template>
                        <div>
                            <multiselect v-model="value" :options="options" :multiple="true" :close-on-select="false"
                                         :clear-on-select="false" :preserve-search="true"
                                         placeholder="Wählen Sie Autoren aus" label="name" track-by="name">
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span
                                        class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span>
                                </template>
                            </multiselect>
                        </div>
                    </template>

                </b-form-group>

                <b-form-group
                        label="Content"
                        label-for="title"
                        invalid-feedback="Content is required"
                >
                    <b-form-textarea
                            id="name-input"
                            v-model="content_string"
                            required
                    ></b-form-textarea>
                </b-form-group>

                <b-form-group
                        label="BNR"
                        label-for="title"
                        invalid-feedback="BNR is required"
                >
                    <b-form-input
                            id="name-input"
                            v-model="BNR"
                            type="number"
                            required
                    ></b-form-input>
                </b-form-group>
            </b-modal>

            <!---------------------------------------------------------

                                        Filter

            ---------------------------------------------------------->

            <b-modal id="Filter" centered title="Filter"
                     @ok="setFilter()">
                <b-form-group
                        label="Systematik"
                        label-for="title"
                        invalid-feedback="Systematik is required"
                >
                    <template>
                        <b-form-input v-on:keyup.enter="setFilter()" v-model="filter_systematik"
                                      list="statistikFilter"></b-form-input>

                        <datalist id="statistikFilter">
                            <option v-for="systematik in systematiken">{{ systematik }}</option>
                        </datalist>
                    </template>

                </b-form-group>

                <b-form-group
                        label="Medium"
                        label-for="title"
                        invalid-feedback="Medium is required"
                >
                    <template>
                        <b-form-input v-on:keyup.enter="setFilter()" v-model="filter_medium"
                                      list="medienFilter"></b-form-input>

                        <datalist id="medienFilter">
                            <option v-for="medium in medien">{{ medium }}</option>
                        </datalist>
                    </template>
                </b-form-group>
            </b-modal>

            <!---------------------------------------------------------

                                 Bookinformation

            ---------------------------------------------------------->

            <b-modal
                    id="BookInformation"
                    ref="BookInformation"
                    :title=title_string
                    centered
            >

                <div class="bookInformation">
                    {{ content_string }}

                    <br>
                    <br>

                    <b>Autor/en:</b> <div v-for="autor in autoren"> {{autor}} <br></div>
                </div>

                <template v-slot:modal-footer="{cancel}">
                    <div v-if="isLoggedIn">
                        <div v-if="isAdmin">
                            <b-button pill v-on:click="deleteItem(id)" v-b-popover.hover.top title="Buch löschen">
                                <font-awesome-icon icon="trash"></font-awesome-icon>
                            </b-button>

                            <b-button v-b-modal.EditItem pill v-on:click="editItem(id)" v-b-popover.hover.top
                                      title="Buch bearbeiten">
                                <font-awesome-icon icon="pen"></font-awesome-icon>
                            </b-button>

                            <b-button :disabled="!isBorrowed" pill v-on:click="returnBook(id)" v-b-popover.hover.top
                                      title="Buch zurückgeben">
                                <font-awesome-icon icon="level-up-alt" class="fa-rotate-270"></font-awesome-icon>
                            </b-button>
                        </div>

                        <div v-if="!isAdmin">
                            <b-button :disabled="isBorrowed" pill v-on:click="putIntoCart(id)" v-b-popover.hover.top
                                      title="Buch reservieren">
                                <font-awesome-icon icon="cart-plus"></font-awesome-icon>
                            </b-button>
                        </div>
                    </div>

                    <div v-if="!isLoggedIn">
                        <b-button v-show="false" size="sm" variant="success" @click="cancel()">
                            Close
                        </b-button>
                    </div>
                </template>
            </b-modal>
        </div>

    </div>
</template>

<script>
    import axios from 'axios';
    import Swal from 'sweetalert2';
    import GoTop from '@inotom/vue-go-top';
    import Multiselect from 'vue-multiselect'

    export default {
        name: "BookList",
        components: {
            GoTop,
            Multiselect
        },
        data() {
            return {
                page: 1,
                notFound: false,
                isAdmin: this.$store.state.isAdmin,
                isLoggedIn: this.$store.state.isLoggedIn,
                isBorrowed: "",
                liste: {
                    data: {
                        data: ""
                    }
                },
                lastPage: 0,
                id: "",
                title_string: "",
                title: [],
                title_short: [],
                systematik: "",
                systematik_long: "",
                category: "",
                medium: "",
                BNR: "",
                content_string: "",
                content: [],
                content_short: [],
                dialog_title: "",
                search: "",
                isAnfang: false,
                isEnde: false,
                show: true,
                reserviert: false,
                platzhalter: false,
                systematiken: [],
                medien: [],
                options: [],
                autor: [],
                showalpha: this.$store.state.showalpha,
                filter_medium: null,
                filter_systematik: null,
                filterList: [],
                filter_show: false,
                value: [],
                item_size: '6',
                cart_count: 0,
                cartList: [],
                pop1: false,
                pop2: false,
                pop3: false,
                pop4: false,
                autoren: []
            };
        },
        mounted() {
            this.isAnfang = true;
            this.isEnde = true;
            this.isLoggedInCheck();
            this.setCartCount();
            this.ausgabe();
        },
        watch: {
            '$store.state.cart_count': {
                handler() {
                    this.cart_count = this.$store.state.cart_count;
                },
                immediate: true
            }
        },
        methods: {
            isLoggedInCheck: function () {
                axios.get('/session')
                    .then(response => {
                            this.$store.state.isLoggedIn = response.data;
                            if (response.data) {
                                this.$store.commit('UserLoggedIn');
                            } else {
                                this.$store.commit('UsernotLoggedIn');
                            }
                        }
                    )
            },
            deleteItem: function (id) {
                axios.post('/books/delete/json/', {
                    id: id
                }).then(response => {
                        if (response.data.status === 200) {
                            Swal.fire({
                                title: 'Erfolg!',
                                text: 'Das ausgewählte Buch wurde erfolgreich gelöscht!',
                                icon: 'success'
                            });

                            this.$refs['BookInformation'].toggle();
                            this.ausgabe();
                        } else {
                            Swal.fire({
                                title: 'Fehler!',
                                text: 'Das ausgewählte Buch konnte nicht gelöscht werden!',
                                icon: 'error'
                            });
                        }
                    }
                )
            },
            editItem: function (id) {
                this.id = id;
                axios.post('/getBook', {
                    id: id
                }).then(response => {
                        this.title_string = response.data.title;
                        this.content_string = response.data.content;
                        this.systematik = response.data.systematik;
                        this.medium = response.data.medium;
                        this.BNR = response.data.BNR;
                        this.systematik_long = response.data.systematik_long;
                        this.category = response.data.category;
                        this.ausgabe();
                    }
                );
            },
            addItem: function () {
                this.title_string = "";
                this.content_string = "";
                this.systematik = "";
                this.medium = "";
                this.BNR = null;
                this.systematik_long = "irgendetwas";
                this.category = "Fachbuch";
                this.value = [];
            },
            saveAdd: function (title, systematik, medium, content, BNR, name, systematik_long, category) {
                for (let i = 0; i < name.length; i++) {
                    name[i] = name[i].name;
                }

                console.log(name);

                axios.post('/books/create/json/', {
                    title: title,
                    systematik: systematik,
                    medium: medium,
                    content: content,
                    BNR: BNR,
                    authorname: name,
                    systematik_long: systematik_long,
                    category: category
                }).then(response => {
                        if (response.data.status === 200) {
                            this.id = "";
                            this.title_string = "";
                            this.content_string = "";
                            this.systematik = "";
                            this.medium = "";
                            this.BNR = "";
                            Swal.fire({
                                title: 'Erfolg!',
                                text: 'Das ausgewählte Buch wurde erfolgreich erstellt!',
                                icon: 'success'
                            });
                        } else {
                            Swal.fire({
                                title: 'Fehler!',
                                text: 'Das ausgewählte Buch konnte nicht erstellt werden!',
                                icon: 'error'
                            });
                        }
                        this.ausgabe();
                    }
                )
            },
            saveEdit: function (id, title, systematik, medium, content, BNR, name, systematik_long, category) {
                for (let i = 0; i < name.length; i++) {
                    name[i] = name[i].name;
                }

                axios.post('/books/edit/json/', {
                    id: id,
                    title: title,
                    systematik: systematik,
                    medium: medium,
                    content: content,
                    BNR: BNR,
                    authorname: name,
                    systematik_long: systematik_long,
                    category: category
                })
                    .then(response => {
                        this.$refs['BookInformation'].toggle();
                        console.log(response);
                        if (response.data.status === 200) {
                            Swal.fire({
                                title: 'Erfolg!',
                                text: 'Das ausgewählte Buch wurde erfolgreich bearbeitet!',
                                icon: 'success'
                            });
                        } else {
                            Swal.fire({
                                title: 'Fehler!',
                                text: 'Das ausgewählte Buch konnte nicht bearbeitet werden!',
                                icon: 'error'
                            });
                        }
                        this.ausgabe();
                    })
            },
            saveContent: function (content) {
                for (let i = 0; i < content.length; i++) {
                    this.content[content[i].id] = content[i].content;
                    let content_words = content[i].content.split(" ");
                    if (content_words.length >= 8) {
                        this.content_short[content[i].id] = "";
                        for (let j = 0; j < 7; j++) {
                            this.content_short[content[i].id] += content_words[j] + " ";
                        }
                        this.content_short[content[i].id] += "...";
                    } else {
                        this.content_short[content[i].id] = content[i].content;
                    }
                }
            },
            saveTitle: function (title) {
                for (let i = 0; i < title.length; i++) {
                    this.title[title[i].id] = title[i].title;
                    let title_words = title[i].title.split(" ");
                    if (title_words.length >= 4) {
                        this.title_short[title[i].id] = "";
                        for (let j = 0; j < 3; j++) {
                            this.title_short[title[i].id] += title_words[j] + " ";
                        }
                        this.title_short[title[i].id] += "...";
                    } else {
                        this.title_short[title[i].id] = title[i].title;
                    }
                }
            },
            buecherInformationen: function (id, title, systematik, systematik_long, category, medium, content, BNR) {
                this.id = id;
                this.title_string = title;
                this.content_string = content;
                this.systematik = systematik;
                this.systematik_long = systematik_long;
                this.category = category;
                this.medium = medium;
                this.BNR = BNR;
                this.value = [];
                this.autoren = [];

                axios.post('book/authors', {
                    id: id
                }).then(response => {
                    for (let i = 0; i < response.data.length; i++) {
                        this.value.push({name: response.data[i][0]});
                    }

                    for (let i = 0; i < this.value.length; i++) {
                        this.autoren[i] = this.value[i].name;
                    }

                    console.log(this.autoren);
                });

                axios.post('/books/borrowed', {
                    id: id
                }).then(response => {
                        this.isBorrowed = response.data;
                    }
                );
            },
            ausgabe: function () {
                this.getSystematik();
                this.getMedium();
                this.getAuthor();
                this.getCart();

                this.filterList = [];
                switch (this.filter_systematik) {
                    case null:
                        break;
                    default:
                        this.filterList.push(this.filter_systematik);
                }

                switch (this.filter_medium) {
                    case null:
                        break;
                    default:
                        this.filterList.push(this.filter_medium);
                }

                this.filter_show = this.filterList.length > 0;
                if (this.$store.state.search !== "") {
                    this.search = this.$store.state.search;
                    this.$store.commit("setSearchEmpty");
                }
                if (this.search === "") {
                    axios.post('/books/json?page=' + this.page, {
                        sortDirection: this.showalpha,
                        medium: this.filter_medium,
                        systematik: this.filter_systematik,
                        author: null,
                        isBorrowed: null,
                        isNotBorrowed: null,
                        item_size: this.item_size
                    })
                        .then(response => {
                                if (response.data.data.length === 0) {
                                    this.page = 1;
                                    this.notFound = true;
                                    this.isAnfang = true;
                                    this.isEnde = true;
                                } else {
                                    this.platzhalter = response.data.data.length % 2 !== 0;
                                    this.notFound = false;
                                    this.liste.data.data = response.data.data;
                                    this.lastPage = response.data.last_page;
                                    this.saveContent(response.data.data);
                                    this.saveTitle(response.data.data);
                                    this.isAnfangfind();
                                    this.isEndefind();
                                }
                            }
                        );
                } else {
                    this.page = 1;
                    axios.post('/books/search?page=' + this.page, {
                        search: this.search,
                        sortDirection: this.showalpha,
                        medium: this.filter_medium,
                        systematik: this.filter_systematik,
                        author: null,
                        isBorrowed: null,
                        isNotBorrowed: null,
                        item_size: this.item_size
                    })
                        .then(response => {
                                if (response.data.data.length === 0) {
                                    this.notFound = true;
                                } else {
                                    this.platzhalter = response.data.data.length % 2 !== 0;
                                    this.notFound = false;
                                    this.liste.data.data = response.data.data;
                                    this.lastPage = response.data.last_page;
                                    this.$store.state.lastPage = this.lastPage;
                                    this.saveContent(response.data.data);
                                    this.saveTitle(response.data.data);
                                    this.isAnfangfind();
                                    this.isEndefind();
                                }
                            }
                        );
                }
            },
            isAnfangfind: function () {
                this.isAnfang = this.page === 1;
            },
            isEndefind: function () {
                this.isEnde = this.page === this.lastPage;
            },
            increment: function () {
                this.page++;
                this.isAnfang = true;
                this.isEnde = true;
                this.pop3 = false;
                this.ausgabe();
            },
            decrement: function () {
                this.page--;
                this.isAnfang = true;
                this.isEnde = true;
                this.pop2 = false;
                this.ausgabe();
            },
            sendtoFirst: function () {
                this.isAnfang = true;
                this.isEnde = true;
                this.page = 1;
                this.pop1 = false;
                this.ausgabe();
            },
            sendtoLast: function () {
                this.isAnfang = true;
                this.isEnde = true;
                this.page = this.lastPage;
                this.pop4 = false;
                this.ausgabe();
            },
            returnBook: function (id) {
                axios.post('/returnBooks', {
                    id: id
                }).then(response => {
                        this.$refs['BookInformation'].toggle();
                        if (response.data.status === 200) {
                            Swal.fire({
                                title: 'Erfolg!',
                                text: 'Das ausgewählte Buch wurde erfolgreich zurückgegeben!',
                                icon: 'success'
                            });
                            this.ausgabe();
                        } else {
                            Swal.fire({
                                title: 'Fehler!',
                                text: 'Das ausgewählte Buch konnte nicht zurückgegeben werden!',
                                icon: 'error'
                            });
                        }
                    }
                )
            },
            putIntoCart: function (id) {
                axios.post('/books/borrow', {
                    id: id,
                    userID: this.$store.state.userID
                }).then(response => {
                        if (response.data.status === 200) {
                            this.$store.state.latestCartCount++;
                            this.$store.commit('setCartCount');
                            this.ausgabe();
                            Swal.fire({
                                title: 'Erfolg!',
                                text: 'Ihr Buch befindet sich nun im Warenkorb!',
                                icon: 'success'
                            });
                        } else {
                            Swal.fire({
                                title: 'Fehler!',
                                text: response.data.statusMessage,
                                icon: 'error'
                            });
                        }
                        this.$refs['BookInformation'].toggle();
                    }
                )
            },
            getSystematik: function () {
                axios.get('/systematik/json')
                    .then(response => {
                            this.systematiken = response.data;
                        }
                    )
            },
            getMedium: function () {
                axios.get('/medium/json')
                    .then(response => {
                            this.medien = response.data;
                        }
                    )
            },
            getAuthor: function () {
                axios.get('/authors/json')
                    .then(response => {
                            for (let i = 0; i < response.data.length; i++) {
                                this.options.push({name: response.data[i]});
                            }
                        }
                    )
            },
            getCart: function () {
                axios.get('/carts/json')
                    .then(response => {
                            this.cartList = response.data
                        }
                    );
            },
            showalphaChange: function () {
                this.showalpha = !this.showalpha;
                this.$store.state.showalpha = this.showalpha;
                this.ausgabe();
            },
            setFilter: function () {
                this.ausgabe();
            },
            setItemSize: function (size) {
                this.item_size = size + '';
                this.page = 1;
                this.ausgabe();
            },
            setCartCount: function () {
                axios.post('/cart/json', {
                    id: this.$store.state.userdata.id
                }).then(response => {
                        this.cart_count = response.data.length;
                        this.$store.state.latestCartCount = this.cart_count;
                        this.$store.commit('setCartCount');
                    }
                );
            },
            clearFilter: function () {
                this.filter_systematik = null;
                this.filter_medium = null;
                this.filterList = [];
                this.ausgabe();
                this.filter_show = false;
            }
        }
    }

</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>

    .UserViewBody {
        display: flex;
        justify-content: center;
        width: 100%;
    }

    .notFound {
        text-align: center;
        padding: 8em;
    }

    .list {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: right;
        padding: 4em 4em 0 8em;
    }

    .list > * {
        flex-basis: 30%;
        flex-grow: 1;
        flex-shrink: 1;
    }

    .listitem {
        margin: 2em;
        border: 1px black solid;
        border-radius: 15px;
    }

    .card_flex {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
    }

    .listitem:hover {
        cursor: pointer;
    }

    .beschreibung {
        font-size: 14px;
        width: 13em;
    }

    .page_buttons {
        width: 100%;
        display: flex;
        justify-content: center;
        text-align: center;
        padding: 2em;
        color: white;
    }

    .addButton {
        float: right;
        margin: 1.2em;
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

    .frei {
        border: 1px green solid;
        border-radius: 10px;
        color: green;
        width: 3em;
        padding: 0.25em;
        margin: 1em;
        text-align: center;
    }

    .borrowed {
        border: 1px red solid;
        border-radius: 10px;
        color: red;
        width: 6em;
        padding: 0.25em;
        margin: 1em;
        text-align: center;
    }

    .reserviert {
        border: 1px #ff9900 solid;
        border-radius: 10px;
        color: #ff9900;
        width: 6em;
        padding: 0.25em;
        margin: 1em;
        text-align: center;
    }

    .bildbruh {
        background-image: url("../../img/default_cover.jpg");
        width: 125px;
        height: 167px;
        border-radius: 15px;
    }

    .book_title {
        font-family: "Nunito", sans-serif;
        font-size: 1.2em;
        font-weight: bold;
    }

    .text {
        height: 7em;
        width: 14em;
        margin: 1.5em;
        display: flex;
        flex-direction: column;
        justify-content: space-around;
    }

    .bookInformation {
        text-align: start;
    }

    .filter {
        display: flex;
        position: absolute;
        justify-content: center;
        align-items: center;
        float: left;
        margin: 1em 0 0 10em;
        border: 1px black solid;
        border-radius: 5px;
        padding: 0.5em;
    }

    .nofilter {
        float: left;
        position: absolute;
        margin: 1em 0 0 10em;
    }

    .tags {
        margin-right: 1em;
    }

    .warenkorb {
        cursor: pointer;
        position: fixed;
        z-index: 1000;
        border: 1px black solid;
        top: 1em;
        right: 1em;
    }

    .BooksCount {
        position: absolute;
        right: 11em;
        top: 16em;
    }

    .paging_buttons {
        margin: .1em;
    }
</style>