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
                <div class="wrap-field h-50">
                    <span class="btn-right-field" v-if="finishingsAreLoading">
                        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                    </span>
                    <select v-model="item.id"
                            @focus="item.hasFocus = true"
                            @blur="item.hasFocus = false"
                            @animationstart="checkAnimation"
                            class="field select"
                            :class="{ hasValue: item.id }"
                            @change="handleFinishingChanging($event, index)"
                            required>
                        <option disabled value="">Choisir</option>
                        <option v-for="finishing in database.finishing.finishings"
                                v-bind:value="finishing.id"
                                :data-name="finishing.name">
                            {{ finishing.name }}
                        </option>
                    </select>
                    <label class="label-field">Type de finition</label>
                </div>

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

                <div class="wrap-field h-50" v-else>
                    <input v-model="item.die.price"
                           @focus="item.hasFocus = true"
                           @blur="item.hasFocus = false"
                           class="field"
                           :class="{ hasValue: item.die.price }"
                           type="number"
                           step="0.0001"
                           autocomplete="off">
                    <label class="label-field">Prix de l'outil à commander</label>
                </div>

                <div class="wrap-field h-50">
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
                            @change="handleConsumableChanging($event, index)"
                            required>
                        <option value="">Choisir</option>
                        <option v-for="consumable in consumables[index]"
                                v-bind:value="consumable.id">
                            {{ consumable.name }}
                        </option>
                    </select>
                    <label class="label-field">Désignation du consommable</label>
                </div>

                <div class="wrap-field h-50" v-if="item.presence_consumable && item.id !== '' && !consumables[index]">
                    <input v-model.trim="item.consumable.name"
                           @focus="item.hasFocus = true"
                           @blur="item.hasFocus = false"
                           class="field"
                           :class="{ hasValue: item.consumable.name }"
                           type="text"
                           autocomplete="off">
                    <label class="label-field">Désignation du consommable</label>
                </div>

                <div class="wrap-field h-50" v-if="item.presence_consumable && item.id !== '' && !consumables[index]">
                    <input v-model="item.consumable.width"
                           @focus="item.hasFocus = true"
                           @blur="item.hasFocus = false"
                           class="field"
                           :class="{ hasValue: item.consumable.width }"
                           type="number"
                           autocomplete="off"
                           required>
                    <label class="label-field">Laize du consommable (mm)</label>
                </div>

                <div class="wrap-field h-50" v-if="item.presence_consumable && item.id !== '' && !consumables[index]">
                    <input v-model="item.consumable.price"
                           @focus="item.hasFocus = true"
                           @blur="item.hasFocus = false"
                           class="field"
                           :class="{ hasValue: item.consumable.price }"
                           type="number"
                           step="0.0001"
                           autocomplete="off"
                           required>
                    <label class="label-field">Prix du consommable (€/m&#xB2;)</label>
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
                <input type="radio" id="cutting_old" v-model="form.finishing.cutting.type" value="old">
                <label for="cutting_old">
                    <i class="fas fa-search"></i>
                    <span>Rechercher un outil</span>
                </label>
            </div>
            <div class="wrap-field">
                <input type="radio" id="cutting_new" v-model="form.finishing.cutting.type" value="new">
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
            <div class="wrap-field h-50">
                <button type="button"
                        v-if="form.finishing.cutting.type === 'old'"
                        class="btn-right-field"
                        id="show-modal">
                    <i class="fas fa-search"></i>
                </button>
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
                <div class="wrap-field h-50">
                    <input v-model="form.finishing.cutting.dimension_width"
                           @focus="form.finishing.cutting.hasFocus = true"
                           @blur="form.finishing.cutting.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.finishing.cutting.dimension_width }"
                           type="number"
                           autocomplete="off"
                           required>
                    <label class="label-field">Laize</label>
                </div>
                <div class="wrap-field h-50">
                    <input v-model="form.finishing.cutting.dimension_length"
                           @focus="form.finishing.cutting.hasFocus = true"
                           @blur="form.finishing.cutting.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.finishing.cutting.dimension_length }"
                           type="number"
                           autocomplete="off"
                           required>
                    <label class="label-field">Avance</label>
                </div>
            </div>
            <div class="wrap-field-inline">
                <span class="legend-line">Entreposes (mm)</span>
                <div class="wrap-field h-50">
                    <input v-model="form.finishing.cutting.bleed_width"
                           @focus="form.finishing.cutting.hasFocus = true"
                           @blur="form.finishing.cutting.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.finishing.cutting.bleed_width }"
                           type="number"
                           autocomplete="off"
                           required>
                    <label class="label-field">Laize</label>
                </div>
                <div class="wrap-field h-50">
                    <input v-model="form.finishing.cutting.bleed_length"
                           @focus="form.finishing.cutting.hasFocus = true"
                           @blur="form.finishing.cutting.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.finishing.cutting.bleed_length }"
                           type="number"
                           autocomplete="off"
                           required>
                    <label class="label-field">Avance</label>
                </div>
            </div>
            <div class="wrap-field-inline">
                <span class="legend-line">Poses (mm)</span>
                <div class="wrap-field h-50">
                    <input v-model="form.finishing.cutting.pose_width"
                           @focus="form.finishing.cutting.hasFocus = true"
                           @blur="form.finishing.cutting.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.finishing.cutting.pose_width }"
                           type="number"
                           autocomplete="off"
                           required>
                    <label class="label-field">Laize</label>
                </div>
                <div class="wrap-field h-50">
                    <input v-model="form.finishing.cutting.pose_length"
                           @focus="form.finishing.cutting.hasFocus = true"
                           @blur="form.finishing.cutting.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.finishing.cutting.pose_length }"
                           type="number"
                           autocomplete="off"
                           required>
                    <label class="label-field">Avance</label>
                </div>
            </div>

            <div v-if="form.finishing.cutting.type === 'new'" class="wrap-field h-50">
                <input v-model="form.finishing.cutting.shape"
                       @focus="form.finishing.cutting.hasFocus = true"
                       @blur="form.finishing.cutting.hasFocus = false"
                       class="field"
                       :class="{ hasValue: form.finishing.cutting.shape }"
                       type="number"
                       step="0.0001"
                       autocomplete="off">
                <label class="label-field">Prix de l'outil à commander</label>
            </div>
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
                consumables: []
            }
        },
        created() {
            this.form.finishing.cutting.dimension_width = this.form.description.label.width;
            this.form.finishing.cutting.dimension_length = this.form.description.label.length;

            console.log("Finishings :");
            console.log(this.form.finishing.finishings);
            console.log(this.database.finishing.finishings);

            if (this.database.finishing.finishings === undefined || this.database.finishing.finishings.length < 1) {
                this.finishingsAreLoading = true;
                this.$store.dispatch('getFinishings').then(() => {
                    console.log(this.database.finishing.finishings);
                    console.log(this.form.finishing.finishings);
                    this.finishingsAreLoading = false;
                });
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
                console.log("finishing");
                console.log(finishing);
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
                console.log(this.dies[index]);

                if (finishing.presence_consumable) {
                    this.consumables[index] = finishing.consumable;
                    console.log(this.consumables[index]);
                    let newConsumable = {
                        id: "",
                        name: "",
                        width: "",
                        price: "",
                        hasFocus: false,
                    };
                    console.log(this.form.finishing);

                    this.form.finishing.finishings[index].consumable = newConsumable;
                    this.form.finishing.finishings[index].presence_consumable = true;
                } else {
                    this.form.finishing.finishings[index].presence_consumable = false;
                    this.form.finishing.finishings[index].consumable = "";
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';
</style>
