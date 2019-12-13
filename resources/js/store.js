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
        workflow: {
            form: {
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
                        type: "new",
                        ethic: false,
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
                        type: "old",
                        ethic: false,
                        id: "",
                        variant: "",
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
                        type: "old",
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
                            die: {
                                id: "",
                                name: "",
                                price: "",
                            },
                            reworking: "",
                            presence_consumable: false,
                            hasFocus: false,
                            consumable: ""
                        }
                    ],
                    cutting: {
                        type: "old",
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
            database: {
                identification: {
                    contacts: [],
                },
                description: {
                    labels: [],
                },
                printing: {
                    printings: [],
                    substrates: {
                        search: {
                            criteria: {
                                isLoading: false,
                                families: [],
                                types: [],
                                colors: [],
                                weights: [],
                                // suppliers: [],
                            },
                        },
                    },
                },
                finishing: {
                    finishings: [],
                    reworkings: [],
                    cuttings: []
                }
            },
        },
        price: [],
        quotation: [],
    },
    getters: {
        loggedIn(state) {
            // return state.token !== null;
            return state.user !== null;
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
            state.workflow.database.printing.printings = data;
        },
        setSubstratesSearchCriteria(state, data) {
            state.workflow.database.printing.substrates.search.criteria = data;
        },
        // setCuttings(state, data) {
        //     state.cuttings = data;
        // },
        setQuotationSummary(state, data) {
            state.workflow.form.summary = data;
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
            state.workflow.database.identification.contacts = data;
        },
        setThirdLabels(state, data) {
            state.workflow.database.description.labels = data;
        },
        setFinishingsLabel(state, data) {
            state.workflow.form.finishing.finishings = data;
        },
        setFinishings(state, data) {
            state.workflow.database.finishing.finishings = data;
        },
        setReworkings(state, data) {
            state.workflow.database.finishing.reworkings = data;
        }
    },
    actions: {
        login(context, credentials) {
            return new Promise((resolve, reject) => {
                axios.post('/api/auth/login', {
                    username: credentials.username,
                    password: credentials.password
                }).then(response => {
                    // localStorage.setItem("token", response.data.token);
                    // context.commit("login", response.data.token);
                    //
                    localStorage.setItem("company", response.data[0].CODESOCIETE);
                    context.commit("setCompany", response.data[0].CODESOCIETE);

                    localStorage.setItem("establishment", response.data[0].CODEETABLISSEMENT);
                    context.commit("setEstablishment", response.data[0].CODEETABLISSEMENT);

                    localStorage.setItem("user", response.data[0].NOM);
                    context.commit("setUser", response.data[0].NOM);

                    resolve(response);
                }).catch(error => {
                    console.log(error);
                    if (error.response) {
                        console.log(error.response.headers);
                    } else if (error.request) {
                        console.log(error.request);
                    } else {
                        console.log(error.message);
                    }
                    // localStorage.removeItem("token");
                    localStorage.removeItem("user");
                    localStorage.removeItem("company");
                    localStorage.removeItem("establishment");
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
                axios.post('/api/auth/quotations/autocomplete/customers', {
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
        async getPrintings(context) {
            let data = (await axios.post('/api/auth/quotations/printings', {
                company: context.state.company
            })).data;
            context.commit("setPrintings", data);
        },
        async getSubstratesSearchCriteria(context) {
            console.log(context.state.workflow.form.description.label.ethic);
            console.log(context.state.workflow.form.description.label.id);
            console.log(context.state.workflow.form.description.label.variant);
            let data = (await axios.post('/api/auth/quotations/substrates/search/criteria', {
                company: context.state.company,
                establishment: context.state.establishment,
                ethic: context.state.workflow.form.description.label.ethic,
                label: context.state.workflow.form.description.label.id,
                variant: context.state.workflow.form.description.label.variant
            })).data;
            console.log(data);
            context.commit("setSubstratesSearchCriteria", data);
        },
        searchSubstratesForAutocomplete(context, credentials) {
            return new Promise((resolve, reject) => {
                axios.post('/api/auth/quotations/substrates/search/autocomplete', {
                    company: context.state.company,
                    establishment: context.state.establishment,
                    queryString: credentials.queryString,
                    ethic: context.state.workflow.form.description.label.ethic,
                    label: context.state.workflow.form.description.label.id,
                    variant: context.state.workflow.form.description.label.variant
                }).then(response => {
                    resolve(response.data);
                }).catch(error => {
                    reject(error);
                });
            });
        },
        getFilteredSubstrates(context, credentials) {
            return new Promise((resolve, reject) => {
                axios.post('/api/auth/quotations/substrates', {
                    company: context.state.company,
                    establishment: context.state.establishment,
                    filters: credentials.filters,
                    page: credentials.page,
                    ethic: context.state.workflow.form.description.label.ethic,
                    label: context.state.workflow.form.description.label.id,
                    variant: context.state.workflow.form.description.label.variant
                }).then(response => {
                    console.log("getFilteredSubstrates");
                    console.log(response.data);
                    resolve(response.data);
                }).catch(error => {
                    console.log(error);
                    reject(error);
                });
            });
        },
        async getFinishings(context) {
            let data = (await axios.post('/api/auth/quotations/finishings', {
                company: context.state.company,
                establishment: context.state.establishment,
                ethic: context.state.workflow.form.description.label.ethic,
                label: context.state.workflow.form.description.label.id,
                variant: context.state.workflow.form.description.label.variant
            })).data;
            // let data = response.data;
            console.log(data);
            if (data.form !== undefined) {
                context.commit("setFinishingsLabel", data.form);
                context.commit("setFinishings", data.form);
            } else {
                console.log("data.form undefined");
                let singleFinishing = [{
                    id: "",
                    name: "",
                    die: {
                        id: "",
                        name: "",
                        price: "",
                    },
                    reworking: "",
                    presence_consumable: false,
                    hasFocus: false,
                    consumable: ""
                }];
                context.commit("setFinishingsLabel", singleFinishing);
                console.log(context.state.workflow.form.finishing.finishings);
                if (data.database !== undefined) {
                    context.commit("setFinishings", data.database);
                }
            }
        },
        async getReworkings(context) {
            let data = (await axios.post('/api/auth/quotations/finishings/reworkings', {
                company: context.state.company,
            })).data;
            console.log(data);
            context.commit("setReworkings", data);
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
                workflow: context.state.workflow.form,
            })).data;
            context.commit("setQuotationPrice", data);
        },
        saveQuotation(context, credentials) {
            return new Promise((resolve, reject) => {
                axios.defaults.headers.common['Authorization'] = 'Bearer ' +  context.state.token;
                axios.post('/api/auth/quotations', { // quotations.store
                    company: context.state.company,
                    price: context.state.price,
                    workflow: context.state.workflow.form,
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
