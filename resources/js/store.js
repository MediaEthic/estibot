import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        token: localStorage.getItem('token') || null,
        user: localStorage.getItem('user') || null,
        quote: [],
        quotations: [],
        printings: [],
        substrates: [],
        finishings: [],
        cuttings: [],
    },
    getters: {
        loggedIn(state) {
            return state.token !== null;
        },
    },
    mutations: {
        login(state, token) {
            state.token = token;
        },
        setUser(state, user) {
            state.user = user;
        },
        logout(state) {
            state.token = null;
        },
        setQuote(state, data) {
            state.quote = data;
        },
        setQuotations(state, data) {
            state.quotations = data;
        },
        setPrintings(state, data) {
            state.printings = data;
        },
        setSubstrates(state, data) {
            state.substrates = data;
        },
        setFinishings(state, data) {
            state.finishings = data;
        },
        setCuttings(state, data) {
            state.cuttings = data;
        }
    },
    actions: {
        login(context, credentials) {
            return new Promise((resolve, reject) => {
                axios.post('/api/auth/login', {
                    email: credentials.email,
                    password: credentials.password
                }).then(response => {
                    localStorage.setItem("token", response.data.token);
                    context.commit("login", response.data.token);
                    localStorage.setItem("user", response.data.user.name);
                    context.commit("setUser", response.data.user.name);
                    resolve(response);
                }).catch(error => {
                    localStorage.removeItem("token");
                    localStorage.removeItem("user");
                    reject(error);
                });
            });
        },
        logout(context) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' +  context.state.token;
            if (context.getters.loggedIn) {
                return new Promise((resolve, reject) => {
                    axios.post('/api/auth/logout').then(response => {
                        localStorage.removeItem("token");
                        context.commit("logout");
                        resolve(response);
                    }).catch(error => {
                        localStorage.removeItem("token");
                        localStorage.removeItem("user");
                        context.commit("logout");
                        reject(error);
                    });
                });
            }
        },
        async getQuote(context, credentials) {
            let data = (await axios.get('/api/quote')).data;
            context.commit("setQuote", data);
        },
        async getQuotations(context, credentials) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' +  context.state.token;
            const url = credentials.url;
            let data = (await axios.get(url)).data;
            context.commit("setQuotations", data);
        },
        async getPrintings(context) {
            let data = (await axios.get('/api/auth/quotation/printings')).data;
            context.commit("setPrintings", data);
        },
        async getSubstrates(context) {
            let data = (await axios.get('/api/auth/quotation/substrates')).data;
            context.commit("setSubstrates", data);
        },
        async getFinishings(context) {
            let data = (await axios.get('/api/auth/quotation/finishings')).data;
            context.commit("setFinishings", data);
        },
        async getCuttings(context) {
            let data = (await axios.get('/api/auth/quotation/cuttings')).data;
            context.commit("setCuttings", data);
        }
    },
})