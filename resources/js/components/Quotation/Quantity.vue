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
            <table>
                <caption>{{ copies }} exemplaires</caption>
                <thead>
                    <tr>
                        <th scope="col">Opération</th>
                        <th scope="col">Temps (h)</th>
                        <th scope="col">Marge (%)</th>
                        <th scope="col">Prix (€)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(operation, index) in quantities[copies].operations">
                        <td data-label="Opération">{{ operation.name }}</td>
                        <td data-label="Temps (h)">{{ operation.time }}</td>
                        <td data-label="Marge (%)">{{ operation.margin }}</td>
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

    table {
        border-collapse: collapse;
        margin: 0;
        padding: 0;
        width: 100%;
        table-layout: fixed;
        box-shadow: 0 0 .5rem rgba($grey-dark, 0.1);

        tr {
            padding: .35em;

            &:not(:last-child) {
                border-bottom: .05rem solid $primary-color-dark;
            }
        }

        th,
        td {
            padding: .625em;
            text-align: center;
        }

        th {
            font-size: .85em;
            letter-spacing: .1em;
            text-transform: uppercase;
        }
    }

    @media screen and (max-width: 600px) {
        table {
            border: 0;

            caption {
                color: $primary-color-dark;
                font-size: 1.8rem;
                line-height: 2.2rem;
                margin-bottom: 1rem;
            }

            thead {
                border: none;
                clip: rect(0 0 0 0);
                height: .1rem;
                margin: -.1rem;
                overflow: hidden;
                padding: 0;
                position: absolute;
                width: .1rem;
            }

            tr {
                display: block;

                &:first-child {
                    border-radius: 1rem 1rem 0 0;
                }

                &:last-child {
                    border-radius: 0 0 1rem 1rem;
                }
            }

            td {
                display: block;
                font-size: 1.4rem;
                line-height: 1.8rem;
                text-align: right;
                color: $primary-color-dark;

                &:before {
                    content: attr(data-label);
                    float: left;
                    font-weight: $bold;
                    text-transform: uppercase;
                    color: $primary-color-light;
                }

                &:last-child {
                    border-bottom: 0;
                }
            }

        }
    }
</style>
