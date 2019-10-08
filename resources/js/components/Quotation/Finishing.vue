<template>
    <div>
        <div v-for="(item, index) in form.finishing.finishings">
            <div class="wrap-group-field"
                 :class="[{ hasValue: item.type },
                          { hasValue: item.shape },
                          { hasValue: (item.presence_consumable && item.consumable.name !== '') },
                          { hasValue: (item.presence_consumable && item.consumable.width !== '') },
                          { hasValue: (item.presence_consumable && item.consumable.price !== '') },
                          { hasFocus: item.hasFocus }]">

                    <span class="btn-right-field" @click="deleteFinishing(index)">
                        <i class="fas fa-times"></i>
                    </span>
                <div class="wrap-field h-50">
                    <select v-model="item.type"
                            @focus="item.hasFocus = true"
                            @blur="item.hasFocus = false"
                            @animationstart="checkAnimation"
                            class="field select"
                            :class="{ hasValue: item.type }"
                            @change="handleFinishingChanging($event, index)"
                            required>
                        <option disabled value="">Choisir</option>
                        <option v-for="finishing in finishings"
                                v-bind:value="finishing.id"
                                :data-name="finishing.name">
                            {{ finishing.name }}
                        </option>
                    </select>
                    <label class="label-field">Type de finition</label>
                </div>

                <div class="wrap-field h-50">
                    <input v-model="item.shape"
                           @focus="item.hasFocus = true"
                           @blur="item.hasFocus = false"
                           class="field"
                           :class="{ hasValue: item.shape }"
                           type="number"
                           step="0.0001"
                           autocomplete="off">
                    <label class="label-field">Prix de l'outil à commander</label>
                </div>

<!--                TODO : mettre la liste des presses-->
<!--                <div class="wrap-field h-50 switcher">-->
<!--                    <input type="radio"-->
<!--                           v-model="item.reworking"-->
<!--                           id="toggle-on-reworking"-->
<!--                           class="field toggle toggle-left hasValue"-->
<!--                           value="true"-->
<!--                           required>-->
<!--                    <label for="toggle-on-reworking" class="toggle-btn"><i class="far fa-check-circle"></i>Oui</label>-->

<!--                    <input type="radio"-->
<!--                           v-model="item.reworking"-->
<!--                           id="toggle-off-reworking"-->
<!--                           class="field toggle toggle-right hasValue"-->
<!--                           value="false">-->
<!--                    <label for="toggle-off-reworking" class="toggle-btn"><i class="far fa-times-circle"></i>Non</label>-->

<!--                    <label class="label-field">Reprise</label>-->
<!--                </div>-->

                <div class="wrap-field h-50" v-if="item.presence_consumable && item.type !== ''">
                    <input v-model.trim="item.consumable.name"
                           @focus="item.hasFocus = true"
                           @blur="item.hasFocus = false"
                           class="field"
                           :class="{ hasValue: item.consumable.name }"
                           type="text"
                           autocomplete="off">
                    <label class="label-field">Désignation du consommable</label>
                </div>

                <div class="wrap-field h-50" v-if="item.presence_consumable && item.type !== ''">
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

                <div class="wrap-field h-50" v-if="item.presence_consumable && item.type !== ''">
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

            }
        },
        created() {
            this.form.finishing.cutting.dimension_width = this.form.description.label.width;
            this.form.finishing.cutting.dimension_length = this.form.description.label.length;
            this.$store.dispatch('getFinishings');
        },
        computed: {
            form() {
                return this.$store.state.workflow;
            },
            finishings() {
                return this.$store.state.finishings;
            },
        },
        methods: {
            checkAnimation({ target, animationName }) {
                if (animationName.startsWith("onAutoFillStart")) {
                    target.classList.add("hasValue");
                }
            },
            addFinishing() {
                this.form.finishing.finishings.push({
                    type: "",
                    id: "",
                    name: "",
                    shape: false,
                    reworking: "",
                    presence_consumable: false,
                    hasFocus: false,
                });
            },
            deleteFinishing(index) {
                this.form.finishing.finishings.splice(index, 1);
            },
            handleFinishingChanging(event, index) {
                if (event.target.options.selectedIndex > 0) {
                    this.form.finishing.finishings[index].name = event.target.options[event.target.options.selectedIndex].dataset.name;
                }
                let finishingID = this.form.finishing.finishings[index].type;
                let finishingConsumable = this.finishings.find(finishing => finishing.id === finishingID);

                if (finishingConsumable.consumable) {
                    let newConsumable = {
                        id: "",
                        name: "",
                        width: "",
                        price: "",
                        hasFocus: false,
                    };
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
