<template>
    <div>
<!--        <div class="wrap-head-operation">-->
<!--            <h2 class="page-subtitle">{{ copies }} exemplaires</h2>-->
<!--        </div>-->
<!--        <div class="wrap-operations">-->
<!--            <ul class="detail-quantity">-->
<!--                <li><i class="far fa-clock"></i>{{ quantities[copies].totals.totalTimes }}</li>-->
<!--                <li><i class="fas fa-tag"></i>{{ quantities[copies].totals.totalCosts }}</li>-->
<!--                <li><i class="fas fa-weight-hanging"></i>{{ quantities[copies].totals.weight }}</li>-->
<!--            </ul>-->
<!--            <ul class="detail-price">-->
<!--                <li><i class="far fa-clock"></i>{{ quantities[copies].totals.totalFixedCosts }}</li>-->
<!--                <li><i class="fas fa-tag"></i>{{ quantities[copies].totals.totalVariableCosts }}</li>-->
<!--                <li><i class="fas fa-weight-hanging"></i>{{ quantities[copies].totals.totalCosts / 1000 }}</li>-->
<!--                <li><i class="fas fa-weight-hanging"></i>{{ (quantities[copies].totals.totalVariableCosts / copies) * 1000 }}</li>-->
<!--            </ul>-->
            <button type="button" v-on:click="$emit('goBack')" class="arrow-return"><i class="fas fa-arrow-left"></i>Retour</button>
            <table class="table-responsive">
                <caption>{{ copies }} exemplaires</caption>
                <thead>
                    <tr>
                        <th scope="col">Opération</th>
                        <th scope="col">Temps (h)</th>
                        <th scope="col">Coût fixe (€)</th>
                        <th scope="col">Coût variable (€)</th>
                        <th scope="col">Prix (€)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(operation, index) in quantities[copies].operations">
                        <td data-label="Opération">{{ operation.name }}</td>
                        <td data-label="Temps (h)">{{ operation.time }}</td>
                        <td data-label="Part fixe (€)">{{ operation.fixed }}</td>
                        <td data-label="Part variable (€)">{{ operation.variable }}</td>
                        <td data-label="Prix (€)">{{ operation.price }}</td>
                    </tr>
                </tbody>
            </table>
    </div>
</template>

<script>
    export default {
        props: {
            quantities: Object,
            copies: Number,
        },
        data() {
            return {
                operation: false,
                index: "",
            }
        },
        created() {

        },
        computed: {

        },
        methods: {
            displayOperationDetail(index) {
                this.operation = true;
                this.index = index;
            },
        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';

    .arrow-return {
        cursor: pointer;
        display: block;
        margin-right: auto;
        font-size: 1.2rem;
        line-height: 1.4rem;
        font-weight: $bold;
        color: $secondary-color;
        text-transform: uppercase;

        [class^="fa"] {
            font-size: 1.5rem;
            margin-right: .5rem;
        }
    }
</style>
