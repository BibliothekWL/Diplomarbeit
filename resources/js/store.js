import Vue from 'vue'
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        count: 1,
        lastPage: 0,
        isAdmin: false,
        isLoggedIn: false
    },
    plugins: [createPersistedState()],
    mutations: {
        increment: state => state.count++,
        decrement: state => state.count--,
        isFirstPage: state => state.count = 1,
        isLastPage: state => state.count = 3,
        UserisAdmin: state => state.isAdmin = true,
        UserisnotAdmin: state => state.isAdmin = false,
    }
});
