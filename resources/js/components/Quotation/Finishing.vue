<template>
    <div>
        <div v-for="(item, index) in form.finishing.finishings">
            <div class="wrap-group-field"
                 :class="[{ hasValue: item.id },
                          { hasValue: item.die.id },
                          { hasValue: (item.presence_consumable && item.consumable.name !== '') },
                          { hasValue: (item.presence_consumable && item.consumable.width !== '') },
                          { hasValue: (item.presence_consumable && item.consumable.price !== '') },
                          { hasFocus: item.hasFocus }]">

                    <span class="btn-right-field" @click="deleteFinishing(index)">
                        <i class="fas fa-times"></i>
                    </span>
                <ValidationProvider tag="div"
                                    class="wrap-field h-50"
                                    name="finishing type"
                                    v-slot="{ errors }">
                    <span class="btn-right-field" v-if="finishingsAreLoading">
                        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                    </span>
                    <select v-model="item.id"
                            @focus="item.hasFocus = true"
                            @blur="item.hasFocus = false"
                            @animationstart="checkAnimation"
                            class="field select"
                            :class="{ hasValue: item.id, 'input-error': errors[0] }"
                            @change="handleFinishingChanging($event, index)">
                        <option value="">Pas de finition</option>
                        <option v-for="finishing in database.finishing.finishings"
                                v-bind:value="finishing.id"
                                :data-name="finishing.name">
                            {{ finishing.name }}
                        </option>
                    </select>
                    <label class="label-field">Type de finition</label>
                    <span class="v-validate">{{ errors[0] }}</span>
                </ValidationProvider>

                <div class="wrap-field h-50" v-if="item.id !== '' && dies[index]">
                    <select v-model="item.die.id"
                            @focus="item.hasFocus = true"
                            @blur="item.hasFocus = false"
                            @animationstart="checkAnimation"
                            class="field select"
                            :class="{ hasValue: item.die.id }"
                            required>
                        <option disabled value="">Choisir</option>
                        <option v-for="die in dies[index]"
                                v-bind:value="die.id">
                            {{ die.name }}
                        </option>
                    </select>
                    <label class="label-field">Outil</label>
                </div>

                <div class="wrap-field h-50" v-else-if="item.id !== '' && item.die.id !== ''">
                    <select v-model="item.die.id"
                            @focus="item.hasFocus = true"
                            @blur="item.hasFocus = false"
                            @animationstart="checkAnimation"
                            class="field select"
                            :class="{ hasValue: item.die.id }"
                            required>
                        <option v-bind:value="item.die.id">
                            {{ item.die.name }} // {{ item.die.width }} // {{ item.die.length }}
                        </option>
                    </select>
                    <label class="label-field">Outil</label>
                </div>

                <ValidationProvider v-else-if="item.id !== ''"
                                    tag="div"
                                    class="wrap-field h-50"
                                    rules="numeric"
                                    name="die price"
                                    v-slot="{ errors }">
                    <input v-model="item.die.price"
                           @focus="item.hasFocus = true"
                           @blur="item.hasFocus = false"
                           class="field"
                           :class="{ hasValue: item.die.price, 'input-error': errors[0] }"
                           type="number"
                           step="0.0001"
                           autocomplete="off">
                    <label class="label-field">Prix de l'outil à commander</label>
                    <span class="v-validate">{{ errors[0] }}</span>
                </ValidationProvider>

                <div class="wrap-field h-50" v-if="database.finishing.reworkings.length > 0">
                    <span class="btn-right-field" v-if="finishingsAreLoading">
                        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                    </span>
                    <select v-model="item.reworking"
                            @focus="item.hasFocus = true"
                            @blur="item.hasFocus = false"
                            @animationstart="checkAnimation"
                            class="field select"
                            :class="{ hasValue: item.reworking }">
                        <option value="">Pas de reprise</option>
                        <option v-for="reworking in database.finishing.reworkings"
                                v-bind:value="reworking.id"
                                :data-name="reworking.name">
                            {{ reworking.name }}
                        </option>
                    </select>
                    <label class="label-field">En reprise sur</label>
                </div>

                <div class="wrap-field h-50" v-if="item.presence_consumable && item.id !== '' && consumables[index]">
                    <select v-model="item.consumable.id"
                            @focus="item.hasFocus = true"
                            @blur="item.hasFocus = false"
                            @animationstart="checkAnimation"
                            class="field select"
                            :class="{ hasValue: item.consumable.id }"
                            @change="handleConsumable($event, index)"
                            required>
                        <option value="">Choisir</option>
                        <option v-for="consumable in consumables[index]"
                                v-bind:value="consumable.id"
                                :data-ethic="consumable.ethic"
                                :data-price="consumable.price">
                            {{ consumable.name }}
                        </option>
                    </select>
                    <label class="label-field">Désignation du consommable</label>
                </div>

                <ValidationProvider v-if="item.presence_consumable && item.id !== '' && !consumables[index]"
                                    tag="div"
                                    class="wrap-field h-50"
                                    name="consumable name"
                                    v-slot="{ errors }">
                    <input v-model.trim="item.consumable.name"
                           @focus="item.hasFocus = true"
                           @blur="item.hasFocus = false"
                           class="field"
                           :class="{ hasValue: item.consumable.name, 'input-error': errors[0] }"
                           type="text"
                           autocomplete="off">
                    <label class="label-field">Désignation du consommable</label>
                    <span class="v-validate">{{ errors[0] }}</span>
                </ValidationProvider>

                <div class="wrap-field-inline" v-if="item.presence_consumable && item.id !== '' && consumables[index]">
                    <span class="legend-line">Consommable</span>
                    <ValidationProvider tag="div"
                                        class="wrap-field h-50"
                                        rules="required|numeric"
                                        name="consumable width"
                                        v-slot="{ errors }">
                        <input v-model="item.consumable.width"
                               @focus="item.hasFocus = true"
                               @blur="item.hasFocus = false"
                               class="field"
                               :class="{ hasValue: item.consumable.width, 'input-error': errors[0] }"
                               type="number"
                               autocomplete="off"
                               required>
                        <label class="label-field">Laize du consommable (mm)</label>
                        <span class="v-validate">{{ errors[0] }}</span>
                    </ValidationProvider>

                    <ValidationProvider tag="div"
                                        class="wrap-field h-50"
                                        rules="required"
                                        name="consumable price"
                                        v-slot="{ errors }">
                        <input v-model="item.consumable.price"
                               @focus="item.hasFocus = true"
                               @blur="item.hasFocus = false"
                               class="field"
                               :class="{ hasValue: item.consumable.price, 'input-error': errors[0] }"
                               type="number"
                               step="0.0001"
                               autocomplete="off"
                               required>
                        <label class="label-field">Prix du consommable (€/m&#xB2;)</label>
                        <span class="v-validate">{{ errors[0] }}</span>
                    </ValidationProvider>
                </div>
                <span class="focus-field"></span>
                <span class="symbol-left-field"><i class="fas fa-cut"></i></span>
            </div>
        </div>

        <button type="button"
                style="margin-bottom: 2.5rem;"
                class="button button-small button-secondary button-dashed"
                @click="addFinishing">
            <i class="fas fa-plus"></i>
            Ajouter une finition
        </button>


        <div class="wrap-radio">
            <div class="wrap-field">
                <input type="radio" id="cutting_old" v-model="form.finishing.cutting.type" value="old" @click="resetCutting">
                <label for="cutting_old">
                    <i class="fas fa-search"></i>
                    <span>Rechercher un outil</span>
                </label>
            </div>
            <div class="wrap-field">
                <input type="radio" id="cutting_new" v-model="form.finishing.cutting.type" value="new" @click="resetCutting">
                <label for="cutting_new">
                    <i class="fas fa-file-medical"></i>
                    <span>Créer un outil</span>
                </label>
            </div>
        </div>

        <div class="wrap-group-field"
             :class="[{ hasValue: form.finishing.cutting.name },
                      { hasValue: form.finishing.cutting.dimension_width },
                      { hasValue: form.finishing.cutting.dimension_length },
                      { hasValue: form.finishing.cutting.bleed_width },
                      { hasValue: form.finishing.cutting.bleed_length },
                      { hasValue: form.finishing.cutting.pose_width },
                      { hasValue: form.finishing.cutting.pose_length },
                      { hasFocus: form.finishing.cutting.hasFocus }]">
            <div class="wrap-field h-50" v-if="form.finishing.cutting.type === 'old'">
                <select v-model="form.finishing.cutting.id"
                        @focus="form.finishing.cutting.hasFocus = true"
                        @blur="form.finishing.cutting.hasFocus = false"
                        @animationstart="checkAnimation"
                        class="field select"
                        :class="{ hasValue: form.finishing.cutting.id }"
                        @change="handleCuttingChanging(form.finishing.cutting.id)"
                        required>
                    <option value="">Choisir</option>
                    <option v-for="cutting in cuttings"
                            v-bind:value="cutting.id">
                        {{ cutting.name }} // {{ cutting.width }} // {{ cutting.length }}
                    </option>
                </select>
                <label class="label-field">Désignation de l'outil</label>
            </div>

            <div class="wrap-field h-50" v-else>
                <input v-model.trim="form.finishing.cutting.name"
                       @focus="form.finishing.cutting.hasFocus = true"
                       @blur="form.finishing.cutting.hasFocus = false"
                       class="field"
                       :class="{ hasValue: form.finishing.cutting.name }"
                       type="text"
                       autocomplete="off">
                <label class="label-field">Désignation de l'outil</label>
            </div>
            <div class="wrap-field-inline">
                <span class="legend-line">Dimensions (mm)</span>
                <ValidationProvider tag="div"
                                    class="wrap-field h-50"
                                    rules="required"
                                    name="dimension width"
                                    v-slot="{ errors }">
                    <input v-model="form.finishing.cutting.dimension_width"
                           @focus="form.finishing.cutting.hasFocus = true"
                           @blur="form.finishing.cutting.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.finishing.cutting.dimension_width, 'input-error': errors[0] }"
                           type="number"
                           autocomplete="off"
                           required>
                    <label class="label-field">Laize</label>
                    <span class="v-validate">{{ errors[0] }}</span>
                </ValidationProvider>
                <ValidationProvider tag="div"
                                    class="wrap-field h-50"
                                    rules="required"
                                    name="dimension length"
                                    v-slot="{ errors }">
                    <input v-model="form.finishing.cutting.dimension_length"
                           @focus="form.finishing.cutting.hasFocus = true"
                           @blur="form.finishing.cutting.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.finishing.cutting.dimension_length, 'input-error': errors[0] }"
                           type="number"
                           autocomplete="off"
                           required>
                    <label class="label-field">Avance</label>
                    <span class="v-validate">{{ errors[0] }}</span>
                </ValidationProvider>
            </div>
            <div class="wrap-field-inline">
                <span class="legend-line">Entreposes (mm)</span>
                <ValidationProvider tag="div"
                                    class="wrap-field h-50"
                                    rules="required|numeric"
                                    name="bleed width"
                                    v-slot="{ errors }">
                    <input v-model="form.finishing.cutting.bleed_width"
                           @focus="form.finishing.cutting.hasFocus = true"
                           @blur="form.finishing.cutting.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.finishing.cutting.bleed_width, 'input-error': errors[0] }"
                           type="number"
                           autocomplete="off"
                           required>
                    <label class="label-field">Laize</label>
                    <span class="v-validate">{{ errors[0] }}</span>
                </ValidationProvider>
                <ValidationProvider tag="div"
                                    class="wrap-field h-50"
                                    rules="required|numeric"
                                    name="bleed length"
                                    v-slot="{ errors }">
                    <input v-model="form.finishing.cutting.bleed_length"
                           @focus="form.finishing.cutting.hasFocus = true"
                           @blur="form.finishing.cutting.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.finishing.cutting.bleed_length, 'input-error': errors[0] }"
                           type="number"
                           autocomplete="off"
                           required>
                    <label class="label-field">Avance</label>
                    <span class="v-validate">{{ errors[0] }}</span>
                </ValidationProvider>
            </div>
            <div class="wrap-field-inline">
                <span class="legend-line">Poses (mm)</span>
                <ValidationProvider tag="div"
                                    class="wrap-field h-50"
                                    rules="required|numeric"
                                    name="pose width"
                                    v-slot="{ errors }">
                    <input v-model="form.finishing.cutting.pose_width"
                           @focus="form.finishing.cutting.hasFocus = true"
                           @blur="form.finishing.cutting.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.finishing.cutting.pose_width, 'input-error': errors[0] }"
                           type="number"
                           autocomplete="off"
                           required>
                    <label class="label-field">Laize</label>
                    <span class="v-validate">{{ errors[0] }}</span>
                </ValidationProvider>
                <ValidationProvider tag="div"
                                    class="wrap-field h-50"
                                    rules="required|numeric"
                                    name="pose length"
                                    v-slot="{ errors }">
                    <input v-model="form.finishing.cutting.pose_length"
                           @focus="form.finishing.cutting.hasFocus = true"
                           @blur="form.finishing.cutting.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.finishing.cutting.pose_length, 'input-error': errors[0] }"
                           type="number"
                           autocomplete="off"
                           required>
                    <label class="label-field">Avance</label>
                    <span class="v-validate">{{ errors[0] }}</span>
                </ValidationProvider>
            </div>

            <ValidationProvider v-if="form.finishing.cutting.type === 'new'"
                                tag="div"
                                class="wrap-field h-50"
                                name="cutting price"
                                v-slot="{ errors }">
                <input v-model="form.finishing.cutting.shape"
                       @focus="form.finishing.cutting.hasFocus = true"
                       @blur="form.finishing.cutting.hasFocus = false"
                       class="field"
                       :class="{ hasValue: form.finishing.cutting.shape, 'input-error': errors[0] }"
                       type="number"
                       step="0.0001"
                       autocomplete="off">
                <label class="label-field">Prix de l'outil à commander</label>
                <span class="v-validate">{{ errors[0] }}</span>
            </ValidationProvider>
            <span class="focus-field"></span>
            <span class="symbol-left-field"><i class="fas fa-crop-alt"></i></span>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                finishingsAreLoading: false,
                dies: [],
                consumables: [],
                cuttings: []
            }
        },
        created() {
            if (this.database.finishing.finishings === undefined || this.database.finishing.finishings.length < 1) {
                this.finishingsAreLoading = true;
                this.$store.dispatch('getFinishings').then(() => {
                    this.finishingsAreLoading = false;

                    this.filteredCuttings();
                }).catch(() => {
                    this.finishingsAreLoading = false;
                    this.$toast.error({
                        title: "Erreur",
                        message: "Oups, un problème est survenu pour charger les finitions"
                    });
                });
            } else {
                this.filteredCuttings();
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
                if (animationName.startsWith("onAutoFillStart")) {
                    target.classList.add("hasValue");
                }
            },
            addFinishing() {
                this.form.finishing.finishings.push({
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
                });
            },
            deleteFinishing(index) {
                this.form.finishing.finishings.splice(index, 1);
            },
            handleFinishingChanging(event, index) {
                if (event.target.options.selectedIndex > 0) {
                    this.form.finishing.finishings[index].name = event.target.options[event.target.options.selectedIndex].dataset.name;
                }
                let finishingID = this.form.finishing.finishings[index].id;
                let finishing = this.database.finishing.finishings.find(finishing => finishing.id === finishingID);

                if (finishing.presence_die) {
                    this.dies[index] = finishing.die;
                    // let newDie = {
                    //     id: "",
                    //     name: "",
                    //     price: "",
                    //     hasFocus: false,
                    // };
                    // this.form.finishing.finishings[index].die = newDie;
                } else {
                    this.form.finishing.finishings[index].die = {
                        id: "",
                        name: "",
                        price: "",
                    };
                }

                if (finishing.presence_consumable) {
                    this.consumables[index] = finishing.consumable;
                    let newConsumable = {
                        id: "",
                        name: "",
                        width: "",
                        price: "",
                        ethic: false,
                        hasFocus: false,
                    };

                    this.form.finishing.finishings[index].consumable = newConsumable;
                    this.form.finishing.finishings[index].presence_consumable = true;
                } else {
                    this.form.finishing.finishings[index].presence_consumable = false;
                    this.form.finishing.finishings[index].consumable = "";
                }
            },
            handleConsumable(event, index) {
                if (event.target.options.selectedIndex > 0) {
                    this.form.finishing.finishings[index].consumable.price = event.target.options[event.target.options.selectedIndex].dataset.price;
                    this.form.finishing.finishings[index].consumable.ethic = event.target.options[event.target.options.selectedIndex].dataset.ethic;
                } else {
                    this.form.finishing.finishings[index].consumable.price = "";
                    this.form.finishing.finishings[index].consumable.ethic = false;
                }
            },
            filteredCuttings() {
                let allCuttings = this.database.finishing.cuttings;

                let matches = [];
                if (allCuttings.length > 1) {
                    allCuttings.filter(value => {
                        if (value['width'].toString().indexOf(this.form.description.label.width.toString()) > -1 && value['length'].toString().indexOf(this.form.description.label.length.toString()) > -1) {
                            matches.push(value);
                        }
                    });
                } else {
                    matches = allCuttings;
                }

                this.cuttings = matches;
            },
            handleCuttingChanging(cuttingID) {
                let cutting = this.database.finishing.cuttings.find(cutting => cutting.id === cuttingID);

                this.form.finishing.cutting.ethic = cutting.ethic;
                this.form.finishing.cutting.id = cutting.id;
                this.form.finishing.cutting.dimension_width = cutting.width;
                this.form.finishing.cutting.dimension_length = cutting.length;
            },
            resetCutting() {
                this.cuttings = [];
                this.form.finishing.cutting.ethic = false;
                this.form.finishing.cutting.id = "";
                this.form.finishing.cutting.name = "";

                if (this.form.finishing.cutting.type === "new") {
                    this.form.finishing.cutting.dimension_width = this.form.description.label.width;
                    this.form.finishing.cutting.dimension_length = this.form.description.label.length;
                } else {
                    this.form.finishing.cutting.dimension_width = "";
                    this.form.finishing.cutting.dimension_length = "";
                }
                this.form.finishing.cutting.bleed_width = "";
                this.form.finishing.cutting.bleed_length = "";
                this.form.finishing.cutting.pose_width = "";
                this.form.finishing.cutting.pose_length = "";
                this.form.finishing.cutting.shape = "";
            },
        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';
</style>
