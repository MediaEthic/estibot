<template>
    <div>
        <Loader v-if="isLoading" />
        <main class="wrap-main-content">
            <div class="wrap-head-page">
                <header v-if="!isMobile && $route.params.id !== undefined" class="wrap-main-header">
                    <router-link class="go-back"
                                 tag="a"
                                 :to="{ name: 'quotations.show', params: { id: this.$route.params.id }}">
                        <i class="fas fa-arrow-left"></i>
                        Annuler
                    </router-link>
                    <h1 class="page-main-title">Modification du devis #{{ this.$route.params.id }}</h1>
                </header>

                <header v-else-if="!isMobile && $route.params.id === undefined" class="wrap-main-header">
                    <router-link
                        class="go-back"
                        tag="a"
                        :to="{ name: 'quotations.index' }">
                        <i class="fas fa-arrow-left"></i>
                        Retour
                    </router-link>
                    <h1 class="page-main-title">Nouveau devis</h1>
                </header>
            </div>

            <ValidationObserver tag="div" class="wrap-central" v-slot="{ handleSubmit }">
                <form class="wrap-main-form left-part" @submit.prevent="handleSubmit(onSubmit)">
                    <fieldset class="wrap-step">
                        <div class="wrap-progress-form">
                            <ul class="nav-tabs flex justify-between items-center">
                                <li
                                    v-for="(tab, index) in steps"
                                    :key="tab.component"
                                    class="nav-item cursor-pointer"
                                    @click="goToStep(index + 1)"
                                >
                                    <img
                                        class="image-step"
                                        :class="[ currentStep-1 === index ? 'w-20' : 'w-10' ]"
                                        :src="tab.img"
                                        :alt="tab.title"
                                        :title="tab.title"
                                    />
                                    <h2
                                        v-if="currentStep-1 === index"
                                        class="page-subtitle main-title-step">
                                        {{ tab.title }}
                                    </h2>
                                </li>
                            </ul>

                            <progress class="progress" :value="progress" max="100">
                                <div class="progress-bar">
                                    <span :style="{ width: progress + '%' }">Progress: {{ progress }}%</span>
                                </div>
                            </progress>
                        </div>

                        <section class="wrap-content-step">
                            <ValidationObserver
                                v-for="(tab, index) in steps"
                                :key="index"
                                v-if="currentStep === index+1"
                                :ref="'formSingleStep' + index"
                                v-slot="{ invalid, passes }"
                            >
                                <keep-alive>
                                    <component
                                        :is="tab.component"
                                        :id="tab.component"
                                    />
                                </keep-alive>

                                <div class="wrap-buttons-controls-step">
                                    <button type="button"
                                            class="button button-small button-secondary"
                                            v-if="currentStep > 1"
                                            @click="prev">
                                        <i class="fas fa-chevron-left"></i>
                                        Précédent
                                    </button>
                                    <button type="button" :disabled="invalid"
                                            class="button button-small button-primary next-step"
                                            v-if="currentStep < 6"
                                            @click="next">
                                        Suivant
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </ValidationObserver>
                        </section>
                    </fieldset>
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
            </ValidationObserver>

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
    import Loader from './Loader';
    import Identification from './Quotation/Identification';
    import Description from './Quotation/Description';
    import Printing from './Quotation/Printing';
    import Finishing from './Quotation/Finishing';
    import Packing from './Quotation/Packing';
    import Result from './Quotation/Result';
    import Notification from "./Notification";

    export default {
        components: {
            Loader,
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
                isLoading: false,
                isModalVisible: false,
                notification: {
                    body: [],
                },
                currentStep: 1,
                steps: [
                    {
                        img: "/assets/img/workflow/undraw_man_eiev.svg",
                        title: "Identité du donneur d'ordre",
                        component: "Identification",
                        isValid: true
                    },
                    {
                        img: "/assets/img/workflow/undraw_to_do_list_a49b.svg",
                        title: "Descriptif du produit",
                        component: "Description",
                        isValid: false
                    },
                    {
                        img: "/assets/img/workflow/undraw_printing_invoices_5r4r.svg",
                        title: "Impression et support",
                        component: "Printing",
                        isValid: false
                    },
                    {
                        img: "/assets/img/workflow/undraw_files1_9ool.svg",
                        title: "Finition",
                        component: "Finishing",
                        isValid: false
                    },
                    {
                        img: "/assets/img/workflow/undraw_collecting_fjjl.svg",
                        title: "Conditionnement et expédition",
                        component: "Packing",
                        isValid: false
                    },
                    {
                        img: "/assets/img/workflow/undraw_empty_cart_co35.svg",
                        title: "Bilan",
                        component: "Result",
                        isValid: false
                    }
                ],
                progress: 16.666,
                summaryPulled: false,
                summary: "",
            }
        },
        created() {
            this.isLoading = true;
            if (this.$route.params.id !== undefined) {
                this.$store.dispatch('getWorkflow', {
                    id: this.$route.params.id
                }).then(() => {
                    this.summary = this.form.summary;
                    this.$store.dispatch("getThirdLabels", {
                        ethic: this.form.identification.third.ethic,
                        third: this.form.identification.third.id,
                    });
                });
            } else {
                this.$store.dispatch('create');
            }

            this.$store.dispatch('getPrintings').then(() => {
                this.$store.dispatch('getReworkings').then(() => {
                    this.isLoading = false;
                });
            }).catch(() => {
                this.isLoading = false;
            });


        },
        computed: {
            form: {
                get() {
                    return this.$store.state.workflow.form;
                },
                set() {
                    return this.$store.state.workflow.form;
                },
            }
        },
        methods: {
            // cancelQuotation() {
            // sweet alter doesn't work
            //     this.$swal({
            //         title: 'Annulation',
            //         text: "Vous allez perdre le devis en cours",
            //         icon: 'warning',
            //         showCancelButton: true,
            //         showCloseButton: true,
            //         confirmButtonText: 'Oui, quitter',
            //         cancelButtonText: 'Non, rester'
            //     }).then((result) => {
            //         if (result.value) {
            //             this.$router.push({ name: 'quotations.index' });
            //         }
            //     })
            // },
            showNotification() {
                this.isModalVisible = true;
            },
            closeNotification() {
                this.isModalVisible = false;
            },
            prev() {
                this.currentStep--;
                document.getElementById('save-quotation').disabled = true;
                this.setProgressBar();
                this.updateSummary();
            },
            next() {
                this.steps[this.currentStep - 1].isValid = true;
                this.currentStep++;
                this.setProgressBar();
                this.updateSummary();
            },
            goToStep(step) {
                if (step <= this.currentStep) {
                    this.currentStep = step;
                    this.setProgressBar();
                    this.updateSummary();
                } else {
                    let formStepNumber = this.currentStep - 1;
                    let formRef = `formSingleStep` + formStepNumber;
                    this.$refs[formRef][0].validate().then(success => {
                        if (!success) {
                            return;
                        } else {
                            this.currentStep = step;
                            this.setProgressBar();
                            this.updateSummary();
                        }
                    });
                }
            },
            onSubmit() {
                console.log("form submitted");
            },
            setProgressBar() {
                this.progress = (this.currentStep * 100) / 6;
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
                if (this.form.finishing.finishings.length > 0 && this.form.finishing.finishings[0].id !== "") {
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
                max-width: 5rem;
                margin: 0 auto;
                transition: all .3s;
            }

            .main-title-step {
                width: 100%;
                margin: 1rem 0;
                transition: all .3s;
            }

            .progress {
                transition: all .3s;
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
                }
            }
        }
    }
</style>
