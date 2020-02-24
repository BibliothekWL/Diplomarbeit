import Vue from 'vue'
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        isAdmin: false,
        isLoggedIn: false,
        search: "",
        searchAuthor: "",
        username: "",
        userID: 0,
        warenkorb: false,
        cart_count: 0,
        showalpha: true,
    },
    plugins: [createPersistedState()],
    mutations: {
        UsernotLoggedIn: state => state.isLoggedIn = false,
        UserLoggedIn: state => state.isLoggedIn = true,
    }
});
