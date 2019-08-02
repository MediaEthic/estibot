import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        token: localStorage.getItem('token') || null,
    },
    getters: {
        loggedIn(state) {
            return state.token !== null;
        }
    },
    mutations: {
        login(state, token) {
            state.token = token;
        },
        logout(state, token) {
            state.token = null;
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
                    localStorage.removeItem("token");
                    reject(error);
                });
            });
        },
        logout(context) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' +  context.state.token;
            if (context.getters.loggedIn) {
                return new Promise((resolve, reject) => {
                    axios.post('/api/auth/logout', {
                        token: context.state.token
                    }).then(response => {
                        localStorage.removeItem("token");
                        context.commit("logout");
                        resolve(response);
                    }).catch(error => {
                        localStorage.removeItem("token");
                        context.commit("logout");
                        reject(error);
                    });
                });
            }
        }
    },
})