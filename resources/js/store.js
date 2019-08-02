import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex);

const state = {
    isLogged: !!localStorage.getItem('token')
}

const mutations = {
    LOGIN_USER (state) {
        state.isLogged = true
    },

    LOGOUT_USER (state) {
        state.isLogged = false
    }
}

export default new Vuex.Store({
    state: {
        token: !!localStorage.getItem('token')
    },
    getters: {
        isLogged(state) {
            return state.token !== null;
        }
    },
    mutations: {
        login(state, token) {
            state.token = token;
        }
    },
    actions: {
        login(context, credentials) {
            return new Promise((resolve, reject) => {
                axios.post('/api/auth/login', {
                    email: credentials.email,
                    password: credentials.password
                }).then(response => {
                    const token = response.data.token;
                    localStorage.setItem("token", token);
                    context.commit("login", token);
                    resolve(response);
                }).catch(error => {
                    reject(error);
                });
            });
        }
    },
})