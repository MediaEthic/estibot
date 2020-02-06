<template>
    <div>
        <Loader v-if="isLoading" />
        <div class="wrap-radio">
            <ValidationProvider tag="div"
                                class="wrap-field"
                                rules="required|oneOf:old,new"
                                name="third type"
                                v-slot="{ errors }">
                <input type="radio"
                       id="third_old"
                       :class="{ 'input-error': errors[0] }"
                       v-model="form.identification.third.type"
                       value="old"
                       @click="resetIdentification">
                <label for="third_old">
                    <i class="fas fa-user-secret"></i>
                    <span>Rechercher un client</span>
                </label>
                <span class="v-validate">{{ errors[0] }}</span>
            </ValidationProvider>
            <div class="wrap-field">
                <input type="radio"
                       id="third_new"
                       v-model="form.identification.third.type"
                       value="new"
                       @click="resetIdentification">
                <label for="third_new">
                    <i class="fas fa-user-plus"></i>
                    <span>Créer un prospect</span>
                </label>
            </div>
        </div>

        <ValidationProvider tag="div"
                            class="wrap-field h-50"
                            rules="required"
                            name="name"
                            v-slot="{ errors }">
            <span v-if="form.identification.third.type === 'old'" class="btn-right-field" @click="show">
                <i class="fas fa-search"></i>
            </span>
            <input v-model.trim="form.identification.third.name"
                   class="field"
                   :class="{ hasValue: form.identification.third.name, 'input-error': errors[0] }"
                   type="text"
                   required>
            <span class="focus-field"></span>
            <label v-if="form.identification.third.type === 'old'" class="label-field">Nom du client</label>
            <label v-else class="label-field">Nom du prospect</label>
            <span class="symbol-left-field"><i class="fas fa-user-tie"></i></span>
            <span class="v-validate">{{ errors[0] }}</span>
        </ValidationProvider>

        <div class="wrap-group-field"
             :class="[{ hasValue: form.identification.third.addressLine1 },
                      { hasValue: form.identification.third.addressLine2 },
                      { hasValue: form.identification.third.addressLine3 },
                      { hasValue: form.identification.third.zipcode },
                      { hasValue: form.identification.third.city },
                      { hasFocus: form.identification.third.hasFocus }]">
            <ValidationProvider tag="div"
                                class="wrap-field h-50"
                                rules="required"
                                name="address line 1"
                                v-slot="{ errors }">
                <input v-model.trim="form.identification.third.addressLine2"
                       @focus="form.identification.third.hasFocus = true"
                       @blur="form.identification.third.hasFocus = false"
                       class="field"
                       :class="[{ hasValue: form.identification.third.addressLine2, 'input-error': errors[0] }]"
                       type="text"
                       autocomplete="off"
                       required>
                <label class="label-field">Numéro + Libellé de la voie</label>
                <span class="v-validate">{{ errors[0] }}</span>
            </ValidationProvider>
            <ValidationProvider tag="div"
                                class="wrap-field h-50"
                                name="address line 2"
                                v-slot="{ errors }">
                <input v-model.trim="form.identification.third.addressLine1"
                       @focus="form.identification.third.hasFocus = true"
                       @blur="form.identification.third.hasFocus = false"
                       class="field"
                       :class="[{ hasValue: form.identification.third.addressLine1, 'input-error': errors[0] }]"
                       type="text"
                       autocomplete="off">
                <label class="label-field">Complément de localisation</label>
                <span class="v-validate">{{ errors[0] }}</span>
            </ValidationProvider>
            <ValidationProvider tag="div"
                                class="wrap-field h-50"
                                name="address line 3"
                                v-slot="{ errors }">
                <input v-model.trim="form.identification.third.addressLine3"
                       @focus="form.identification.third.hasFocus = true"
                       @blur="form.identification.third.hasFocus = false"
                       class="field"
                       :class="[{ hasValue: form.identification.third.addressLine3, 'input-error': errors[0] }]"
                       type="text"
                       autocomplete="off">
                <label class="label-field">BP - Lieu dit</label>
                <span class="v-validate">{{ errors[0] }}</span>
            </ValidationProvider>
            <div class="wrap-field-inline">
                <ValidationProvider tag="div"
                                    class="wrap-field h-50"
                                    name="zipcode"
                                    rules="required|digits:5"
                                    v-slot="{ errors }">
                    <input v-model.trim="form.identification.third.zipcode"
                           @focus="form.identification.third.hasFocus = true"
                           @blur="form.identification.third.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.identification.third.zipcode, 'input-error': errors[0] }"
                           type="text"
                           autocomplete="off"
                           required>
                    <label class="label-field">Code postal</label>
                    <span class="v-validate">{{ errors[0] }}</span>
                </ValidationProvider>
                <ValidationProvider tag="div"
                                    class="wrap-field h-50"
                                    name="city"
                                    v-slot="{ errors }">
                    <input v-model.trim="form.identification.third.city"
                           @focus="form.identification.third.hasFocus = true"
                           @blur="form.identification.third.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.identification.third.city, 'input-error': errors[0] }"
                           type="text"
                           autocomplete="off">
                    <label class="label-field">Ville</label>
                    <span class="v-validate">{{ errors[0] }}</span>
                </ValidationProvider>
            </div>
            <span class="focus-field"></span>
            <span class="symbol-left-field"><i class="fas fa-map-marker-alt"></i></span>
        </div>

        <div v-if="form.identification.third.type === 'old' && database.identification.contacts.length"
             class="wrap-group-field"
             :class="[{ hasValue: form.identification.contact.id },
                      { hasValue: form.identification.contact.email },
                      { hasFocus: form.identification.contact.hasFocus }]">

            <ValidationProvider tag="div"
                                class="wrap-field h-50"
                                name="contact"
                                rules="required|is_not:''"
                                v-slot="{ errors }">
                <span class="btn-right-field" v-if="contactsAreLoading">
                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                </span>
                <select v-model="form.identification.contact.id"
                        @focus="form.identification.contact.hasFocus = true"
                        @blur="form.identification.contact.hasFocus = false"
                        @animationstart="checkAnimation"
                        class="field select"
                        :class="{ hasValue: form.identification.contact.id, 'input-error': errors[0] }"
                        @change="setContact(contact)"
                        required>
                    <option disabled value="">Choisir</option>
                    <option v-for="contact in database.identification.contacts"
                            v-bind:value="contact.id">
                        {{ generateContact(contact) }}
                    </option>
                </select>
                <label class="label-field">Contact</label>
                <span class="v-validate">{{ errors[0] }}</span>
            </ValidationProvider>

            <ValidationProvider tag="div"
                                class="wrap-field h-50"
                                rules="required"
                                name="e-mail"
                                v-slot="{ errors }">
                <input v-model.trim="form.identification.contact.email"
                       @focus="form.identification.contact.hasFocus = true"
                       @blur="form.identification.contact.hasFocus = false"
                       class="field"
                       :class="[{ hasValue: form.identification.contact.email, 'input-error': errors[0] }]"
                       type="email"
                       autocomplete="off"
                       required>
                <label class="label-field">E-mail</label>
                <span class="v-validate">{{ errors[0] }}</span>
            </ValidationProvider>
            <span class="focus-field"></span>
            <span class="symbol-left-field"><i class="fas fa-id-card-alt"></i></span>
        </div>

        <div v-else
             class="wrap-group-field"
             :class="[{ hasValue: form.identification.contact.civility },
                      { hasValue: form.identification.contact.name },
                      { hasValue: form.identification.contact.surname },
                      { hasValue: form.identification.contact.email },
                      { hasFocus: form.identification.contact.hasFocus }]">
            <div class="wrap-field-inline">
                <ValidationProvider tag="div"
                                    class="wrap-field h-50"
                                    name="civility"
                                    rules="is_not:''"
                                    v-slot="{ errors }"
                                    style="width: 26rem;">
                    <select v-model="form.identification.contact.civility"
                            @focus="form.identification.contact.hasFocus = true"
                            @blur="form.identification.contact.hasFocus = false"
                            @animationstart="checkAnimation"
                            class="field select"
                            :class="{ hasValue: form.identification.contact.civility, 'input-error': errors[0] }">
                        <option disabled value="">Choisir</option>
                        <option value="Mr">M.</option>
                        <option value="Mrs">Mme</option>
                    </select>
                    <label class="label-field">Civilité</label>
                    <span class="v-validate">{{ errors[0] }}</span>
                </ValidationProvider>
                <ValidationProvider tag="div"
                                    class="wrap-field h-50"
                                    name="last name"
                                    v-slot="{ errors }">
                    <input v-model.trim="form.identification.contact.surname"
                           @focus="form.identification.contact.hasFocus = true"
                           @blur="form.identification.contact.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.identification.contact.surname, 'input-error': errors[0] }"
                           type="text"
                           autocomplete="off">
                    <label class="label-field">Nom de famille</label>
                    <span class="v-validate">{{ errors[0] }}</span>
                </ValidationProvider>
            </div>
            <ValidationProvider tag="div"
                                class="wrap-field h-50"
                                name="first name"
                                v-slot="{ errors }">
                <input v-model.trim="form.identification.contact.name"
                       @focus="form.identification.contact.hasFocus = true"
                       @blur="form.identification.contact.hasFocus = false"
                       class="field"
                       :class="{ hasValue: form.identification.contact.name, 'input-error': errors[0] }"
                       type="text"
                       autocomplete="off">
                <label class="label-field">Prénom du contact</label>
                <span class="v-validate">{{ errors[0] }}</span>
            </ValidationProvider>
            <ValidationProvider tag="div"
                                class="wrap-field h-50"
                                rules="required"
                                name="e-mail"
                                v-slot="{ errors }">
                <input v-model.trim="form.identification.contact.email"
                       @focus="form.identification.contact.hasFocus = true"
                       @blur="form.identification.contact.hasFocus = false"
                       class="field"
                       :class="[{ hasValue: form.identification.contact.email, 'input-error': errors[0] }]"
                       type="email"
                       autocomplete="off"
                       required>
                <label class="label-field">E-mail</label>
                <span class="v-validate">{{ errors[0] }}</span>
            </ValidationProvider>
            <span class="focus-field"></span>
            <span class="symbol-left-field"><i class="fas fa-id-card-alt"></i></span>
        </div>

        <transition name="modal" mode="out-in">
            <modal name="search-customer" @opened="opened" :adaptive="true" :width="1180" :height="640">
                <div>
                    <button type="button"
                            class="modal-close"
                            aria-label="Fermer la fenêtre modale"
                            @click="hide">
                    </button>
                    <h3 class="page-main-title modal-header">Rechercher un client</h3>

                    <div class="modal-body">
                        <form action="#" method="post" class="form-filters">
                            <div class="inline">
                                <Autocomplete :items="autocomplete.customers"
                                              :isAsync="true"
                                              :label="`Abrégé/Nom`"
                                              :focus="true"
                                              v-on:search="searchCustomersForAutocomplete"
                                              v-on:input="setCustomerName"
                                />

                                <ValidationProvider tag="div"
                                                    class="wrap-field h-50"
                                                    name="zipcode"
                                                    rules="digits:2"
                                                    v-slot="{ errors }">
                                    <input v-model.trim="filters.zipcode"
                                           class="field"
                                           :class="[{ hasValue: filters.zipcode, 'input-error': errors[0] }]"
                                           type="text"
                                           autocomplete="off">
                                    <span class="focus-field"></span>
                                    <label class="label-field">Département/Code postal</label>
                                    <span class="symbol-left-field"><i class="fas fa-map-marker-alt"></i></span>
                                    <span class="v-validate">{{ errors[0] }}</span>
                                </ValidationProvider>

        <!--                        <ValidationProvider class="wrap-field h-50"-->
        <!--                                            name="contact"-->
        <!--                                            v-slot="{ errors }">-->
        <!--                            <input v-model.trim="filters.contact"-->
        <!--                                   class="field"-->
        <!--                                   :class="[{ hasValue: filters.contact }]"-->
        <!--                                   type="text"-->
        <!--                                   autocomplete="off">-->
        <!--                            <span class="focus-field"></span>-->
        <!--                            <label class="label-field">Contact</label>-->
        <!--                            <span class="symbol-left-field"><i class="fas fa-id-card-alt"></i></span>-->
        <!--                            <span class="v-validate">{{ errors[0] }}</span>-->
        <!--                        </ValidationProvider>-->
                            </div>

                            <button type="submit"
                                    class="button button-small button-primary"
                                    style="margin-left: auto;"
                                    @click.prevent="getCustomers(1)"
                                    @keydown.tab.exact.prevent="">
                                Rechercher
                                <i class="fas fa-search"></i>
                            </button>
                        </form>

                        <div class="container-table" v-if="customers.length || Object.keys(customers).length">
                            <table class="wrap-table">
                                <thead class="table-header">
                                    <tr class="table-row">
                                        <th scope="col" class="table-cell">Base Ethic</th>
                                        <th scope="col" class="table-cell">Type de compte</th>
                                        <th scope="col" class="table-cell">N° de compte</th>
                                        <th scope="col" class="table-cell">Raison sociale</th>
                                        <th scope="col" class="table-cell">Code postal</th>
                                        <th scope="col" class="table-cell">Ville</th>
                                    </tr>
                                </thead>
                                <tbody class="table-body">
                                    <tr class="table-row" v-for="(customer, index) in customers" :key="index" @click="selectedCustomer(customer)">
                                        <td class="table-cell" data-title="Base Ethic">{{ customer.ethic ? "Oui" : "Non" }}</td>
                                        <td class="table-cell" data-title="Type de compte">{{ customer.type }}</td>
                                        <td class="table-cell" data-title="N° de compte">{{ customer.id }}</td>
                                        <td class="table-cell" data-title="Raison sociale">{{ customer.alias }} // {{ customer.name }}</td>
                                        <td class="table-cell" data-title="Code postal">{{ customer.zipcode }}</td>
                                        <td class="table-cell" data-title="Ville">{{ customer.city }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <Pagination :pagination="pagination" v-on:fetchResults="getCustomers"></Pagination>
                        </div>
                        <div v-else class="wrap-empty-result">
                            <p class="text-no-data">Aucun résultat trouvé</p>
                            <img class="image-no-data"
                                 src="/assets/img/undraw_no_data_qbuo.svg"
                                 alt="Illustration montrant qu'aucun résultat n'a été trouvé"/>
                        </div>
                    </div>
                </div>
            </modal>
        </transition>
    </div>
</template>

<script>
    import Loader from '../Loader';
    import VueGoogleAutocomplete from 'vue-google-autocomplete'
    import Autocomplete from "./Autocomplete";
    import Pagination from "../Pagination";

    export default {
        components: {
            Pagination,
            Autocomplete,
            Loader,
            VueGoogleAutocomplete
        },
        data() {
            return {
                isLoading: false,
                filters: {
                    ethic: "",
                    name: "",
                    zipcode: "",
                    contact: "",
                },
                autocomplete: {
                    customers: [],
                },
                customers: [],
                pagination: {},
                contactsAreLoading: false,
            }
        },
        created() {

        },
        computed: {
            form() {
                return this.$store.state.workflow.form;
            },
            database() {
                return this.$store.state.workflow.database;
            }
        },
        methods: {
            show() {
                this.$modal.show('search-customer');
            },
            opened() {

            },
            hide() {
                this.$modal.hide('search-customer');
            },
            checkAnimation({ target, animationName }) {
                if(animationName.startsWith("onAutoFillStart")) {
                    target.classList.add("hasValue");
                }
            },
            generateContact(contact) {
                if (contact.name !== null || contact.surname !== null) {
                    let $contact = ``;
                    if (contact.civility === "Mr" || contact.civility === "1") {
                        $contact += `M. `;
                    } else if (contact.civility === "Mrs" || contact.civility === "2") {
                        $contact += `Mme `;
                    } else if (contact.civility === "3") {
                        $contact += `Mlle `;
                    }

                    if (contact.name !== null) {
                        $contact += contact.name + ` `;
                    }
                    if (contact.surname !== null) {
                        $contact += contact.surname;
                    }
                    return $contact;
                } else {
                    this.form.identification.contact.id = "";
                }
            },
            setContact(contact) {
                this.form.identification.contact.ethic = contact.ethic;
                this.form.identification.contact.type = "old";
                if (this.form.identification.contact.email !== null || this.form.identification.contact.email !== "") {
                    this.form.identification.contact.email = contact.email;
                }
            },
            searchCustomersForAutocomplete(query) {
                this.filters.name = query.toUpperCase();
                if (query.length > 2) {
                    this.$store.dispatch("searchCustomersForAutocomplete", {
                        queryString: query,
                    }).then(response => {
                        this.autocomplete.customers = response.data;
                    }).catch(error => {
                        this.autocomplete.customers = [];
                        this.$toast.error({
                            title: "Erreur",
                            message: "Oups, un problème est survenu pour charger les donneurs d'ordre"
                        });
                    });
                }
            },
            setCustomerName(value) {
                this.filters.name = value.toUpperCase();
            },
            getCustomers(page) {
                this.customers = [];
                this.isLoading = true;

                page = page || 1;
                this.$store.dispatch("getCustomers", {
                    filters: this.filters,
                    page: parseInt(page)
                }).then(response => {
                    this.customers = response.data;
                    this.makePagination(response);
                }).catch(error => {
                    console.log(error.response);
                    this.customers = [];
                    this.$toast.error({
                        title: "Erreur",
                        message: "Oups, un problème est survenu pour charger les donneurs d'ordre"
                    });
                    this.isLoading = false;
                });
            },
            makePagination(meta) {
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page: meta.next_page_url,
                    previous_page: meta.prev_page_url,
                    total: meta.total,
                };

                this.pagination = pagination;
                this.isLoading = false;
            },
            selectedCustomer(customer) {
                this.isLoading = true;
                this.contactsAreLoading = true;
                this.form.identification.contact.id = "";
                this.database.identification.contacts = [];
                this.form.identification.contact.type = "new";
                this.form.identification.contact.ethic = false;

                this.$store.dispatch("getThirdContacts", {
                    ethic: customer.ethic,
                    third: customer.id,
                }).then(() => {
                    this.database.identification.contacts.forEach(element => {
                        if (element.default) {
                            this.form.identification.contact.id = element.id;
                            this.form.identification.contact.type = "old";
                            this.form.identification.contact.ethic = element.ethic;
                        }
                    });
                    this.contactsAreLoading = false;
                }).catch(() => {
                    this.contactsAreLoading = false;
                });

                this.form.identification.third.ethic = customer.ethic;
                this.form.identification.third.id = customer.id;
                this.form.identification.third.name = customer.name;
                this.form.identification.third.addressLine1 = customer.addressLine1;
                this.form.identification.third.addressLine2 = customer.addressLine2;
                this.form.identification.third.addressLine3 = customer.addressLine3;
                this.form.identification.third.zipcode = customer.zipcode;
                this.form.identification.third.city = customer.city;
                if (customer.email !== undefined && (customer.email !== null || customer.email !== "")) {
                    this.form.identification.contact.email = customer.email;
                }
                this.hide();


                this.$store.dispatch("getThirdLabels", {
                    ethic: customer.ethic,
                    third: customer.id,
                }).then(() => { this.isLoading = false; }).catch(() => { this.isLoading = false; });
            },
            resetIdentification() {
                this.customers = [];
                this.form.identification.third.ethic = false;
                this.form.identification.third.id = "";
                this.form.identification.third.name = "";
                this.form.identification.third.addressLine1 = "";
                this.form.identification.third.addressLine2 = "";
                this.form.identification.third.addressLine3 = "";
                this.form.identification.third.zipcode = "";
                this.form.identification.third.city = "";

                this.database.identification.contacts = [];
                this.form.identification.contact.type = "new";
                this.form.identification.contact.ethic = false;
                this.form.identification.contact.id = "";
                this.form.identification.contact.civility = "";
                this.form.identification.contact.name = "";
                this.form.identification.contact.surname = "";
                this.form.identification.contact.email = "";

                this.resetLabel();
            },
            resetLabel() {
                this.database.description.labels = [];
                this.form.description.label.ethic = false;
                this.form.description.label.id = "";
                this.form.description.label.variant = "";
                this.form.description.label.name = "";
                this.form.description.label.width = "";
                this.form.description.label.length = "";

                console.log("resetLabel in Identification Component");
                console.log("getFinishings");
                this.$store.dispatch('getFinishings');

                this.resetSubstrate();
            },
            resetSubstrate() {
                this.form.printing.substrate.ethic = false;
                this.form.printing.substrate.id = "";
                this.form.printing.substrate.name = "";
                this.form.printing.substrate.width = "";
                this.form.printing.substrate.weight = "";
                this.form.printing.substrate.price = "";

                this.database.printing.substrates.search.criteria.isLoading = false;
                this.database.printing.substrates.search.criteria.families = [];
                this.database.printing.substrates.search.criteria.types = [];
                this.database.printing.substrates.search.criteria.colors = [];
                this.database.printing.substrates.search.criteria.weights = [];
            },
        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';

</style>
