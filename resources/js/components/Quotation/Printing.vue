<template>
    <div>
        <div class="wrap-group-field"
             :class="[{ hasValue: form.printing.press },
                      { hasValue: form.printing.colors },
                      { hasValue: form.printing.quadri },
                      { hasFocus: form.printing.hasFocus }]">
            <div class="wrap-field h-50">
                <select v-model="form.printing.press"
                        @focus="form.printing.hasFocus = true"
                        @blur="form.printing.hasFocus = false"
                        @animationstart="checkAnimation"
                        class="field select"
                        :class="{ hasValue: form.printing.press }"
                        @change="handlePressChange($event)"
                        required>
                    <option disabled value="">Choisir</option>
                    <option v-for="printing in database.printing.printings"
                            v-bind:value="printing.id"
                            :data-name="printing.name">
                        {{ printing.name }}
                    </option>
                </select>
                <label class="label-field">Machine d'impression</label>
            </div>
            <ValidationProvider tag="div"
                                class="wrap-field h-50"
                                rules="numeric"
                                name="colors"
                                v-slot="{ errors }">
                <input v-model="form.printing.colors"
                       @focus="form.printing.hasFocus = true"
                       @blur="form.printing.hasFocus = false"
                       class="field"
                       :class="{ hasValue: form.printing.colors, 'input-error': errors[0] }"
                       type="number"
                       autocomplete="off"
                       required>
                <label class="label-field">Nombre de couleurs</label>
                <span class="v-validate">{{ errors[0] }}</span>
            </ValidationProvider>

            <div class="wrap-field h-50 switcher">
                <input type="radio"
                       v-model="form.printing.quadri"
                       id="toggle-on"
                       class="field toggle toggle-left hasValue"
                       value="true"
                       required>
                <label for="toggle-on" class="toggle-btn"><i class="far fa-check-circle"></i>Oui</label>

                <input type="radio" id="toggle-off" class="field toggle toggle-right hasValue" name="toggle" v-model="form.printing.quadri" value="false">
                <label for="toggle-off" class="toggle-btn"><i class="far fa-times-circle"></i>Non</label>

                <label class="label-field">Quadrichromie</label>
            </div>
            <span class="focus-field"></span>
            <span class="symbol-left-field"><i class="fas fa-print"></i></span>
        </div>

        <div class="wrap-radio">
            <div class="wrap-field">
                <input type="radio" id="substrate_old" v-model="form.printing.substrate.type" value="old" @click="resetSubstrate">
                <label for="substrate_old">
                    <i class="fas fa-search"></i>
                    <span>Rechercher un support</span>
                </label>
            </div>
            <div class="wrap-field">
                <input type="radio" id="substrate_new" v-model="form.printing.substrate.type" value="new" @click="resetSubstrate">
                <label for="substrate_new">
                    <i class="fas fa-file-medical"></i>
                    <span>Créer un support</span>
                </label>
            </div>
        </div>

        <div class="wrap-group-field"
             :class="[{ hasValue: form.printing.substrate.name },
                      { hasValue: form.printing.substrate.width },
                      { hasValue: form.printing.substrate.price },
                      { hasFocus: form.printing.substrate.hasFocus }]">
            <ValidationProvider tag="div"
                                class="wrap-field h-50"
                                name="name"
                                v-slot="{ errors }">
                <button type="button"
                        v-if="form.printing.substrate.type === 'old'"
                        class="btn-right-field"
                        @click="show">
                    <i class="fas fa-search"></i>
                </button>
                <input v-model.trim="form.printing.substrate.name"
                       @focus="form.printing.substrate.hasFocus = true"
                       @blur="form.printing.substrate.hasFocus = false"
                       class="field"
                       :class="{ hasValue: form.printing.substrate.name, 'input-error': errors[0] }"
                       type="text"
                       autocomplete="off">
                <label class="label-field">Désignation du support</label>
                <span class="v-validate">{{ errors[0] }}</span>
            </ValidationProvider>
            <ValidationProvider tag="div"
                                class="wrap-field h-50"
                                name="width"
                                rules="required|numeric"
                                v-slot="{ errors }">
                <input v-model="form.printing.substrate.width"
                       @focus="form.printing.substrate.hasFocus = true"
                       @blur="form.printing.substrate.hasFocus = false"
                       class="field"
                       :class="{ hasValue: form.printing.substrate.width, 'input-error': errors[0] }"
                       type="number"
                       autocomplete="off"
                       required>
                <label class="label-field">Laize (mm)</label>
                <span class="v-validate">{{ errors[0] }}</span>
            </ValidationProvider>
            <ValidationProvider tag="div"
                                class="wrap-field h-50"
                                name="weight"
                                rules="required"
                                v-slot="{ errors }">
                <input v-model="form.printing.substrate.weight"
                       @focus="form.printing.substrate.hasFocus = true"
                       @blur="form.printing.substrate.hasFocus = false"
                       class="field"
                       :class="{ hasValue: form.printing.substrate.weight, 'input-error': errors[0] }"
                       type="number"
                       autocomplete="off"
                       required>
                <label class="label-field">Grammage (g/m&#xB2;)</label>
                <span class="v-validate">{{ errors[0] }}</span>
            </ValidationProvider>
            <ValidationProvider tag="div"
                                class="wrap-field h-50"
                                name="price"
                                rules="required"
                                v-slot="{ errors }">
                <input v-model="form.printing.substrate.price"
                       @focus="form.printing.substrate.hasFocus = true"
                       @blur="form.printing.substrate.hasFocus = false"
                       class="field"
                       :class="{ hasValue: form.printing.substrate.price, 'input-error': errors[0] }"
                       type="number"
                       step="0.0001"
                       autocomplete="off"
                       required>
                <label class="label-field">Prix (€/m&#xB2;)</label>
                <span class="v-validate">{{ errors[0] }}</span>
            </ValidationProvider>
            <span class="focus-field"></span>
            <span class="symbol-left-field"><i class="fas fa-scroll"></i></span>
        </div>


        <modal name="search-substrate" @opened="opened" :adaptive="true" :width="1180" :height="640">
            <div>
                <button type="button"
                        class="modal-close"
                        aria-label="Fermer la fenêtre modale"
                        @click="hide">
                </button>
                <Loader v-if="searchSubstrates.isLoading" />
                <h3 class="page-main-title modal-header">Rechercher un support d'impression</h3>

                <div class="modal-body">
<!--                    <div v-if="serverErrors" class="notification notification-secondary notification-wrapper" role="alert">-->
<!--                        <div class="notification-container">-->
<!--                            <div class="notification-body">-->
<!--                                <p v-for="(value, key) in serverErrors" :key="key">-->
<!--                                    {{ value[0] }}-->
<!--                                </p>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
                    <form action="#" method="post" class="form-filters">
                        <div class="inline">
                            <ValidationProvider tag="div"
                                                class="wrap-field h-50"
                                                name="family"
                                                v-slot="{ errors }">
                                <span class="btn-right-field" v-if="database.printing.substrates.search.criteria.isLoading">
                                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                </span>
                                <select v-model="searchSubstrates.criteria.family"
                                        @animationstart="checkAnimation"
                                        class="field select"
                                        :class="{ hasValue: searchSubstrates.criteria.family, 'input-error': errors[0] }">
                                    <option value="">Choisir</option>
                                    <option v-for="(family, index) in database.printing.substrates.search.criteria.families"
                                            v-bind:value="family.family">
                                        {{ family.family }}
                                    </option>
                                </select>
                                <span class="focus-field"></span>
                                <label class="label-field">Famille</label>
                                <span class="symbol-left-field"><i class="fas fa-folder-open"></i></span>
                                <span class="v-validate">{{ errors[0] }}</span>
                            </ValidationProvider>

                            <ValidationProvider tag="div"
                                                class="wrap-field h-50"
                                                name="type"
                                                v-slot="{ errors }">
                                <span class="btn-right-field" v-if="database.printing.substrates.search.criteria.isLoading">
                                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                </span>
                                <select v-model="searchSubstrates.criteria.type"
                                        @animationstart="checkAnimation"
                                        class="field select"
                                        :class="{ hasValue: searchSubstrates.criteria.type, 'input-error': errors[0] }">
                                    <option value="">Choisir</option>
                                    <option v-for="(type, index) in database.printing.substrates.search.criteria.types"
                                            v-bind:value="type.type">
                                        {{ type.type }}
                                    </option>
                                </select>
                                <span class="focus-field"></span>
                                <label class="label-field">Type</label>
                                <span class="symbol-left-field"><i class="fas fa-file"></i></span>
                                <span class="v-validate">{{ errors[0] }}</span>
                            </ValidationProvider>

                            <ValidationProvider tag="div"
                                                class="wrap-field h-50"
                                                name="color"
                                                v-slot="{ errors }">
                                <span class="btn-right-field" v-if="database.printing.substrates.search.criteria.isLoading">
                                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                </span>
                                <select v-model="searchSubstrates.criteria.color"
                                        @animationstart="checkAnimation"
                                        class="field select"
                                        :class="{ hasValue: searchSubstrates.criteria.color, 'input-error': errors[0] }">
                                    <option value="">Choisir</option>
                                    <option v-for="(color, index) in database.printing.substrates.search.criteria.colors"
                                            v-bind:value="color.color">
                                        {{ color.color }}
                                    </option>
                                </select>
                                <span class="focus-field"></span>
                                <label class="label-field">Couleur</label>
                                <span class="symbol-left-field"><i class="fas fa-palette"></i></span>
                                <span class="v-validate">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>

                        <div class="inline">
                            <ValidationProvider tag="div"
                                                class="wrap-field h-50"
                                                name="weight"
                                                v-slot="{ errors }">
                                <span class="btn-right-field" v-if="database.printing.substrates.search.criteria.isLoading">
                                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                </span>
                                <select v-model="searchSubstrates.criteria.weight"
                                        @animationstart="checkAnimation"
                                        @keydown.shift.tab.prevent=""
                                        class="field select"
                                        :class="{ hasValue: searchSubstrates.criteria.weight, 'input-error': errors[0] }">
                                    <option value="">Choisir</option>
                                    <option v-for="(weight, index) in database.printing.substrates.search.criteria.weights"
                                            v-bind:value="weight.weight">
                                        {{ weight.weight }}
                                    </option>
                                </select>
                                <span class="focus-field"></span>
                                <label class="label-field">Grammage</label>
                                <span class="symbol-left-field"><i class="fas fa-weight-hanging"></i></span>
                                <span class="v-validate">{{ errors[0] }}</span>
                            </ValidationProvider>

<!--                            <ValidationProvider class="wrap-field h-50"-->
<!--                                                name="supplier"-->
<!--                                                v-slot="{ errors }">-->
<!--                                <span class="btn-right-field" v-if="searchSubstrates.criteria.isLoading">-->
<!--                                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>-->
<!--                                </span>-->
<!--                                <select v-model="searchSubstrates.criteria.supplier"-->
<!--                                        @animationstart="checkAnimation"-->
<!--                                        @keydown.shift.tab.prevent=""-->
<!--                                        class="field select"-->
<!--                                        :class="{ hasValue: searchSubstrates.criteria.supplier }">-->
<!--                                    <option value="">Choisir</option>-->
<!--                                    <option v-for="(supplier, index) in searchSubstrates.datas.suppliers"-->
<!--                                            v-bind:value="supplier.RAISONSOCIALE">-->
<!--                                        {{ supplier.RAISONSOCIALE }}-->
<!--                                    </option>-->
<!--                                </select>-->
<!--                                <span class="focus-field"></span>-->
<!--                                <label class="label-field">Fournisseur</label>-->
<!--                                <span class="symbol-left-field"><i class="fas fa-map-marker-alt"></i></span>-->
<!--                                <span class="v-validate">{{ errors[0] }}</span>-->
<!--                            </ValidationProvider>-->

                            <Autocomplete :items="searchSubstrates.names"
                                          :isAsync="true"
                                          :label="`Désignation`"
                                          icon="fas fa-scroll"
                                          :focus="true"
                                          v-on:search="searchSubstratesForAutocomplete"
                                          v-on:input="setSubstrateName"
                            />
                        </div>

                        <button type="submit"
                                class="button button-small button-primary"
                                style="margin-left: auto;"
                                @click.prevent="getFilteredSubstrates"
                                @keydown.tab.exact.prevent="">
                            Rechercher
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    <div class="container-table" v-if="searchSubstrates.substrates.length || Object.keys(searchSubstrates.substrates).length">
                        <table class="wrap-table">
                            <thead class="table-header">
                            <tr class="table-row">
                                <th scope="col" class="table-cell">Base Ethic</th>
                                <th scope="col" class="table-cell">Famille</th>
                                <th scope="col" class="table-cell">Type</th>
                                <th scope="col" class="table-cell">Désignation</th>
                                <th scope="col" class="table-cell">Couleur</th>
                                <th scope="col" class="table-cell">Grammage</th>
                                <th scope="col" class="table-cell">Laize</th>
                                <th scope="col" class="table-cell">Prix</th>
                            </tr>
                            </thead>
                            <tbody class="table-body">
                            <tr class="table-row" v-for="(substrate, index) in searchSubstrates.substrates" :key="index" @click="selectedSubstrate(substrate)">
                                <td class="table-cell" data-title="Base Ethic">{{ substrate.ethic ? "Oui" : "Non" }}</td>
                                <td class="table-cell" data-title="Famille">{{ substrate.family }}</td>
                                <td class="table-cell" data-title="Type">{{ substrate.type }}</td>
                                <td class="table-cell" data-title="Désignation">{{ substrate.name }}</td>
                                <td class="table-cell" data-title="Couleur">{{ substrate.color }}</td>
                                <td class="table-cell" data-title="Grammage">{{ substrate.weight }}</td>
                                <td class="table-cell" data-title="Laize">{{ substrate.width }}</td>
                                <td class="table-cell" data-title="Prix">{{ substrate.price }}</td>
                            </tr>
                            </tbody>
                        </table>
                        <Pagination :pagination="searchSubstrates.pagination" v-on:pagechanged="getFilteredSubstrates"></Pagination>
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
    </div>
</template>

<script>
    import Autocomplete from "./Autocomplete";
    import Pagination from "../Pagination";
    import Loader from '../Loader';

    export default {
        components: {
            Pagination,
            Autocomplete,
            Loader
        },
        data() {
            return {
                searchSubstrates: {
                    criteria: {
                        isLoading: false,
                        family: "",
                        type: "",
                        color: "",
                        weight: "",
                        // supplier: "",
                        name: "",
                    },
                    names: [],
                    substrates: [],
                    pagination: {}
                }
            }
        },
        created() {
            this.searchSubstrates.names = [];
        },
        mounted() {
            this.form.printing.colors = this.form.description.quantities[0].plate;
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
            checkAnimation({ target, animationName }) {
                if(animationName.startsWith("onAutoFillStart")) {
                    target.classList.add("hasValue");
                }
            },
            show() {
                this.$modal.show('search-substrate');
            },
            opened() {
                if (this.database.printing.substrates.search.criteria.types.length < 1) {
                    this.getSubstratesSearchCriteria();
                }
            },
            hide() {
                this.$modal.hide('search-substrate');
            },
            searchSubstratesForAutocomplete(query) {
                this.searchSubstrates.names = [];
                this.searchSubstrates.criteria.name = query.toUpperCase();
                if (query.length > 2) {
                    this.$store.dispatch("searchSubstratesForAutocomplete", {
                        queryString: query,
                    }).then(response => {
                        this.searchSubstrates.names = response;
                    }).catch(() => {
                        this.$toast.error({
                            title: "Erreur",
                            message: "Oups, un problème est survenu pour charger les supports d'impression"
                        });
                    });
                }
            },
            setSubstrateName(value) {
                this.searchSubstrates.criteria.name = value.toUpperCase();
            },
            getFilteredSubstrates(page) {
                this.searchSubstrates.substrates = [];
                this.searchSubstrates.isLoading = true;

                page = page || 1;
                this.$store.dispatch("getFilteredSubstrates", {
                    filters: this.searchSubstrates.criteria,
                    page: parseInt(page)
                }).then(response => {
                    this.searchSubstrates.substrates = response.data;
                    console.log(this.searchSubstrates.substrates);
                    // this.searchSubstrates.isLoading = false;
                    this.makePagination(response);
                }).catch(error => {
                    this.searchSubstrates.substrates = [];
                    this.$toast.error({
                        title: "Erreur",
                        message: "Oups, un problème est survenu pour charger les supports d'impression"
                    });
                    this.searchSubstrates.isLoading = false;
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

                this.searchSubstrates.pagination = pagination;
                this.searchSubstrates.isLoading = false;
            },
            selectedSubstrate(substrate) {
                this.form.printing.substrate.ethic = substrate.ethic;
                this.form.printing.substrate.id = substrate.id;
                this.form.printing.substrate.name = substrate.name;
                this.form.printing.substrate.width = substrate.width;
                this.form.printing.substrate.weight = substrate.weight;
                this.form.printing.substrate.price = substrate.price;
                this.hide();
            },
            getSubstratesSearchCriteria() {
                if (this.form.printing.substrate.type === "old") {
                    this.database.printing.substrates.search.criteria.isLoading = true;
                    this.$store.dispatch('getSubstratesSearchCriteria').then(() => {
                        this.database.printing.substrates.search.criteria.isLoading = false;
                    }).catch(() => {
                        this.database.printing.substrates.search.criteria.isLoading = false;
                        this.$toast.error({
                            title: "Erreur",
                            message: "Oups, un problème est survenu pour charger les critères de recherche"
                        });
                    });
                }
            },
            resetSubstrate() {
                this.getSubstratesSearchCriteria();
                this.form.printing.substrate.ethic = false;
                this.form.printing.substrate.id = "";
                this.form.printing.substrate.name = "";
                this.form.printing.substrate.width = "";
                this.form.printing.substrate.weight = "";
                this.form.printing.substrate.price = "";
                this.searchSubstrates.substrates = [];
            },
            handlePressChange(event) {
                // get the name of the printing press for summary
                if (event.target.options.selectedIndex > 0) {
                    this.form.printing.name = event.target.options[event.target.options.selectedIndex].dataset.name;
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';
</style>
