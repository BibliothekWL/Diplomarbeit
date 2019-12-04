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
        latestSearch: ""
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
        UsernotLoggedIn: state => state.isLoggedIn = false
    }
});
