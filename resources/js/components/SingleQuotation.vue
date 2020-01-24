<template>
    <div>
        <main class="wrap-main-content">
            <Loader v-if="loading" />
            <div class="wrap-head-page">
                <header class="wrap-main-header">
                    <router-link class="go-back"
                                 tag="a"
                                 :to="{ name: 'quotations.index' }">
                        <i class="fas fa-arrow-left"></i>
                        Retour
                    </router-link>
                    <h1 class="page-main-title">Devis #{{ quotation.id }}</h1>
                    <div class="wrap-actions-quotation">
                        <input id="options-toggler" class="options-toggler" type="checkbox">
                        <label for="options-toggler" class="fas fa-cog"></label>
                        <ul class="list-actions">
                            <li class="action-item"><a :href="'/api/auth/quotations/' + quotation.id + '/pdf/' + company" target="_blank"><i class="fas fa-print action-event"></i><span>Imprimer</span></a></li>
                            <li class="action-item" @click="showModal = true"><i class="fas fa-paper-plane action-event"></i><span>Envoyer par e-mail</span></li>
                            <router-link tag="li" :to="{ name: 'quotations.edit', params: { id: quotation.id } }" class="action-item"><i class="fas fa-edit action-event"></i><span>Modifier</span></router-link>
<!--                            <li class="action-item"><i class="fas fa-copy action-event"></i><span>Dupliquer</span></li>-->
                            <li class="action-item" @click="destroyQuotation(quotation.id)"><i class="fas fa-trash-alt action-event"></i><span>Supprimer</span></li>
                        </ul>
                    </div>
                    <div class="wrap-list-details-quotation">
                        <ul class="list-details-quotation">
                            <li class="item-detail-quotation">
                                <i class="fas fa-user-tie"></i>
                                <address>
                                    <span v-for="line in third">
                                        {{ line }}
                                        <br>
                                    </span>
                                </address>
                            </li>
                            <li class="item-detail-quotation">
                                <div class="wrap-field h-50">
                                    <select v-model="quotation.settlement_id"
                                            @focus="form.hasFocus = true"
                                            @blur="form.hasFocus = false"
                                            @animationstart="checkAnimation"
                                            class="field select"
                                            :class="{ hasValue: quotation.settlement_id }">
                                        <option v-for="settlement in quotation.settlements"
                                                v-bind:value="settlement.id">
                                            {{ settlement.name }}
                                        </option>
                                    </select>
                                    <span class="focus-field"></span>
                                    <label class="label-field">Conditions de règlement</label>
                                    <span class="symbol-left-field"><i class="fas fa-money-check"></i></span>
                                </div>
                            </li>
                            <li class="item-detail-quotation">
                                <i class="fas fa-user"></i>
                                <p>Suivi par {{ quotation.user_name }} {{ quotation.user_surname }}</p>
                            </li>

    <!--                        <li class="item-detail-quotation">-->
    <!--                            <i class="fas fa-spinner"></i>-->
    <!--                            <p>État : {{ quotation.status.name }}</p>-->
    <!--                        </li>-->
                        </ul>
                        <ul class="list-details-quotation">
                            <li class="item-detail-quotation">
                                <i class="far fa-calendar-plus"></i>
                                <p>Date de création : <time :datetime="quotation.created_at">{{ getHumanDate(quotation.created_at) }}</time></p>
                            </li>
                            <li class="item-detail-quotation">
                                <i class="far fa-calendar-check"></i>
                                <p>Date de modification : <time :datetime="quotation.updated_at">{{ getHumanDate(quotation.updated_at) }}</time></p>
                            </li>
    <!--                        <li class="item-detail-quotation">-->
    <!--                            <i class="fas fa-hourglass-half"></i>-->
    <!--                            <label>Durée de validité :</label>-->
    <!--                            <input type="number" class="editable" step="1">-->
    <!--                            <select id="settlement"-->
    <!--                                    class="editable">-->
    <!--                                <option value="month">mois</option>-->
    <!--                                <option value="day">mois</option>-->
    <!--                            </select>-->
    <!--                        </li>-->
                            <li class="item-detail-quotation">
                                <i class="far fa-calendar-alt"></i>
                                <p>Fin de validité  : <time :datetime="quotation.validity">{{ getHumanDate(quotation.validity) }}</time></p>
                            </li>
                        </ul>
                    </div>
                </header>
            </div>

            <div class="wrap-central">
                <div class="left-part">
                    <table class="table-responsive">
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
                        <tr v-for="(quantity, index) in quotation.quantities">
                            <td data-label="Quantité">{{ quantity.quantity }}</td>
                            <td data-label="Marge (%)">
                                <input type="number" class="editable" step="0.0001" @change="calculateCost('margin', index)" v-model="quantity.margin">
                            </td>
                            <td data-label="Prix du mille (€)">
                                <input type="number" class="editable" step="0.0001" @change="calculateCost('thousand', index)" v-model="quantity.thousand">
                            </td>
                            <td data-label="Frais d'expédition (€)">{{ quantity.shipping }}</td>
                            <td data-label="Prix HT (€)">{{ quantity.subtotal }}</td>
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
                <textarea v-model="quotation.description" class="editable" @keydown="textareaAutosize">
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
                                <td class="price">{{ (parseFloat(quotation.cost) + parseFloat(quotation.shipping)).toFixed(2) }}€</td>
                            </tr>
                            <tr>
                                <td>TVA</td>
                                <td class="price">{{ quotation.vat_price  }}€</td>
                            </tr>
                            <tr>
                                <td>Total TTC</td>
                                <td class="price">{{ quotation.price }}€</td>
                            </tr>
                        </table>

                        <div class="wrap-button-submit">
                            <button type="submit" @click="updateQuotation(quotation)" class="cta" id="save-quotation">
                                <span>Sauvegarder</span>
                                <svg width="13px" height="10px" viewBox="0 0 13 10">
                                    <path d="M1,5 L11,5"></path>
                                    <polyline points="8 1 12 5 8 9"></polyline>
                                </svg>
                            </button>
                        </div>
                    </div>
                </aside>
            </div>

            <Modal v-if="showModal" @close="showModal = false">
                <h3 slot="header" class="page-main-title">Envoyer par e-mail</h3>

                <div slot="body">
                    <ValidationProvider class="wrap-field h-50"
                                        name="subject email"
                                        v-slot="{ errors }">
                        <input v-model.trim="quotation.contact.email"
                               class="field"
                               :class="[{ hasValue: quotation.contact.email }]"
                               type="email"
                               autocomplete="off"
                               required>
                        <span class="focus-field"></span>
                        <label class="label-field">E-mail</label>
                        <span class="symbol-left-field"><i class="fas fa-at"></i></span>
                        <span class="v-validate">{{ errors[0] }}</span>
                    </ValidationProvider>

                    <ValidationProvider class="wrap-field h-50"
                                        name="subject email"
                                        v-slot="{ errors }">
                        <input v-model.trim="quotation.subject_email"
                               class="field"
                               :class="[{ hasValue: quotation.subject_email }]"
                               type="text"
                               autocomplete="off"
                               required>
                        <span class="focus-field"></span>
                        <label class="label-field">Sujet</label>
                        <span class="symbol-left-field"><i class="fas fa-comment-dots"></i></span>
                        <span class="v-validate">{{ errors[0] }}</span>
                    </ValidationProvider>


                    <ValidationProvider class="wrap-field h-50"
                                        style="height: auto !important; min-height: 5rem;"
                                        name="body email"
                                        v-slot="{ errors }">
                        <textarea v-model.trim="quotation.body_email"
                                  @keydown="textareaAutosize"
                                  class="field editable"
                                  :class="[{ hasValue: quotation.body_email }]"
                                  autocomplete="off"
                                  required>
                        </textarea>

                        <span class="focus-field"></span>
                        <label class="label-field">Message</label>
                        <span class="symbol-left-field"><i class="fas fa-align-justify"></i></span>
                        <span class="v-validate">{{ errors[0] }}</span>
                    </ValidationProvider>

                    <transition name="modal-fade" mode="out-in">
                        <div v-if="serverErrors" class="notification notification-secondary notification-wrapper" role="alert">
                            <div class="notification-container">
                                <div class="notification-body">
                                    <p v-for="(value, key) in serverErrors" :key="key">
                                        {{ value[0] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </transition>
                </div>

                <div slot="footer">
                    <button type="button"
                            class="button button-small button-primary"
                            style="margin-left: auto;"
                            @click="sendEmail()">
                        Envoyer
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </Modal>
        </main>
    </div>
</template>

<script>
    import moment from 'moment';
    import Loader from './Loader';
    import Modal from "./Modal";

    export default {
        props: {
            id: Number,
        },
        components: {
            Loader,
            Modal
        },
        data() {
            return {
                loading: true,
                company: localStorage.getItem('company'),
                showModal: false,
                isModalVisible: false,
                image: "",
                third: [],
                summaryPulled: false,
                indexMinValue: "",
                user: {
                    name: "",
                    surname: "",
                },
                form: {
                    hasFocus: false,
                },
                serverErrors: ""
            }
        },
        created() {
            this.$store.dispatch('getQuotation', {
                id: this.$route.params.id
            }).then(() => {
                this.generateThird();
                document.getElementsByClassName('wrap-main-header')[0].style.backgroundImage = 'url(/assets/img/quotations/' + this.quotation.image + ')';

                let textareaList = document.getElementsByTagName("textarea");
                for(let i = 0; i < textareaList.length; i++) {
                    let el = textareaList[i];
                    setTimeout(() => {
                        el.style.cssText = 'height:auto !important; padding:0 !important;';
                        let scrollHeight = el.scrollHeight + 10;
                        el.style.cssText = 'height:' + scrollHeight + 'px !important';
                        if (el.value === "") {
                            el.style.cssText = 'height:100% !important; ';
                        }
                    }, 0);
                }

                let quantities = [];
                this.quotation.quantities.forEach(element => {
                    quantities.push(element.quantity);
                });
                let indexQuantity = quantities.indexOf(Math.min(...quantities));
                this.indexMinValue = indexQuantity;

                this.quotation.subject_email = "Votre demande de devis #" + this.quotation.id;
                this.loading = false;
           });
        },
        computed: {
            quotation: {
                get() {
                    return this.$store.state.quotation;
                },
                set() {
                    return this.$store.state.quotation;
                },
            },
        },
        methods: {
            checkAnimation({ target, animationName }) {
                if(animationName.startsWith("onAutoFillStart")) {
                    target.classList.add("hasValue");
                }
            },
            getHumanDate(date) {
                return moment(date, 'YYYY-MM-DD').format('DD/MM/YYYY');
            },
            generateThird() {
                this.third = [];
                if (this.quotation.third.name !== undefined) this.third.push(this.quotation.third.name);


                let contact = ``;
                if (this.quotation.contact.name !== undefined && this.quotation.contact.name !== null || this.quotation.contact.surname !== undefined && this.quotation.contact.surname !== null) {
                    let contact = `À l'attention de `;
                    if (this.quotation.contact.civility === "Mr") {
                        contact += `M.`;
                    } else {
                        contact += `Mme`;
                    }

                    if (this.quotation.contact.name !== undefined && this.quotation.contact.name !== null)  contact += ` ` + this.quotation.contact.name;
                    if (this.quotation.contact.surname !== undefined && this.quotation.contact.surname !== null)  contact += ` ` + this.quotation.contact.surname;
                    if (this.quotation.contact.service !== undefined && this.quotation.contact.service !== null)  contact += ` - Service ` + this.quotation.contact.service;

                    if (contact !== "") this.third.push(contact);
                }

                if (this.quotation.third.addressLine1 !== undefined && this.quotation.third.addressLine1 !== null) this.third.push(this.quotation.third.addressLine1);
                if (this.quotation.third.addressLine2 !== undefined && this.quotation.third.addressLine2 !== null) this.third.push(this.quotation.third.addressLine2);
                if (this.quotation.third.addressLine3 !== undefined && this.quotation.third.addressLine3 !== null) this.third.push(this.quotation.third.addressLine3);
                if (this.quotation.third.zipcode !== undefined && this.quotation.third.zipcode !== null) {
                    if (this.quotation.third.city !== undefined && this.quotation.third.city !== null) {
                        this.third.push(this.quotation.third.zipcode + ` ` + this.quotation.third.city);
                    } else {
                        this.third.push(this.quotation.third.zipcode);
                    }
                }

                if (this.quotation.contact.email !== undefined && this.quotation.contact.email !== null) this.third.push(this.quotation.contact.email);
            },
            textareaAutosize() {
                let textareaList = document.getElementsByTagName("textarea");
                for (let i = 0; i < textareaList.length; i++) {
                    let el = textareaList[i];
                    setTimeout(() => {
                        el.style.cssText = 'height:auto !important; padding:0 !important;';
                        let scrollHeight = el.scrollHeight + 10;
                        el.style.cssText = 'height:' + scrollHeight + 'px !important; ';
                        if (el.value === "") {
                            el.style.cssText = 'height:100% !important; ';
                        }
                    }, 0);
                }
            },
            generatePDF(id) {
                this.loading = true;
                this.$store.dispatch("generatePDF", {
                    id: id
                }).then(resp => {
                    this.loading = false;
                }).catch(err => {
                    this.loading = false;
                });
            },
            sendEmail() {
                this.serverErrors = "";
                this.loading = true;
                this.$store.dispatch("sendEmail", {
                    quotation: this.quotation,
                }).then(() => {
                    this.loading = false;
                    this.showModal = false;
                    this.$toast.success({
                        title: "Devis envoyé",
                        message: "Votre devis a bien été envoyé"
                    });
                }).catch(error => {
                    // TODO: handle server errors
                    this.serverErrors = [];
                    for (let i = 0; i < error.response.data.length; i++) {
                        this.serverErrors.push(error.response.data[i]);
                    }
                    this.loading = false;
                });
            },
            destroyQuotation(id) {
                this.loading = true;
                this.$store.dispatch("destroyQuotation", {
                    id: id
                }).then(() => {
                    this.loading = false;
                    this.$router.push({ name: "quotations.index", params: { dataSuccessMessage: "Devis supprimé" } });
                }).catch(err => {
                    this.loading = false;
                });
            },
            updateQuotation(quotation) {
                this.loading = true;
                this.$store.dispatch("updateQuotation", {
                    quotation: quotation,
                }).then(() => {
                    this.quotation = this.$store.state.quotation;
                    this.loading = false;
                    this.$toast.success({
                        title: "Devis modifié",
                        message: "Votre devis a bien été modifié"
                    });
                }).catch(err => {
                    this.loading = false;
                });
            },
            calculateCost(element, quantityID) {
                let quantity = this.quotation.quantities[quantityID].quantity;
                let margin = parseFloat(this.quotation.quantities[quantityID].margin);
                let cost = this.quotation.quantities[quantityID].cost;

                let costWithMargin = 0;
                let thousandWithMargin = 0;

                if (element === "margin") {
                    costWithMargin = cost + (cost * (margin / 100));
                    thousandWithMargin = (costWithMargin / quantity) * 1000;
                    this.quotation.quantities[quantityID].thousand = (thousandWithMargin).toFixed(2);

                } else if (element === "thousand") {
                    thousandWithMargin = parseFloat(this.quotation.quantities[quantityID].thousand);
                    costWithMargin = (thousandWithMargin / 1000) * quantity;
                    let costWithoutShipping = this.quotation.quantities[quantityID].subtotal - this.quotation.quantities[quantityID].shipping;
                    let percentage = ((costWithMargin * margin) / costWithoutShipping) / 100;
                    this.quotation.quantities[quantityID].margin = (parseFloat(margin) + percentage).toFixed(2);
                }

                let subtotal = parseFloat(this.quotation.quantities[quantityID].shipping + costWithMargin);
                subtotal = parseFloat(subtotal);
                let vat = parseFloat(this.quotation.vat);
                let vatPrice = subtotal * (vat / 100);
                let price = subtotal + vatPrice;

                this.quotation.quantities[quantityID].subtotal = (subtotal).toFixed(2);
                this.quotation.quantities[quantityID].price = (price).toFixed(2);

                if (quantityID === this.indexMinValue) {
                    this.quotation.cost = (costWithMargin).toFixed(2);
                    this.quotation.thousand = (thousandWithMargin).toFixed(2);
                    this.quotation.vat_price = (vatPrice).toFixed(2);
                    this.quotation.price = (price).toFixed(2);
                }
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
                            display: none;
                            top: 0;
                            bottom: 0;
                            left: 0;
                            right: 0;
                            margin: auto;
                            width: 3.5rem;
                            height: 3.5rem;
                            opacity: 0;
                            transition: 0.5s;

                            > a > .action-event,
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

                                &:hover {
                                    color: $secondary-color-light;
                                    border-color: $secondary-color-light;
                                    transform: scale(1.1);
                                }
                            }

                            span {
                                display: none;
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
                                display: block;
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

            .wrap-list-details-quotation {
                display: flex;
                flex-flow: row wrap;
                margin-top: 3rem;

                .list-details-quotation {
                    padding: 0 2rem;
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
    }

    .wrap-central {
        .left-part {
            overflow-x: auto;
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
                width: 100%;
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

                            > a {
                                color: $secondary-color;
                            }

                            &:hover {
                                background-color: $secondary-color;
                                color: $white;

                                * {
                                    color: $white;
                                }
                            }

                            > a > .action-event,
                            > .action-event {
                                display: initial;
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
