import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        token: localStorage.getItem('token') ? localStorage.getItem('token') : ''
    },
    mutations: {
        changeLogin (state, user) {
            state.token = user.token;
            localStorage.setItem('token', user.token);
        }
    }
});

export default store
