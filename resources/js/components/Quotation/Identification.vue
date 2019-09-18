<template>
    <div>
        <div class="wrap-radio">
<!--            <div class="wrap-field">-->
<!--                <input type="radio" id="third_old" v-model="form.identification.third.type" value="old">-->
<!--                <label for="third_old">-->
<!--                    <i class="fas fa-user-secret"></i>-->
<!--                    <span>Rechercher un client</span>-->
<!--                </label>-->
<!--            </div>-->
            <div class="wrap-field">
                <input type="radio" id="third_new" v-model="form.identification.third.type" value="new">
                <label for="third_new">
                    <i class="fas fa-user-plus"></i>
                    <span>Créer un prospect</span>
                </label>
            </div>
        </div>

        <div class="wrap-field h-50">
            <span v-if="form.identification.third.type === 'old'" class="btn-right-field" @click="">
                <i class="fas fa-search"></i>
            </span>
            <input v-model.trim="form.identification.third.name"
                   class="field "
                   :class="{ hasValue: form.identification.third.name }"
                   type="text"
                   required>
            <span class="focus-field"></span>
            <label v-if="form.identification.third.type === 'old'" class="label-field">Nom du client</label>
            <label v-else class="label-field">Nom du prospect</label>
            <span class="symbol-left-field"><i class="fas fa-user-tie"></i></span>
        </div>

        <div class="wrap-group-field"
             :class="[{ hasValue: form.identification.third.address },
                      { hasValue: form.identification.third.zipcode },
                      { hasValue: form.identification.third.city },
                      { hasFocus: form.identification.third.hasFocus }]">
            <div class="wrap-field h-50">
                <input v-if="form.identification.third.type === 'old'"
                       v-model.trim="form.identification.third.address"
                       @focus="form.identification.third.hasFocus = true"
                       @blur="form.identification.third.hasFocus = false"
                       class="field"
                       :class="[{ hasValue: form.identification.third.address }]"
                       type="text"
                       autocomplete="off"
                       required>

                <input v-else
                       v-model.trim="form.identification.third.address"
                       @focus="form.identification.third.hasFocus = true"
                       @blur="form.identification.third.hasFocus = false"
                       class="field"
                       :class="{ hasValue: form.identification.third.address }"
                       type="text"
                       autocomplete="off"
                       required>

                <label class="label-field">Adresse</label>
            </div>
            <div class="wrap-field-inline">
                <div class="wrap-field h-50">
                    <input v-model.trim="form.identification.third.zipcode"
                           @focus="form.identification.third.hasFocus = true"
                           @blur="form.identification.third.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.identification.third.zipcode }"
                           type="text"
                           autocomplete="off"
                           required>
                    <label class="label-field">Code postal</label>
                </div>
                <div class="wrap-field h-50">
                    <input v-model.trim="form.identification.third.city"
                           @focus="form.identification.third.hasFocus = true"
                           @blur="form.identification.third.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.identification.third.city }"
                           type="text"
                           autocomplete="off">
                    <label class="label-field">Ville</label>
                </div>
            </div>
            <span class="focus-field"></span>
            <span class="symbol-left-field"><i class="fas fa-map-marker-alt"></i></span>
        </div>

        <div v-if="form.identification.third.type === 'old'"
             class="wrap-group-field"
             :class="[{ hasValue: form.identification.contact.civility },
                      { hasValue: form.identification.contact.name },
                      { hasValue: form.identification.contact.surname },
                      { hasValue: form.identification.contact.email },
                      { hasFocus: form.identification.contact.hasFocus }]">

            <div class="wrap-field h-50">
                <select v-model="form.identification.contact.id"
                        @focus="form.identification.contact.hasFocus = true"
                        @blur="form.identification.contact.hasFocus = false"
                        @animationstart="checkAnimation"
                        class="field select"
                        :class="{ hasValue: form.identification.contact.id }"
                        required>
                    <option disabled value="">Choisir</option>
                </select>
                <label class="label-field">Contact</label>
            </div>
            <div class="wrap-field h-50">
                <input v-model.trim="form.identification.contact.email"
                       @focus="form.identification.contact.hasFocus = true"
                       @blur="form.identification.contact.hasFocus = false"
                       class="field"
                       :class="[{ hasValue: form.identification.contact.email }]"
                       type="text"
                       autocomplete="off"
                       required>
                <label class="label-field">E-mail</label>
            </div>
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
                <div class="wrap-field h-50" style="width: 26rem;">
                    <select v-model="form.identification.contact.civility"
                            @focus="form.identification.contact.hasFocus = true"
                            @blur="form.identification.contact.hasFocus = false"
                            @animationstart="checkAnimation"
                            class="field select"
                            :class="{ hasValue: form.identification.contact.civility }">
                        <option disabled value="">Choisir</option>
                        <option value="Mr">M.</option>
                        <option value="Mrs">Mme</option>
                    </select>
                    <label class="label-field">Civilité</label>
                </div>
                <div class="wrap-field h-50">
                    <input v-model.trim="form.identification.contact.surname"
                           @focus="form.identification.contact.hasFocus = true"
                           @blur="form.identification.contact.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.identification.contact.surname }"
                           type="text"
                           autocomplete="off">
                    <label class="label-field">Nom de famille</label>
                </div>
            </div>
            <div class="wrap-field h-50">
                <input v-model.trim="form.identification.contact.name"
                       @focus="form.identification.contact.hasFocus = true"
                       @blur="form.identification.contact.hasFocus = false"
                       class="field"
                       :class="{ hasValue: form.identification.contact.name }"
                       type="text"
                       autocomplete="off">
                <label class="label-field">Prénom du contact</label>
            </div>
            <div class="wrap-field h-50">
                <input v-model.trim="form.identification.contact.email"
                       @focus="form.identification.contact.hasFocus = true"
                       @blur="form.identification.contact.hasFocus = false"
                       class="field"
                       :class="[{ hasValue: form.identification.contact.email }]"
                       type="text"
                       autocomplete="off"
                       required>
                <label class="label-field">E-mail</label>
            </div>
            <span class="focus-field"></span>
            <span class="symbol-left-field"><i class="fas fa-id-card-alt"></i></span>
        </div>
    </div>
</template>

<script>
    import VueGoogleAutocomplete from 'vue-google-autocomplete'

    export default {
        components: {
            VueGoogleAutocomplete
        },
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
        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';
</style>
