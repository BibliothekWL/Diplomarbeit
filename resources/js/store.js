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
        latestUsername: "",
        username: "",
        latestUserID: 0,
        userID: 0,
        warenkorb: false,
        nichtwarenkorb: false,
        cart_count: 0,
        showalpha: true,
        filter_medium: null,
        filter_systematik: null
    },
    plugins: [createPersistedState()],
    mutations: {
        increment: state => state.page++,
        decrement: state => state.page--,
        UserisAdmin: state => state.isAdmin = true,
        UserisnotAdmin: state => state.isAdmin = false,
        UserLoggedIn: state => state.isLoggedIn = true,
        UsernotLoggedIn: state => state.isLoggedIn = false,
        setUsername: state => state.username = state.latestUsername,
        setUserID: state => state.userID = state.latestUserID,
        UserisInCart: state => state.warenkorb = true,
        UserisInCart_2: state => state.nichtwarenkorb = false,
        UserisNotInCart: state => state.nichtwarenkorb = true,
        UserisNotInCart_2: state => state.warenkorb = false,
    }
});
