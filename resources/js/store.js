import Vue from 'vue'
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        page: 1,
        lastPage: 0,
        isAdmin: false,
        isLoggedIn: false,
        search: "",
        latestSearch: "",
        latestUsername: "",
        username: "",
        latestUserID: 0,
        userID: 0,
        warenkorb: false,
        nichtwarenkorb: false,
        cart_count: 0,
        latestCartCount: 0
    },
    plugins: [createPersistedState()],
    mutations: {
        increment: state => state.page++,
        decrement: state => state.page--,
        isFirstPage: state => state.page = 1,
        isLastPage: state => state.page = state.lastPage,
        setSearch: state => state.search = state.latestSearch,
        setSearchEmpty: state => state.search = "",
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
        setCartCount: state => state.cart_count = state.latestCartCount
    }
});
