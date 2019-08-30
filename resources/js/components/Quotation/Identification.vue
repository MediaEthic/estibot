<template>
    <div>
        <div class="wrap-radio">
            <div class="wrap-field">
                <input type="radio" id="third_old" v-model="form.third.type" value="old">
                <label for="third_old">
                    <i class="fas fa-user-secret"></i>
                    <span>Rechercher un client</span>
                </label>
            </div>
            <div class="wrap-field">
                <input type="radio" id="third_new" v-model="form.third.type" value="new">
                <label for="third_new">
                    <i class="fas fa-user-plus"></i>
                    <span>Créer un prospect</span>
                </label>
            </div>
        </div>

        <div class="wrap-field h-50">
            <span v-if="form.third.type === 'old'" class="btn-right-field" @click="">
                <i class="fas fa-search"></i>
            </span>
            <input v-model.trim="form.third.name"
                   class="field "
                   :class="{ hasValue: form.third.name }"
                   type="text"
                   required>
            <span class="focus-field"></span>
            <label class="label-field">Nom du prospect</label>
            <span class="symbol-left-field"><i class="fas fa-user-tie"></i></span>
        </div>

        <div class="wrap-group-field"
             :class="[{ hasValue: form.third.address },
                      { hasValue: form.third.zipcode },
                      { hasValue: form.third.city },
                      { hasFocus: form.third.hasFocus }]">
            <div class="wrap-field h-50">
                <input v-if="form.third.type === 'old'"
                       v-model.trim="form.third.address"
                       @focus="form.third.hasFocus = true"
                       @blur="form.third.hasFocus = false"
                       class="field"
                       :class="[{ hasValue: form.third.address }]"
                       type="text"
                       autocomplete="off"
                       required>

                <vue-google-autocomplete v-else
                        ref="form.third.address"
                        id="map"
                        @focus="form.third.hasFocus = true"
                        @blur="form.third.hasFocus = false"
                        :class="[{ hasValue: form.third.address }]"
                        classname="field"
                        required
                >
                </vue-google-autocomplete>

                <label class="label-field">Adresse</label>
            </div>
            <div class="wrap-field-inline">
                <div class="wrap-field h-50">
                    <input v-model.trim="form.third.zipcode"
                           @focus="form.third.hasFocus = true"
                           @blur="form.third.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.third.zipcode }"
                           type="text"
                           autocomplete="off"
                           required>
                    <label class="label-field">Code postal</label>
                </div>
                <div class="wrap-field h-50">
                    <input v-model.trim="form.third.city"
                           @focus="form.third.hasFocus = true"
                           @blur="form.third.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.third.city }"
                           type="text"
                           autocomplete="off"
                           required>
                    <label class="label-field">Ville</label>
                </div>
            </div>
            <span class="focus-field"></span>
            <span class="symbol-left-field"><i class="fas fa-map-marker-alt"></i></span>
        </div>

        <div v-if="form.third.type === 'old'"
             class="wrap-group-field"
             :class="[{ hasValue: form.contact.civility },
                      { hasValue: form.contact.name },
                      { hasValue: form.contact.surname },
                      { hasValue: form.contact.email },
                      { hasFocus: form.contact.hasFocus }]">

            <div class="wrap-field h-50">
                <select v-model="form.contact.id"
                        @focus="form.contact.hasFocus = true"
                        @blur="form.contact.hasFocus = false"
                        @animationstart="checkAnimation"
                        class="field select"
                        :class="{ hasValue: form.contact.id }"
                        required>
                    <option disabled value="">Choisir</option>
                </select>
                <label class="label-field">Contact</label>
            </div>
            <div class="wrap-field h-50">
                <input v-model.trim="form.contact.email"
                       @focus="form.contact.hasFocus = true"
                       @blur="form.contact.hasFocus = false"
                       class="field"
                       :class="[{ hasValue: form.contact.email }]"
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
             :class="[{ hasValue: form.contact.civility },
                      { hasValue: form.contact.name },
                      { hasValue: form.contact.surname },
                      { hasValue: form.contact.email },
                      { hasFocus: form.contact.hasFocus }]">
            <div class="wrap-field-inline">
                <div class="wrap-field h-50" style="width: 26rem;">
                    <select v-model="form.contact.civility"
                            @focus="form.contact.hasFocus = true"
                            @blur="form.contact.hasFocus = false"
                            @animationstart="checkAnimation"
                            class="field select"
                            :class="{ hasValue: form.contact.civility }"
                            required>
                        <option disabled value="">Choisir</option>
                        <option value="Mr">M.</option>
                        <option value="Mrs">Mme</option>
                    </select>
                    <label class="label-field">Civilité</label>
                </div>
                <div class="wrap-field h-50">
                    <input v-model.trim="form.contact.surname"
                           @focus="form.contact.hasFocus = true"
                           @blur="form.contact.hasFocus = false"
                           class="field"
                           :class="{ hasValue: form.contact.surname }"
                           type="text"
                           autocomplete="off">
                    <label class="label-field">Nom de famille</label>
                </div>
            </div>
            <div class="wrap-field h-50">
                <input v-model.trim="form.contact.name"
                       @focus="form.contact.hasFocus = true"
                       @blur="form.contact.hasFocus = false"
                       class="field"
                       :class="{ hasValue: form.contact.name }"
                       type="text"
                       autocomplete="off">
                <label class="label-field">Prénom du contact</label>
            </div>
            <div class="wrap-field h-50">
                <input v-model.trim="form.contact.email"
                       @focus="form.contact.hasFocus = true"
                       @blur="form.contact.hasFocus = false"
                       class="field"
                       :class="[{ hasValue: form.contact.email }]"
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
                form: {
                    third: {
                        type: "old",
                        id: "",
                        name: "",
                        address: "",
                        zipcode: "",
                        city: "",
                        hasFocus: false,
                    },
                    contact: {
                        id: "",
                        civility: "",
                        name: "",
                        surname: "",
                        email: "",
                        hasFocus: false,
                    }
                }
            }
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