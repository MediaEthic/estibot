<template>
    <div>
        <main class="wrap-main-content">
            <div class="wrap-head-page">
                <header v-if="!isMobile && $route.params.id !== undefined" class="wrap-main-header">
                    <router-link class="go-back"
                                 tag="a"
                                 :to="{ name: 'quotations.show', params: { id: $route.params.id }}">
                        <i class="fas fa-arrow-left"></i>
                        Annuler
                    </router-link>
                    <h1 class="page-main-title">Modification du devis #{{ this.$route.params.id }}</h1>
                </header>

                <header v-else-if="!isMobile && $route.params.id === undefined" class="wrap-main-header">
                    <router-link class="go-back"
                                 tag="a"
                                 :to="{ name: 'quotations.index' }">
                        <i class="fas fa-arrow-left"></i>
                        Retour
                    </router-link>
                    <h1 class="page-main-title">Nouveau devis</h1>
                </header>
            </div>

            <div class="wrap-central">
                <form class="wrap-main-form left-part">
                    <fieldset class="wrap-step">
                        <img class="image-step"
                             :src="steps[step-1].img"
                             alt="Illustration d'un donneur d'ordre"/>
                        <h2 class="page-subtitle main-title-step">{{ steps[step-1].title }}</h2>
                        <progress class="progress" :value="progress" max="100">
                            <div class="progress-bar">
                                <span :style="{ width: progress + '%' }">Progress: {{ progress }}%</span>
                            </div>
                        </progress>
                        <section class="wrap-content-step">
                            <transition name="fade"
                                        mode="out-in">
                                <component :is="steps[step-1].component"></component>
                            </transition>
                        </section>
                    </fieldset>
                    <div class="wrap-buttons-controls-step">
                        <button type="button"
                                class="button button-small button-secondary"
                                v-if="step > 1"
                                @click="prev">
                            <i class="fas fa-chevron-left"></i>
                            Précédent
                        </button>
                        <button type="button"
                                class="button button-small button-primary next-step"
                                v-if="step < 6"
                                @click="next">
                            Suivant
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </form>
                <aside class="wrap-summary right-part" :class="{ pull: summaryPulled }">
                    <div class="head-summary">
                        <input v-model="summaryPulled"
                               id="pull-summary"
                               class="pull-summary"
                               type="checkbox">
                        <label for="pull-summary" class="page-subtitle"><i :class="this.summaryPulled ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>Récapitulatif</label>
                    </div>
                    <div class="wrap-content-summary">
                        <img v-if="summary === ''"
                             class="image-no-data"
                             src="/assets/img/undraw_no_data_qbuo.svg"
                             alt="Illustration montrant qu'aucune donnée n'a encore été saisie"/>

                        <div v-else>
                            <textarea v-model="summary"
                                      rows="15"
                                      disabled>
                            </textarea>

                            <div class="wrap-total-quotation">
                                <div class="wrap-subtotal">

                                </div>
                            </div>
                        </div>

                        <div class="wrap-button-submit">
                            <button type="submit" @click="saveQuotation" class="cta" id="save-quotation" disabled>
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

            <Notification v-show="isModalVisible"
                          @close="closeNotification">
                <ul slot="body">
                    <li v-for="error in notification.body">
                        {{ error }}
                    </li>
                </ul>
            </Notification>
        </main>
    </div>
</template>

<script>
    import Identification from './Quotation/Identification';
    import Description from './Quotation/Description';
    import Printing from './Quotation/Printing';
    import Finishing from './Quotation/Finishing';
    import Packing from './Quotation/Packing';
    import Result from './Quotation/Result';
    import Notification from "./Notification";

    export default {
        components: {
            Identification,
            Description,
            Printing,
            Finishing,
            Packing,
            Result,
            Notification,
        },
        data() {
            return {
                isModalVisible: false,
                notification: {
                    body: [],
                },
                step: 1,
                steps: [
                    {
                        img: "/assets/img/workflow/undraw_man_eiev.svg",
                        title: "Identité du donneur d'ordre",
                        component: "Identification",
                    },
                    {
                        img: "/assets/img/workflow/undraw_to_do_list_a49b.svg",
                        title: "Descriptif du produit",
                        component: "Description",
                    },
                    {
                        img: "/assets/img/workflow/undraw_printing_invoices_5r4r.svg",
                        title: "Impression et support",
                        component: "Printing",
                    },
                    {
                        img: "/assets/img/workflow/undraw_files1_9ool.svg",
                        title: "Finition",
                        component: "Finishing",
                    },
                    {
                        img: "/assets/img/workflow/undraw_collecting_fjjl.svg",
                        title: "Conditionnement et expédition",
                        component: "Packing",
                    },
                    {
                        img: "/assets/img/workflow/undraw_empty_cart_co35.svg",
                        title: "Bilan",
                        component: "Result",
                    }
                ],
                progress: 16.666,
                summaryPulled: false,
                summary: "",
            }
        },
        created() {
            console.log(this.$route.params.id);
            if (this.$route.params.id !== undefined) {
                this.$store.dispatch('getWorkflow', {
                    id: this.$route.params.id
                }).then(() => {
                    console.log(this.form);
                    this.summary = this.form.summary;
                });
            }
        },
        computed: {
            form: {
                get() {
                    return this.$store.state.workflow;
                },
                set() {
                    return this.$store.state.workflow;
                },
            }
        },
        methods: {
            showNotification() {
                this.isModalVisible = true;
            },
            closeNotification() {
                this.isModalVisible = false;
            },
            prev() {
                this.step--;
                this.progress = (this.step * 100) / 6;
                document.getElementById('save-quotation').disabled = true;
                this.updateSummary();
            },
            next() {
                this.step++;
                this.progress = (this.step * 100) / 6;

                this.updateSummary();
            },
            updateSummary() {
                this.summary = "";

                let labelName = ``;
                if (this.form.description.label.name !== "") {
                    labelName = ` : ` + this.form.description.label.name;
                }
                if (this.form.description.label.type === "old") this.summary += `Étiquette existante` + labelName;
                if (this.form.description.label.type === "new") this.summary += `Nouvelle étiquette` + labelName;


                if (this.form.description.label.width > 0 && this.form.description.label.length > 0) {
                    this.summary += `\nFormat : ` + this.form.description.label.width + `mm (laize) x ` + this.form.description.label.length + `mm (avance)`;
                }

                if (this.form.printing.press !== "") this.summary += `\nMachine : ` + this.form.printing.name;
                if (this.form.printing.colors > 0) this.summary += `\nImpression : ` + this.form.printing.colors + ` couleurs`;
                if (this.form.printing.quadri) this.summary += ` en quadrichromie`;
                if (this.form.printing.substrate.name !== "") {
                    if (this.form.printing.substrate.type === "old") this.summary += `\nPapier existant : ` + this.form.printing.substrate.name;
                    if (this.form.printing.substrate.type === "new") this.summary += `\nNouveau papier : ` + this.form.printing.substrate.name;
                    this.summary += ` (` + this.form.printing.substrate.width + `mm en laize - ` + this.form.printing.substrate.weight + `g/m²)`;
                } else {
                    if (this.form.printing.substrate.type === "old" && this.form.printing.substrate.width !== '') this.summary += `\nPapier existant : ` + this.form.printing.substrate.width + `mm en laize - ` + this.form.printing.substrate.weight + `g/m²`;
                    if (this.form.printing.substrate.type === "new" && this.form.printing.substrate.width !== '') this.summary += `\nNouveau papier : ` +  + this.form.printing.substrate.width + `mm en laize - ` + this.form.printing.substrate.weight + `g/m²`;
                }
                if (this.form.finishing.finishings.length > 0 && this.form.finishing.finishings[0].type !== "") {
                    if (this.form.finishing.finishings.length > 1) {
                        this.summary += `\nFinitions :`;
                    } else {
                        this.summary += `\nFinition : `;
                    }

                    this.form.finishing.finishings.forEach(el => {
                        let $consumable = "";
                        if (el.presence_consumable) $consumable = ` + consommable`;

                        if (this.form.finishing.finishings.length > 1) {
                            this.summary += `\n - ` + el.name + $consumable;
                        } else {
                            this.summary += el.name + $consumable;
                        }
                    });


                    if (this.form.finishing.cutting.name !== "") {
                        if (this.form.finishing.cutting.type === "old") this.summary += `\nOutil de découpe existant : ` + this.form.finishing.cutting.name;
                        if (this.form.finishing.cutting.type === "new") this.summary += `\nNouvel outil de découpe : ` + this.form.finishing.cutting.name;
                    } else {
                        if (this.form.finishing.cutting.type === "new") this.summary += `\nNouvel outil de découpe`;
                    }

                    let $direction = "";
                    if (this.form.packing.direction === "ehead") {
                        $direction = "extérieur tête en avant";
                    } else if (this.form.packing.direction === "efoot") {
                        $direction = "extérieur pied en avant";
                    } else if (this.form.packing.direction === "eright") {
                        $direction = "extérieur droite en avant";
                    } else if (this.form.packing.direction === "eleft") {
                        $direction = "extérieur droite en avant";
                    } else if (this.form.packing.direction === "ihead") {
                        $direction = "intérieur tête en avant";
                    } else if (this.form.packing.direction === "ifoot") {
                        $direction = "intérieur pied en avant";
                    } else if (this.form.packing.direction === "iright") {
                        $direction = "intérieur droite en avant";
                    } else if (this.form.packing.direction === "ileft") {
                        $direction = "intérieur gauche en avant";
                    }

                    if ($direction !== "") this.summary += `\nSens d'enroulement : ` + $direction;

                    if (this.form.packing.packing > 0) this.summary += `\nConditionnement : ` + this.form.packing.packing + ` étiquettes par bobine`;

                    this.$store.dispatch('updateQuotationSummary', {
                        summary: this.summary,
                    });
                }
            },
            saveQuotation() {
                this.$store.dispatch('saveQuotation', {
                    quotation: this.$route.params.id,
                }).then(resp => {
                    console.log(resp);
                    if (resp.errors === undefined) {
                        this.$router.push({ name: "quotations.show", params: { id: resp.data.id } });
                    } else {
                        this.showNotification();
                        this.notification.body = resp.errors;
                        setTimeout(() => {
                            this.closeNotification();
                        }, 5000);
                    }
                }).catch(error => {
                    console.log(error);
                });
            }
        }
    }
</script>

<style lang="scss">
    @import '~@/_variables.scss';

    .wrap-main-form {
        margin-top: 3rem;
        margin-bottom: 13rem;

        .wrap-step {
            text-align: center;

            .image-step {
                display: block;
                width: 100%;
                max-width: 5rem;
                margin: 0 auto;
            }

            .main-title-step {
                width: 100%;
                margin: 1rem 0;
            }

            .progress {
            }

            .wrap-content-step {
                margin-top: 3.5rem;
            }
        }

        .wrap-buttons-controls-step {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;

            .next-step {
                margin-left: auto;
            }
        }
    }


    @media screen and (min-width: 680px) {
        .wrap-main-content {
            .wrap-head-page {
                .wrap-main-header {
                    display: flex;
                    flex-flow: row wrap;
                    align-items: center;
                }
            }
        }

        .wrap-central {
            .wrap-main-form {
                .wrap-step {
                    text-align: left;

                    .image-step {
                        margin-left: 0;
                    }
                }
            }
        }
    }
</style>
