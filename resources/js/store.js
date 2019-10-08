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
        // substrates: [],
        finishings: [],
        // cuttings: [],
        workflow: {
            summary: "",
            identification: {
                third: {
                    type: "new",
                    ethic: false,
                    id: "",
                    name: "",
                    address: "",
                    zipcode: "",
                    city: "",
                    contacts: [],
                    hasFocus: false,
                },
                contact: {
                    id: "",
                    civility: "",
                    name: "",
                    surname: "",
                    email: "",
                    hasFocus: false,
                }
            },
            description: {
                label: {
                    type: "new",
                    ethic: false,
                    id: "",
                    name: "",
                    width: "",
                    length: "",
                    hasFocus: false,
                },
                quantities: [
                    {
                        id: "",
                        quantity: "",
                        model: "",
                        plate: "",
                        hour: "",
                        minute: "",
                        hasFocus: false,
                    }
                ]
            },
            printing: {
                press: "",
                name: "",
                colors: "",
                quadri: false,
                hasFocus: false,
                substrate: {
                    type: "new",
                    ethic: false,
                    id: "",
                    name: "",
                    width: "",
                    weight: "",
                    price: "",
                    hasFocus: false,
                },
            },
            finishing: {
                finishings: [
                    {
                        type: "",
                        id: "",
                        name: "",
                        shape: false,
                        reworking: "",
                        presence_consumable: false,
                        hasFocus: false,
                        consumable: ""
                    }
                ],
                cutting: {
                    type: "new",
                    ethic: false,
                    id: "",
                    name: "",
                    dimension_width: "",
                    dimension_length: "",
                    bleed_width: "",
                    bleed_length: "",
                    pose_width: "",
                    pose_length: "",
                    shape: "",
                    hasFocus: false,
                }
            },
            packing: {
                packing: "",
                direction: "",
            },
        },
        price: [],
        quotation: [],
    },
    getters: {
        loggedIn(state) {
            return state.token !== null;
        },
        workflow(state) {
            return state.workflow;
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
        // setSubstrates(state, data) {
        //     state.substrates = data;
        // },
        setFinishings(state, data) {
            state.finishings = data;
        },
        // setCuttings(state, data) {
        //     state.cuttings = data;
        // },
        setQuotationSummary(state, data) {
            state.workflow.summary = data;
        },
        setQuotationPrice(state, data) {
            state.price = data;
        },
        setQuotation(state, data) {
            state.quotation = data;
        },
        setWorkflow(state, data) {
            state.workflow = data;
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
        async getQuote(context) {
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
        // async getSubstrates(context) {
        //     let data = (await axios.get('/api/auth/quotation/substrates')).data;
        //     context.commit("setSubstrates", data);
        // },
        async getFinishings(context) {
            let data = (await axios.get('/api/auth/quotation/finishings')).data;
            context.commit("setFinishings", data);
        },
        // async getCuttings(context) {
        //     let data = (await axios.get('/api/auth/quotation/cuttings')).data;
        //     context.commit("setCuttings", data);
        // },
        updateQuotationSummary(context, credentials) {
            const summary = credentials.summary;
            context.commit("setQuotationSummary", summary);
        },
        async getQuotationPrice(context) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' +  context.state.token;
            let data = (await axios.post('/api/auth/quotation/price', {
                workflow: context.getters.workflow,
            })).data;
            context.commit("setQuotationPrice", data);
        },
        saveQuotation(context, credentials) {
            return new Promise((resolve, reject) => {
                axios.defaults.headers.common['Authorization'] = 'Bearer ' +  context.state.token;
                axios.post('/api/auth/quotation', {
                    price: context.state.price,
                    workflow: context.state.workflow,
                    quotation: credentials.quotation,
                }).then(response => {
                    console.log("SaveQuotation :");
                    console.log(response.data);
                    context.commit("setQuotation", response.data);
                    resolve(response);
                }).catch(error => {
                    console.log(error);
                    reject(error);
                });
            });
        },
        updateQuotation(context, credentials) {
            return new Promise((resolve, reject) => {
                axios.defaults.headers.common['Authorization'] = 'Bearer ' +  context.state.token;
                axios.post('/api/auth/quotation/' + credentials.quotation.id + '/edit', {
                    quotation: credentials.quotation
                }).then(response => {
                    console.log(response);
                    context.commit("setQuotation", response.data);
                    resolve(response);
                }).catch(error => {
                    console.log(error);
                    reject(error);
                });
            });
        },
        async getQuotation(context, credentials) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' +  context.state.token;
            let data = (await axios.get('/api/auth/quotation/' + credentials.id + '/edit')).data;
            context.commit("setQuotation", data);
        },
        async destroyQuotation(context, credentials) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' +  context.state.token;
            let data = (await axios.delete('/api/auth/quotation/' + credentials.id)).data;
            context.commit("setQuotations", data);
        },
        async getWorkflow(context, credentials) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' +  context.state.token;
            let data = (await axios.get('/api/auth/quotation/' + credentials.id + '/edit')).data;
            let workflow = JSON.parse(data.workflow);
            let contacts = data.contacts;
            workflow.identification.third.contacts = contacts;
            context.commit("setWorkflow", workflow);
        },
    },
})
