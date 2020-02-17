import Vue from 'vue'
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
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
        latestCartCount: 0,
        showalpha: true,
        latestFilterMedium: null,
        filter_medium: null,
        latestFilterSystematik: null,
        filter_systematik: null
    },
    plugins: [createPersistedState()],
    mutations: {
        increment: state => state.page++,
        decrement: state => state.page--,
        isFirstPage: state => state.page = 1,
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
        setCartCount: state => state.cart_count = state.latestCartCount,
        setFilterMedium: state => state.filter_medium = state.latestFilterMedium,
        setFilterSystematik: state => state.filter_systematik = state.latestFilterSystematik
    }
});
