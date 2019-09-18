<template>
    <div>
        <ul v-if="errors.length > 0" class="wrap-list-errors">
            <li class="item-list" v-for="error in errors">
                {{ error }}
            </li>
        </ul>

        <div v-else-if="quantity">
            <transition name="fade"
                        mode="out-in">
                <Quantity :quantities="result.quantities"
                          :copies="copies"
                          v-on:goBack="hideQuantityDetail" />
            </transition>
        </div>

        <div v-else class="wrap-success">
<!--            <Quantity v-if="objectSize(result.quantities) === 1"-->
<!--                      :quantities="result.quantities"-->
<!--                      :copies="copies" />-->

            <transition name="fade"
                        mode="out-in">
            <div class="list-results">
                <div v-for="(quantity, index) in result.quantities"
                    @click="displayQuantityDetail(index)"
                    class="item-list">
                    <div class="left-part">
                        <p class="page-subtitle">{{ index }} exemplaires</p>
                        <p>{{ quantity.datas.models }} modèle(s) - {{ quantity.datas.plates }} cliché(s)</p>
                        <p class="price-quotation">{{ quantity.totals.totalCosts }}<span class="symbol-price">€</span> HT</p>
                        <ul class="detail-quantity">
                            <li class="item-detail"><i class="far fa-clock"></i>{{ quantity.totals.totalTimes }}h</li>
                            <li class="item-detail"><i class="fas fa-weight-hanging"></i>{{ quantity.totals.weight }}kg</li>
                            <li class="item-detail"><i class="fas fa-layer-group"></i>{{ (quantity.totals.totalFixedCosts).toFixed(2) }}€</li>
                            <li class="item-detail"><i class="fas fa-percentage"></i>{{ (quantity.totals.totalVariableCosts).toFixed(2) }}€</li>
                            <li class="item-detail"><i class="fas fa-tag"></i>{{ (quantity.totals.totalCosts / index).toFixed(2) }}€</li>
    <!--                        <li class="item-detail"><i class="fas fa-weight-hanging"></i>{{ (quantity.totals.totalVariableCosts / copies) * 1000 }}</li>-->
                            <li class="item-detail"><i class="fas fa-tags"></i>{{ (quantity.totals.totalCosts / 1000).toFixed(2) }}€</li>
                        </ul>
                    </div>
                    <button class="detail-result"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
            </transition>
        </div>
    </div>
</template>

<script>
    import Quantity from './Quantity';

    export default {
        components: {
            Quantity
        },
        data() {
            return {
                errors: [],
                quantity: false,
                copies: "",
            }
        },
        created() {
            this.$store.dispatch("getQuotationPrice").then(res => {
                this.result = this.$store.state.price;

                if (this.result.errors !== undefined) {
                    document.getElementById('save-quotation').disabled = true;
                    this.errors = this.result.errors;
                } else {
                    this.errors = [];
                    document.getElementById('save-quotation').disabled = false;
                }
            });
        },
        computed: {
            result: {
                get() {
                    return this.$store.state.price;
                },
                set() {
                    return this.$store.state.price;
                }
            },
        },
        methods: {
            objectSize(obj) {
                var size = 0, key;
                for (key in obj) {
                    if (obj.hasOwnProperty(key)) size++;
                }
                return size;
            },
            displayQuantityDetail(index) {
                this.quantity = true;
                this.copies = Number(index);
            },
            hideQuantityDetail (value) {
                this.quantity = false;
                this.copies = "";
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';


    .wrap-list-errors {
        text-align: left;

        .item-list {
            font-size: 1.3rem;
            line-height: 1.7rem;
            padding: .75rem 1.5rem;

            &:not(:last-child) {
                border-bottom: .05rem solid $primary-color-dark;
            }
        }
    }

    .wrap-success {
        .list-results {
            text-align: left;

            .item-list {
                display: flex;
                cursor: pointer;
                padding: 1.5rem 2rem;
                box-shadow: 0 0 .5rem rgba($primary-color-dark, 0.2);
                border-radius: 2rem 1rem 3rem 1rem;
                margin: 1rem 0;
                transition: all .4s;

                &:hover {
                    background-position-x: 7rem;
                    border-left: 1.5rem solid $primary-color;
                    box-shadow: 0 0 1rem rgba($primary-color-dark, 0.4);
                    transform: scale(1.1);
                }

                .detail-result {
                    border: 0;
                    background: transparent;
                    font-size: 2.5rem;
                    color: $secondary-color;
                }

                .detail-quantity {
                    display: flex;
                    flex-flow: row wrap;
                    margin-top: .5rem;

                    .item-detail {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        margin: .5rem;
                        font-size: 1.3rem;
                        line-height: 1.7rem;

                        [class^="fa"] {
                            font-size: 1.5rem;
                            color: $primary-color-light;
                            margin-right: .5rem;
                        }
                    }
                }
            }
        }
    }
</style>
