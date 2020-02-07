<template>
    <div>
        <div class="wrap-radio">
            <div class="wrap-field">
                <input type="radio" id="label_old" v-model="form.description.label.type" value="old" :disabled="!this.database.description.labels.length" @click="resetLabel">
                <label for="label_old">
                    <i class="fas fa-search"></i>
                    <span>Rechercher une étiquette</span>
                </label>
            </div>
            <div class="wrap-field">
                <input type="radio" id="label_new" v-model="form.description.label.type" value="new" @click="resetLabel">
                <label for="label_new">
                    <i class="fas fa-file-medical"></i>
                    <span>Créer une étiquette</span>
                </label>
            </div>
        </div>

        <div class="wrap-group-field"
             :class="[{ hasValue: form.description.label.name },
                      { hasValue: form.description.label.width },
                      { hasValue: form.description.label.length },
                      { hasFocus: form.description.label.hasFocus }]">
            <ValidationProvider tag="div"
                                class="wrap-field h-50"
                                name="name"
                                v-slot="{ errors }">
                <span v-if="form.description.label.type === 'old'" class="btn-right-field" @click="show">
                    <i class="fas fa-search"></i>
                </span>
                <input v-model.trim="form.description.label.name"
                       @focus="form.description.label.hasFocus = true"
                       @blur="form.description.label.hasFocus = false"
                       class="field"
                       :class="{ hasValue: form.description.label.name, 'input-error': errors[0] }"
                       type="text"
                       autocomplete="off">
                <label class="label-field">Désignation de l'étiquette</label>
                <span class="v-validate">{{ errors[0] }}</span>
            </ValidationProvider>
            <div class="wrap-field-inline">
                <ValidationProvider tag="div"
                                    class="wrap-field h-50"
                                    rules="required|numeric"
                                    name="width"
                                    v-slot="{ errors }">
                    <input v-model="form.description.label.width"
                           @focus="form.description.label.hasFocus = true"
                           @blur="form.description.label.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.description.label.width, 'input-error': errors[0] }"
                           type="number"
                           autocomplete="off"
                           required>
                    <label class="label-field">Laize (mm)</label>
                    <span class="v-validate">{{ errors[0] }}</span>
                </ValidationProvider>
                <ValidationProvider tag="div"
                                    class="wrap-field h-50"
                                    rules="required|numeric"
                                    name="length"
                                    v-slot="{ errors }">
                    <input v-model="form.description.label.length"
                           @focus="form.description.label.hasFocus = true"
                           @blur="form.description.label.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.description.label.length, 'input-error': errors[0] }"
                           type="number"
                           autocomplete="off"
                           required>
                    <label class="label-field">Avance (mm)</label>
                    <span class="v-validate">{{ errors[0] }}</span>
                </ValidationProvider>
            </div>
            <span class="focus-field"></span>
            <span class="symbol-left-field"><i class="fas fa-image"></i></span>
        </div>

        <div v-for="(item, index) in form.description.quantities"
             class="wrap-group-field"
             :class="[{ hasValue: item.quantity },
                      { hasValue: item.model },
                      { hasValue: item.plate },
                      { hasFocus: item.hasFocus }]">


                <span class="btn-right-field" @click="deleteQuantity(index)">
                    <i class="fas fa-times"></i>
                </span>
            <ValidationProvider tag="div"
                                class="wrap-field h-50"
                                rules="required|numeric"
                                name="quantity"
                                v-slot="{ errors }">
                <input v-model="item.quantity"
                       @focus="item.hasFocus = true"
                       @blur="item.hasFocus = false"
                       class="field"
                       :class="{ hasValue: item.quantity, 'input-error': errors[0] }"
                       type="number"
                       autocomplete="off"
                       required>
                <label class="label-field">Nombre d'exemplaires</label>
                <span class="v-validate">{{ errors[0] }}</span>
            </ValidationProvider>
            <ValidationProvider tag="div"
                                class="wrap-field h-50"
                                rules="required|numeric"
                                name="model"
                                v-slot="{ errors }">
                <input v-model="item.model"
                       @focus="item.hasFocus = true"
                       @blur="item.hasFocus = false"
                       class="field"
                       :class="{ hasValue: item.model, 'input-error': errors[0] }"
                       type="number"
                       autocomplete="off"
                       required>
                <label class="label-field">Nombre de modèles</label>
                <span class="v-validate">{{ errors[0] }}</span>
            </ValidationProvider>
            <ValidationProvider tag="div"
                                class="wrap-field h-50"
                                rules="required|numeric"
                                name="plate"
                                v-slot="{ errors }">
                <input v-model="item.plate"
                       @focus="item.hasFocus = true"
                       @blur="item.hasFocus = false"
                       class="field"
                       :class="{ hasValue: item.plate, 'input-error': errors[0] }"
                       type="number"
                       autocomplete="off"
                       required>
                <label class="label-field">Nombre de clichés</label>
                <span class="v-validate">{{ errors[0] }}</span>
            </ValidationProvider>
            <div class="wrap-field-inline">
                <span class="legend-line">Temps prépresse</span>
                <ValidationProvider tag="div"
                                    class="wrap-field h-50"
                                    rules="numeric"
                                    name="prepress hour"
                                    v-slot="{ errors }">
                    <input v-model="item.hour"
                           @focus="item.hasFocus = true"
                           @blur="item.hasFocus = false"
                           class="field"
                           :class="{ hasValue: item.hour, 'input-error': errors[0] }"
                           type="number"
                           autocomplete="off">
                    <label class="label-field">Heure(s)</label>
                    <span class="v-validate">{{ errors[0] }}</span>
                </ValidationProvider>
                <ValidationProvider tag="div"
                                    class="wrap-field h-50"
                                    rules="numeric"
                                    name="prepress minutes"
                                    v-slot="{ errors }">
                    <input v-model="item.minute"
                           @focus="item.hasFocus = true"
                           @blur="item.hasFocus = false"
                           class="field"
                           :class="{ hasValue: item.minute, 'input-error': errors[0] }"
                           type="number"
                           autocomplete="off">
                    <label class="label-field">Minute(s)</label>
                    <span class="v-validate">{{ errors[0] }}</span>
                </ValidationProvider>
            </div>
            <span class="focus-field"></span>
            <span class="symbol-left-field"><i class="fas fa-copy"></i></span>
        </div>

        <button type="button"
                class="button button-small button-secondary button-dashed"
                @click="addQuantity">
            <i class="fas fa-plus"></i>
            Ajouter une quantité
        </button>


        <modal name="search-label" @opened="opened" :adaptive="true" :width="1180" :height="640">
            <div>
                <button type="button"
                        class="modal-close"
                        aria-label="Fermer la fenêtre modale"
                        @click="hide">
                </button>
                <Loader v-if="searchLabels.isLoading" />
                <h3 class="page-main-title modal-header">Rechercher une étiquette</h3>

                <div class="modal-body">
                    <form action="#" method="post" class="form-filters">
                        <div class="inline">
                            <ValidationProvider tag="div"
                                                class="wrap-field h-50"
                                                rules="numeric"
                                                name="width"
                                                v-slot="{ errors }">
                                <span class="btn-right-field" v-if="searchLabels.criteria.isLoading">
                                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                </span>
                                <select v-model="searchLabels.criteria.width"
                                        ref="searchByLabelWidth"
                                        @keydown.shift.tab.prevent=""
                                        @animationstart="checkAnimation"
                                        class="field select"
                                        :class="{ hasValue: searchLabels.criteria.width, 'input-error': errors[0] }">
                                    <option value="">Choisir</option>
                                    <option v-for="(width, index) in searchLabels.database.widths"
                                            v-bind:value="width">
                                        {{ width }}
                                    </option>
                                </select>
                                <span class="focus-field"></span>
                                <label class="label-field">Laize</label>
                                <span class="symbol-left-field"><i class="fas fa-map-marker-alt"></i></span>
                                <span class="v-validate">{{ errors[0] }}</span>
                            </ValidationProvider>

                            <ValidationProvider tag="div"
                                                class="wrap-field h-50"
                                                rules="numeric"
                                                name="length"
                                                v-slot="{ errors }">
                                <span class="btn-right-field" v-if="searchLabels.criteria.isLoading">
                                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                </span>
                                <select v-model="searchLabels.criteria.length"
                                        @animationstart="checkAnimation"
                                        class="field select"
                                        :class="{ hasValue: searchLabels.criteria.length, 'input-error': errors[0] }">
                                    <option value="">Choisir</option>
                                    <option v-for="(length, index) in searchLabels.database.lengths"
                                            v-bind:value="length">
                                        {{ length }}
                                    </option>
                                </select>
                                <span class="focus-field"></span>
                                <label class="label-field">Avance</label>
                                <span class="symbol-left-field"><i class="fas fa-map-marker-alt"></i></span>
                                <span class="v-validate">{{ errors[0] }}</span>
                            </ValidationProvider>
                        </div>
                        <div class="inline">
                            <ValidationProvider tag="div"
                                                class="wrap-field h-50"
                                                name="reference"
                                                v-slot="{ errors }">
                                <span class="btn-right-field" v-if="searchLabels.criteria.isLoading">
                                    <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                </span>
                                <select v-model="searchLabels.criteria.reference"
                                        @animationstart="checkAnimation"
                                        class="field select"
                                        :class="{ hasValue: searchLabels.criteria.reference, 'input-error': errors[0] }">
                                    <option value="">Choisir</option>
                                    <option v-for="(reference, index) in searchLabels.database.references"
                                            v-bind:value="reference">
                                        {{ reference }}
                                    </option>
                                </select>
                                <span class="focus-field"></span>
                                <label class="label-field">Référence client</label>
                                <span class="symbol-left-field"><i class="fas fa-map-marker-alt"></i></span>
                                <span class="v-validate">{{ errors[0] }}</span>
                            </ValidationProvider>

                            <Autocomplete :items="searchLabels.database.names"
                                          :isAsync="false"
                                          :label="`Désignation`"
                                          :focus="false"
                                          v-on:search="setLabelName"
                                          v-on:input="setLabelName"
                            />
                        </div>

                        <button type="submit"
                                class="button button-small button-primary"
                                style="margin-left: auto;"
                                @click.prevent="getLabels"
                                @keydown.tab.exact.prevent="">
                            Rechercher
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    <div class="container-table" v-if="searchLabels.filteredLabels.length || Object.keys(searchLabels.filteredLabels).length">
                        <table class="wrap-table">
                            <thead class="table-header">
                            <tr class="table-row">
                                <th scope="col" class="table-cell">Base Ethic</th>
                                <th scope="col" class="table-cell">Référence/Variante</th>
                                <th scope="col" class="table-cell">Référence client</th>
                                <th scope="col" class="table-cell">Désignation</th>
                                <th scope="col" class="table-cell">Laize (mm)</th>
                                <th scope="col" class="table-cell">Avance (mm)</th>
                            </tr>
                            </thead>
                            <tbody class="table-body">
                            <tr class="table-row" v-for="(label, index) in searchLabels.filteredLabels" :key="index" @click="selectedLabel(label)">
                                <td class="table-cell" data-title="Base Ethic">{{ label.ethic ? "Oui" : "Non" }}</td>
                                <td class="table-cell" data-title="Référence/Variante">{{ label.id }} // {{ label.variant }}</td>
                                <td class="table-cell" data-title="Référence client">{{ label.reference }}</td>
                                <td class="table-cell" data-title="Désignation">{{ label.name }}</td>
                                <td class="table-cell" data-title="Laize (mm)">{{ label.width }}</td>
                                <td class="table-cell" data-title="Avance (mm)">{{ label.length }}</td>
                            </tr>
                            </tbody>
                        </table>
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
    import Loader from '../Loader';
    import Autocomplete from "./Autocomplete";

    export default {
        components: {
            Autocomplete,
            Loader
        },
        data() {
            return {
                allThirdLabels: [],
                searchLabels: {
                    isLoading: false,
                    criteria: {
                        isLoading: false,
                        reference: "",
                        name: "",
                        width: "",
                        length: "",
                    },
                    database: {
                        references: [],
                        names: [],
                        widths: [],
                        lengths: [],
                    },
                    filteredLabels: []
                }
            }
        },
        created() {
            const labels = this.database.description.labels;
            this.allThirdLabels = labels;
            console.log(labels);
            if (labels.length > 1) {
                this.form.description.label.type = "old";

                let widths = labels.map(item => item.width)
                    .filter((value, index, self) => self.indexOf(value) === index);
                this.searchLabels.database.widths = widths;

                let lengths = labels.map(item => item.length)
                    .filter((value, index, self) => self.indexOf(value) === index);
                this.searchLabels.database.lengths = lengths;

                let references = labels.map(item => item.reference)
                    .filter((value, index, self) => self.indexOf(value) === index);
                this.searchLabels.database.references = references;

                let names = labels.map(item => item.name)
                    .filter((value, index, self) => self.indexOf(value) === index);
                this.searchLabels.database.names = names;
            } else {
                this.form.description.label.type = "new";
            }
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
                this.$modal.show('search-label');
            },
            opened() {
                this.$refs.searchByLabelWidth.focus();
            },
            hide() {
                this.$modal.hide('search-label');
            },
            setLabelName(value) {
                this.searchLabels.criteria.name = value.toUpperCase();
            },
            getLabels() {
                let matchLabels = this.allThirdLabels;
                console.log(matchLabels);
                console.log(this.searchLabels.criteria);
                if (this.searchLabels.criteria.width !== "") {
                    let matches = [];
                    matchLabels.filter(value => {
                        if (value['width'].toString().indexOf(this.searchLabels.criteria.width.toString()) > -1) {
                            matches.push(value);
                        }
                    });
                    matchLabels = matches;
                }

                if (this.searchLabels.criteria.length !== "") {
                    let matches = [];
                    matchLabels.filter(value => {
                        if (value['length'].toString().indexOf(this.searchLabels.criteria.length.toString()) > -1) {
                            matches.push(value);
                        }
                    });
                    matchLabels = matches;
                }

                if (this.searchLabels.criteria.reference !== "") {
                    let matches = [];
                    matchLabels.filter(value => {
                        if (value['reference'].toLowerCase().indexOf(this.searchLabels.criteria.reference.toLowerCase()) > -1) {
                            matches.push(value);
                        }
                    });
                    matchLabels = matches;
                }

                if (this.searchLabels.criteria.name !== "") {
                    let matches = [];
                    matchLabels.filter(value => {
                        if (value['reference'].toLowerCase().indexOf(this.searchLabels.criteria.name.toLowerCase()) > -1) {
                            matches.push(value);
                        }
                    });
                    matchLabels = matches;
                }
                this.searchLabels.filteredLabels = matchLabels;
                console.log(this.searchLabels.filteredLabels);
            },
            selectedLabel(label) {
                this.form.description.label.ethic = label.ethic;
                this.form.description.label.id = label.id;
                this.form.description.label.variant = label.variant;
                this.form.description.label.name = label.name;
                this.form.description.label.width = label.width;
                this.form.description.label.length = label.length;
                this.form.packing.packing = label.packing;

                this.database.printing.substrates.search.criteria.isLoading = true;
                this.$store.dispatch('getSubstratesSearchCriteria').then(() => {
                    this.database.printing.substrates.search.criteria.isLoading = false;
                }).catch(() => {
                    this.database.printing.substrates.search.criteria.isLoading = false;
                });

                console.log("selectedLabel in Description Component");
                console.log("getFinishings");
                this.$store.dispatch('getFinishings');

                this.hide();
            },
            resetLabel() {
                this.form.description.label.ethic = false;
                this.form.description.label.id = "";
                this.form.description.label.variant = "";
                this.form.description.label.name = "";
                this.form.description.label.width = "";
                this.form.description.label.length = "";

                console.log("resetLabel in Description Component");
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
            addQuantity() {
                this.form.description.quantities.push({
                    id: "",
                    quantity: "",
                    model: "",
                    plate: "",
                    hour: "",
                    minute: "",
                    hasFocus: false,
                });
            },
            deleteQuantity(index) {
                this.form.description.quantities.splice(index, 1);
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';
</style>
