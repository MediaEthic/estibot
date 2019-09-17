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
                    <option v-for="printing in printings"
                            v-bind:value="printing.id"
                            :data-name="printing.name">
                        {{ printing.name }}
                    </option>
                </select>
                <label class="label-field">Machine d'impression</label>
            </div>
            <div class="wrap-field h-50">
                <input v-model="form.printing.colors"
                       @focus="form.printing.hasFocus = true"
                       @blur="form.printing.hasFocus = false"
                       class="field"
                       :class="{ hasValue: form.printing.colors }"
                       type="number"
                       autocomplete="off"
                       required>
                <label class="label-field">Nombre de couleurs</label>
            </div>

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
                <input type="radio" id="substrate_old" v-model="form.printing.substrate.type" value="old">
                <label for="substrate_old">
                    <i class="fas fa-search"></i>
                    <span>Rechercher un support</span>
                </label>
            </div>
            <div class="wrap-field">
                <input type="radio" id="substrate_new" v-model="form.printing.substrate.type" value="new">
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
            <div class="wrap-field h-50">
                <button type="button"
                        v-if="form.printing.substrate.type === 'old'"
                        class="btn-right-field"
                        id="show-modal">
                    <i class="fas fa-search"></i>
                </button>
                <input v-model.trim="form.printing.substrate.name"
                       @focus="form.printing.substrate.hasFocus = true"
                       @blur="form.printing.substrate.hasFocus = false"
                       class="field"
                       :class="{ hasValue: form.printing.substrate.name }"
                       type="text"
                       autocomplete="off"
                       required>
                <label class="label-field">Désignation du support</label>
            </div>
            <div class="wrap-field h-50">
                <input v-model="form.printing.substrate.width"
                       @focus="form.printing.substrate.hasFocus = true"
                       @blur="form.printing.substrate.hasFocus = false"
                       class="field"
                       :class="{ hasValue: form.printing.substrate.width }"
                       type="number"
                       autocomplete="off"
                       required>
                <label class="label-field">Laize (mm)</label>
            </div>
            <div class="wrap-field h-50">
                <input v-model="form.printing.substrate.weight"
                       @focus="form.printing.substrate.hasFocus = true"
                       @blur="form.printing.substrate.hasFocus = false"
                       class="field"
                       :class="{ hasValue: form.printing.substrate.weight }"
                       type="number"
                       autocomplete="off"
                       required>
                <label class="label-field">Grammage (g/m&#xB2;)</label>
            </div>
            <div class="wrap-field h-50">
                <input v-model="form.printing.substrate.price"
                       @focus="form.printing.substrate.hasFocus = true"
                       @blur="form.printing.substrate.hasFocus = false"
                       class="field"
                       :class="{ hasValue: form.printing.substrate.price }"
                       type="number"
                       step="0.0001"
                       autocomplete="off"
                       required>
                <label class="label-field">Prix (€/m&#xB2;)</label>
            </div>
            <span class="focus-field"></span>
            <span class="symbol-left-field"><i class="fas fa-scroll"></i></span>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                showModal: false,
            }
        },
        created() {
            this.$store.dispatch('getPrintings');
            this.$store.dispatch('getSubstrates');
        },
        computed: {
            form() {
                return this.$store.state.workflow;
            },
            printings() {
                return this.$store.state.printings;
            },
            substrates() {
                return this.$store.state.substrates;
            }
        },
        methods: {
            checkAnimation({ target, animationName }) {
                if(animationName.startsWith("onAutoFillStart")) {
                    target.classList.add("hasValue");
                }
            },
            handlePressChange(event) {
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
