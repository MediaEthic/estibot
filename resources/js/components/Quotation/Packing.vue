<template>
    <div>
        <div class="wrap-field h-50 title-radio">
            <label class="label-field">Sens d'enroulement</label>
            <span class="symbol-left-field"><i class="fas fa-text-width"></i></span>
        </div>
        <h3 class="legend-radio">Enroulement extérieur</h3>
        <div class="wrap-radio">
            <div class="wrap-field">
                <input type="radio" id="ehead" v-model="form.packing.direction" value="ehead">
                <label for="ehead">
                    <i class="fa icon-exterior-top"></i>
                    <span>Tête en avant</span>
                </label>
            </div>
            <div class="wrap-field">
                <input type="radio" id="efoot" v-model="form.packing.direction" value="efoot">
                <label for="efoot">
                    <i class="fa icon-exterior-bottom"></i>
                    <span>Pied en avant</span>
                </label>
            </div>
        </div>

        <div class="wrap-radio">
            <div class="wrap-field">
                <input type="radio" id="eright" v-model="form.packing.direction" value="eright">
                <label for="eright">
                    <i class="fa icon-exterior-right"></i>
                    <span>Droite en avant</span>
                </label>
            </div>
            <div class="wrap-field">
                <input type="radio" id="eleft" v-model="form.packing.direction" value="eleft">
                <label for="eleft">
                    <i class="fa icon-exterior-left"></i>
                    <span>Gauche en avant</span>
                </label>
            </div>
        </div>

        <h3 class="legend-radio">Enroulement intérieur</h3>
        <div class="wrap-radio">
            <div class="wrap-field">
                <input type="radio" id="ihead" v-model="form.packing.direction" value="ihead">
                <label for="ihead">
                    <i class="fa icon-interior-top"></i>
                    <span>Tête en avant</span>
                </label>
            </div>
            <div class="wrap-field">
                <input type="radio" id="ifoot" v-model="form.packing.direction" value="ifoot">
                <label for="ifoot">
                    <i class="fa icon-interior-bottom"></i>
                    <span>Pied en avant</span>
                </label>
            </div>
        </div>

        <div class="wrap-radio">
            <div class="wrap-field">
                <input type="radio" id="iright" v-model="form.packing.direction" value="iright">
                <label for="iright">
                    <i class="fa icon-interior-right"></i>
                    <span>Droite en avant</span>
                </label>
            </div>
            <div class="wrap-field">
                <input type="radio" id="ileft" v-model="form.packing.direction" value="ileft">
                <label for="ileft">
                    <i class="fa icon-interior-left"></i>
                    <span>Gauche en avant</span>
                </label>
            </div>
        </div>

        <div class="wrap-field h-50">
            <input v-model="form.packing.packing"
                   class="field"
                   :class="{ hasValue: form.packing.packing }"
                   type="number"
                   required>
            <span class="focus-field"></span>
            <label class="label-field">Confitionnement</label>
            <span class="symbol-left-field"><i class="fas fa-tape"></i></span>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    packing: {
                        packing: "",
                        direction: "ehead",
                    }
                }
            }
        },
        created() {

        },
        computed: {

        },
        methods: {

        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';

    .title-radio {
        border: none;
        margin-bottom: 0;
    }

    .legend-radio {
        margin-bottom: 1rem;
        text-align: center;
        font-size: 1.4rem;
        line-height: 1.8rem;
    }
</style>