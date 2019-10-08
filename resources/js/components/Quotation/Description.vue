<template>
    <div>
        <div class="wrap-radio">
            <div class="wrap-field">
                <input type="radio" id="label_old" v-model="form.description.label.type" value="old">
                <label for="label_old">
                    <i class="fas fa-search"></i>
                    <span>Rechercher une étiquette</span>
                </label>
            </div>
            <div class="wrap-field">
                <input type="radio" id="label_new" v-model="form.description.label.type" value="new">
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
            <div class="wrap-field h-50">
                <span v-if="form.description.label.type === 'old'" class="btn-right-field" @click="">
                    <i class="fas fa-search"></i>
                </span>
                <input v-model.trim="form.description.label.name"
                       @focus="form.description.label.hasFocus = true"
                       @blur="form.description.label.hasFocus = false"
                       class="field"
                       :class="{ hasValue: form.description.label.name }"
                       type="text"
                       autocomplete="off">
                <label class="label-field">Désignation de l'étiquette</label>
            </div>
            <div class="wrap-field-inline">
                <div class="wrap-field h-50">
                    <input v-model="form.description.label.width"
                           @focus="form.description.label.hasFocus = true"
                           @blur="form.description.label.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.description.label.width }"
                           type="number"
                           autocomplete="off"
                           required>
                    <label class="label-field">Laize (mm)</label>
                </div>
                <div class="wrap-field h-50">
                    <input v-model="form.description.label.length"
                           @focus="form.description.label.hasFocus = true"
                           @blur="form.description.label.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.description.label.length }"
                           type="number"
                           autocomplete="off"
                           required>
                    <label class="label-field">Avance (mm)</label>
                </div>
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
            <div class="wrap-field h-50">
                <input v-model="item.quantity"
                       @focus="item.hasFocus = true"
                       @blur="item.hasFocus = false"
                       class="field"
                       :class="{ hasValue: item.quantity }"
                       type="number"
                       autocomplete="off"
                       required>
                <label class="label-field">Nombre d'exemplaires</label>
            </div>
            <div class="wrap-field h-50">
                <input v-model="item.model"
                       @focus="item.hasFocus = true"
                       @blur="item.hasFocus = false"
                       class="field"
                       :class="{ hasValue: item.model }"
                       type="number"
                       autocomplete="off"
                       required>
                <label class="label-field">Nombre de modèles</label>
            </div>
            <div class="wrap-field h-50">
                <input v-model="item.plate"
                       @focus="item.hasFocus = true"
                       @blur="item.hasFocus = false"
                       class="field"
                       :class="{ hasValue: item.plate }"
                       type="number"
                       autocomplete="off"
                       required>
                <label class="label-field">Nombre de clichés</label>
            </div>
            <div class="wrap-field-inline">
                <span class="legend-line">Temps prépresse</span>
                <div class="wrap-field h-50">
                    <input v-model="item.hour"
                           @focus="item.hasFocus = true"
                           @blur="item.hasFocus = false"
                           class="field"
                           :class="{ hasValue: item.hour }"
                           type="number"
                           autocomplete="off"
                           required>
                    <label class="label-field">Heure(s)</label>
                </div>
                <div class="wrap-field h-50">
                    <input v-model="item.minute"
                           @focus="item.hasFocus = true"
                           @blur="item.hasFocus = false"
                           class="field"
                           :class="{ hasValue: item.minute }"
                           type="number"
                           autocomplete="off"
                           required>
                    <label class="label-field">Minute(s)</label>
                </div>
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
    </div>
</template>

<script>
    export default {
        data() {
            return {

            }
        },
        created() {

        },
        computed: {
            form() {
                return this.$store.state.workflow;
            },
        },
        methods: {
            checkAnimation({ target, animationName }) {
                if(animationName.startsWith("onAutoFillStart")) {
                    target.classList.add("hasValue");
                }
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
