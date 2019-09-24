<template>
    <div>
        <main class="wrap-main-content">
            <div class="wrap-head-page">
                <header class="wrap-main-header"
                        :style="{ backgroundImage: 'url(/assets/img/quotations/' + image + ')' }">
                    <router-link class="go-back"
                                 tag="a"
                                 :to="{ name : 'home' }">
                        <i class="fas fa-arrow-left"></i>
                        Retour
                    </router-link>
                    <h1 class="page-main-title">Devis #{{ quotation.id }}</h1>
                    <div class="wrap-actions-quotation">
                        <input id="options-toggler" class="options-toggler" type="checkbox">
                        <label for="options-toggler" class="fas fa-cog"></label>
                        <ul class="list-actions">
                            <li class="action-item"><i class="fas fa-print action-event"></i><span>Imprimer</span></li>
                            <li class="action-item"><i class="fas fa-edit action-event"></i><span>Modifiter</span></li>
                            <li class="action-item"><i class="fas fa-copy action-event"></i><span>Dupliquer</span></li>
                            <li class="action-item" @click="destroyQuotation(quotation.id)"><i class="fas fa-trash-alt action-event"></i><span>Supprimer</span></li>
                        </ul>
                    </div>
                    <ul class="list-details-quotation">
                        <li class="item-detail-quotation">
                            <i class="fas fa-user-tie"></i>
                            <p>{{ this.third }}</p>
                        </li>
                        <li class="item-detail-quotation">
                            <i class="far fa-calendar-plus"></i>
                            <time :datetime="quotation.created_at">{{ getHumanDate(quotation.created_at) }}</time>
                        </li>
                        <li class="item-detail-quotation">
                            <i class="fas fa-hourglass-half"></i>
                            <time :datetime="quotation.validity">{{ getHumanDate(quotation.validity) }}</time>
                        </li>
                    </ul>
                </header>
            </div>

            <div class="wrap-central">
                <div class="left-part">
                    <table class="responsive-table">
                        <thead>
                            <tr>
                                <th scope="col">Quantité</th>
                                <th scope="col">Marge (%)</th>
                                <th scope="col">Prix du mille (€)</th>
                                <th scope="col">Frais d'expédition (€)</th>
                                <th scope="col">Prix HT (€)</th>
                                <th scope="col">TVA (%)</th>
                                <th scope="col">Prix TTC (€)</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr v-for="quantity in quotation.quantities">
                            <td data-label="Quantité">{{ quantity.quantity }}</td>
                            <td data-label="Marge (%)">{{ quantity.margin }}</td>
                            <td data-label="Prix du mille (€)">{{ quantity.thousand }}</td>
                            <td data-label="Frais d'expédition (€)">{{ quantity.shipping }}</td>
                            <td data-label="Prix HT (€)">{{ quantity.cost }}</td>
                            <td data-label="TVA (%)">20</td>
                            <td data-label="Prix TTC (€)">{{ quantity.price }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <aside class="wrap-summary right-part" :class="{ pull: summaryPulled }">
                    <div class="head-summary">
                        <input v-model="summaryPulled"
                               id="pull-summary"
                               class="pull-summary"
                               type="checkbox">
                        <label for="pull-summary" class="page-subtitle"><i :class="this.summaryPulled ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>Récapitulatif</label>
                    </div>
                    <div class="wrap-content-summary">
                <textarea v-model="summary" @keydown="textareaAutosize">
                </textarea>

                        <table class="table">
                            <tr class="border">
                                <td>Sous-total</td>
                                <td class="price">{{ quotation.cost }}€</td>
                            </tr>
                            <tr>
                                <td>Frais d'expédition</td>
                                <td class="price">{{ quotation.shipping }}€</td>
                            </tr>
                            <tr class="border">
                                <td>Total HT</td>
                                <td class="price">{{ (quotation.cost + quotation.shipping).toFixed(2) }}€</td>
                            </tr>
                            <tr>
                                <td>TVA</td>
                                <td class="price">{{ ((quotation.cost + quotation.shipping) * quotation.vat / 100).toFixed(2)  }}€</td>
                            </tr>
                            <tr>
                                <td>Total TTC</td>
                                <td class="price">{{ ((quotation.cost + quotation.shipping) + ((quotation.cost + quotation.shipping) * quotation.vat / 100)).toFixed(2) }}€</td>
                            </tr>
                        </table>

                        <div class="wrap-button-submit">
                            <button type="submit" class="cta" id="save-quotation" disabled>
                                <span>Enregistrer</span>
                                <svg width="13px" height="10px" viewBox="0 0 13 10">
                                    <path d="M1,5 L11,5"></path>
                                    <polyline points="8 1 12 5 8 9"></polyline>
                                </svg>
                            </button>
                        </div>
                    </div>
                </aside>
            </div>
        </main>
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        data() {
            return {
                third: "",
                image: "",
                summary: "",
                summaryPulled: false,
            }
        },
        created() {
            this.$store.dispatch('getQuotation', {
                id: this.$route.params.id
            }).then(() => {
                this.summary = this.quotation.description;
                let el = document.querySelector('textarea');
                setTimeout(function(){
                    el.style.cssText = 'height:auto; padding:0';
                    el.style.cssText = 'height:' + el.scrollHeight + 'px';
                },0);
                this.image = this.quotation.image;
                this.generateThird();
            })
        },
        computed: {
            quotation() {
                console.log(this.$store.state.quotation);
                return this.$store.state.quotation;
            }
        },
        methods: {
            goBack() {
                $router.go(-1);
            },
            getHumanDate(date) {
                return moment(date, 'YYYY-MM-DD').format('DD/MM/YYYY');
            },
            generateThird() {
                let third = ``;
                if (this.quotation.third.name !== null) third += this.quotation.third.name;
                if (this.quotation.third.address !== null) third += ` - ` + this.quotation.third.address;
                if (this.quotation.third.zipcode !== null) third += ` - ` + this.quotation.third.zipcode;
                if (this.quotation.third.city !== null) third += ` ` + this.quotation.third.city;
                this.third = third;
            },
            textareaAutosize() {
                let el = document.querySelector('textarea');
                setTimeout(function(){
                    el.style.cssText = 'height:auto; padding:0';
                    el.style.cssText = 'height:' + el.scrollHeight + 'px';
                },0);
            },
            destroyQuotation(id) {
                this.$store.dispatch("destroyQuotation", {
                    id: id
                }).then(() => {
                    this.$router.push({name: "home"});
                }).catch(err => console.log(err));
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';

    .wrap-main-content {
        margin-bottom: 13rem !important;
    }

    .wrap-head-page {
        margin-bottom: 3rem;

        .wrap-main-header {
            position: relative;
            background-position: bottom right;
            background-size: auto 15rem;
            background-repeat: no-repeat;

            .page-main-title {
                width: 100%;
            }

            .wrap-actions-quotation {
                position: absolute;
                top: 2rem;
                right: 0;

                .options-toggler {
                    position: absolute;
                    left: -9999px;

                    & + label {
                        font-size: 2rem;
                        color: $secondary-color;
                        background-color: #fff;
                        border: .15rem solid $secondary-color;
                        border-radius: 50%;
                        position: relative;
                        z-index: 1;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        width: 3.5rem;
                        height: 3.5rem;
                        transition: 0.4s;
                    }

                    & ~ .list-actions {
                        position: relative;

                        .action-item {
                            position: absolute;
                            display: block;
                            top: 0;
                            bottom: 0;
                            left: 0;
                            right: 0;
                            margin: auto;
                            width: 3.5rem;
                            height: 3.5rem;
                            opacity: 0;
                            transition: 0.5s;

                            > .action-event {
                                display: block;
                                width: inherit;
                                height: inherit;
                                line-height: 3.25rem;
                                color: $secondary-color;
                                background: #fff;
                                border: .15rem solid $secondary-color;
                                border-radius: 50%;
                                text-align: center;
                                text-decoration: none;
                                font-size: 2rem;
                                pointer-events: none;
                                transition: 0.2s;

                                span {
                                    display: none;
                                }

                                &:hover {
                                    color: $secondary-color-light;
                                    border-color: $secondary-color-light;
                                    transform: scale(1.1);
                                }
                            }
                        }
                    }

                    &:checked {
                        & + label {
                            color: $white;
                            background-color: $secondary-color;
                            border: .15rem solid $secondary-color;
                            transform: rotate(180deg);
                        }

                        ~ .list-actions {
                            .action-item {
                                opacity: 1;

                                &:nth-child(1) {
                                    transform: translateY(2.5rem);
                                }

                                &:nth-child(2) {
                                    transform: translateY(6.5rem);
                                }

                                &:nth-child(3) {
                                    transform: translateY(10.5rem);
                                }

                                &:nth-child(4) {
                                    transform: translateY(14.5rem);
                                }

                                > .action-event {
                                    pointer-events: auto;
                                }
                            }
                        }
                    }
                }
            }

            .list-details-quotation {
                margin-top: 3rem;

                .item-detail-quotation {
                    display: flex;
                    margin: 1rem 0;

                    [class^="fa"] {
                        color: $primary-color;
                        margin-right: 1.5rem;
                    }
                }
            }
        }
    }

    .wrap-central {
        .left-part {
            overflow-x: scroll;
        }
    }

    .table {
        width: 100%;
        font-size: 1.4rem;
        margin-top: 2rem;

        td {
            padding: .75rem;
        }

        .border {
            border-top: .1rem solid $grey-dark;
        }

        .price {
            font-family: $font-family-secondary-medium;
            font-size: 1.6rem;
            line-height: 2rem;
            color: $primary-color;
            text-align: right;
        }
    }

    @media screen and (min-width: 680px) {
        .wrap-head-page .wrap-main-header {
            .wrap-actions-quotation {
                position: initial;
                margin-top: 3rem;

                > .options-toggler,
                > .options-toggler + label {
                    display: none !important;

                    ~ .list-actions {
                        display: flex;
                        position: initial;

                        .action-item {
                            cursor: pointer;
                            position: initial;
                            display: flex;
                            align-items: center;
                            margin: 0 2rem 0 0;
                            padding: .25rem 1rem;
                            width: auto;
                            height: auto;
                            opacity: 1;
                            border: .15rem solid $secondary-color;
                            border-radius: 5rem;
                            font-size: 1.3rem;
                            color: $secondary-color;
                            text-transform: uppercase;

                            &:hover {
                                background-color: $secondary-color;
                                color: $white;
                            }

                            > .action-event {
                                border: 0;
                                font-size: inherit;
                                line-height: initial;
                                margin-right: 1rem;
                                background: transparent;
                                color: inherit;
                            }

                            span {
                                display: initial;
                                font-weight: $medium;
                                letter-spacing: 0.02em;
                            }
                        }
                    }
                }
            }
        }
    }
</style>
