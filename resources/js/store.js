import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        token: localStorage.getItem('token') || null,
        company: localStorage.getItem('company') || null,
        establishment: localStorage.getItem('establishment') || null,
        user: localStorage.getItem('user') || null,
        windowWidth: window.innerWidth,
        quote: [],
        quotations: [],
        printings: [],
        workflow: {
            summary: "",
            identification: {
                third: {
                    type: "old",
                    ethic: false,
                    id: "",
                    name: "",
                    addressLine1: "",
                    addressLine2: "",
                    addressLine3: "",
                    zipcode: "",
                    city: "",
                    hasFocus: false,
                },
                contact: {
                    id: "",
                    civility: "",
                    name: "",
                    surname: "",
                    service: "",
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
        third: {
            contacts: [],
            labels: [],
        },
        price: [],
        quotation: [],
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
        setCompany(state, company) {
            state.company = company;
        },
        setEstablishment(state, establishment) {
            state.establishment = establishment;
        },
        setUser(state, user) {
            state.user = user;
        },
        logout(state) {
            state.token = null;
        },
        setWindowWidth(state, data) {
            state.windowWidth = data;
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
        },
        setThirdContacts(state, data) {
            state.third.contacts = data;
        },
        setThirdLabels(state, data) {
            state.third.labels = data;
        }
    },
    actions: {
        login(context, credentials) {
            axios.defaults.headers.common['Content-Type'] = 'multipart/form-data';
            return new Promise((resolve, reject) => {
                axios.post('/api/auth/login', {
                // axios.post('http://89.92.37.229/API/AUTHENTIFICATION', {
                    email: credentials.username,
                    password: credentials.password
                }).then(response => {
                    localStorage.setItem("token", response.data.token);
                    context.commit("login", response.data.token);

                    localStorage.setItem("company", "001");
                    context.commit("setCompany", "001");

                    localStorage.setItem("establishment", "001");
                    context.commit("setEstablishment", "001");

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
            console.log(context.getters.loggedIn);
            if (context.getters.loggedIn) {
                return new Promise((resolve, reject) => {
                    axios.post('/api/auth/logout', { // quotations.price
                        token: context.state.token,
                    }).then(response => {
                        localStorage.removeItem("token");
                        localStorage.removeItem("company");
                        localStorage.removeItem("establishment");
                        localStorage.removeItem("user");
                        context.commit("logout");
                        resolve(response);
                    }).catch(error => {
                        localStorage.removeItem("token");
                        localStorage.removeItem("company");
                        localStorage.removeItem("establishment");
                        localStorage.removeItem("user");
                        context.commit("logout");
                        reject(error);
                    });
                });
            }
        },
        getWindowWidth(context, credentials) {
            context.commit("setWindowWidth", credentials.windowSize);
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
        getCustomers(context, credentials) {
            return new Promise((resolve, reject) => {
                axios.post('api/auth/quotations/customers', {
                    company: context.state.company,
                    filters: credentials.filters,
                    page: credentials.page
                }).then(response => {
                    resolve(response.data);
                }).catch(error => {
                    console.log("store error getCustomers");
                    console.log(error);
                    reject(error);
                });
            });
        },
        searchCustomersForAutocomplete(context, credentials) {
            return new Promise((resolve, reject) => {
                axios.post('/api/auth/quotations/search/autocomplete/customers', {
                    company: context.state.company,
                    queryString: credentials.queryString
                }).then(response => {
                    resolve(response);
                }).catch(error => {
                    console.log("store error searchCustomersForAutocompletion");
                    console.log(error);
                    reject(error);
                });
            });
        },
        async getThirdContacts(context, credentials) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' +  context.state.token;
            let data = (await axios.post('/api/auth/quotations/search/contacts', {
                company: context.state.company,
                ethic: credentials.ethic,
                third: credentials.third,
            })).data;
            console.log(data);
            context.commit("setThirdContacts", data);
        },
        async getThirdLabels(context, credentials) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' +  context.state.token;
            let data = (await axios.post('/api/auth/quotations/third/labels', {
                company: context.state.company,
                ethic: credentials.ethic,
                third: credentials.third,
            })).data;
            console.log(data);
            context.commit("setThirdLabels", data);
        },
        getPrintings(context) {
            return new Promise((resolve, reject) => {
                axios.post('/api/auth/quotations/printings', {
                    company: context.state.company
                }).then(response => {
                    console.log(response);
                    resolve(response);
                }).catch(error => {
                    console.log(error);
                    reject(error);
                });
            });
        },
        async getSubstrates(context) {
            let data = (await axios.get('http://89.92.37.229/API/SUPPORT/001/001')).data;
            console.log("getSubstrates");
            console.log(data);
            context.commit("setSubstrates", data);
        },
        getFinishings(context) {
            return new Promise((resolve, reject) => {
                axios.post('/api/auth/quotations/finishings', {
                    company: context.state.company
                }).then(response => {
                    resolve(response);
                }).catch(error => {
                    reject(error);
                });
            });
        },
        // async getCuttings(context) {
        //     let data = (await axios.get('/api/auth/quotations/cuttings')).data;
        //     context.commit("setCuttings", data);
        // },
        updateQuotationSummary(context, credentials) {
            const summary = credentials.summary;
            context.commit("setQuotationSummary", summary);
        },
        async getQuotationPrice(context) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' +  context.state.token;
            let data = (await axios.post('/api/auth/quotations/price', { // quotations.price
                workflow: context.state.workflow,
            })).data;
            context.commit("setQuotationPrice", data);
        },
        saveQuotation(context, credentials) {
            return new Promise((resolve, reject) => {
                axios.defaults.headers.common['Authorization'] = 'Bearer ' +  context.state.token;
                axios.post('/api/auth/quotations', { // quotations.store
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
                axios.post('/api/auth/quotations/' + credentials.quotation.id, { // quotations.update
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
            let data = (await axios.get('/api/auth/quotations/' + credentials.id + '/edit')).data; // quotations.edit
            console.log("getQuotation");
            console.log(data);
            context.commit("setQuotation", data);
        },
        async destroyQuotation(context, credentials) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' +  context.state.token;
            let data = (await axios.delete('/api/auth/quotations/' + credentials.id)).data; // quotations.destroy
            context.commit("setQuotations", data);
        },
        async getWorkflow(context, credentials) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' +  context.state.token;
            let data = (await axios.get('/api/auth/quotations/' + credentials.id + '/edit')).data; // quotations.edit
            let workflow = JSON.parse(data.workflow);
            let third = data.third;
            context.commit("setWorkflow", workflow);
            context.commit("setThirdContacts", third);
        },
        async generatePDF(context, credentials) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' +  context.state.token;
            let data = await axios.get('/api/auth/quotations/' + credentials.id + '/pdf'); // quotations.generatePDF
            console.log(data)
        },
        async sendEmail(context, credentials) {
            axios.defaults.headers.common['Authorization'] = 'Bearer ' +  context.state.token;
            let data = await axios.post('/api/auth/quotations/' + credentials.quotation.id + '/email', { // quotations.sendEmail
                quotation: credentials.quotation
            });
            console.log("sendEmail data");
            console.log(data.data);
        },
    },
})
