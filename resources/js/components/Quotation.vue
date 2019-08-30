<template>
    <div>
        <main class="wrap-main-content">
            <div class="wrap-head-page">
                <header class="wrap-main-header">
                    <router-link v-if="!isMobile"
                                 class="go-back"
                                 tag="a"
                                 :to="{ name : 'home' }">
                        <i class="fas fa-arrow-left"></i>
                    </router-link>
                    <h1 class="page-main-title">Nouveau devis</h1>
                </header>
            </div>

            <div class="wrap-central">
                <form class="wrap-main-form">
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
                            <Identification v-if="step === 1" />
                            <Description v-if="step === 2" />
                            <Printing v-if="step === 3" />
                            <Finishing v-if="step === 4" />
                            <Packing v-if="step === 5" />
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
                <aside class="wrap-summary" :class="{ pull: summaryPulled }">
                    <div class="head-summary">
                        <input v-model="summaryPulled"
                               id="pull-summary"
                               class="pull-summary"
                               type="checkbox">
                        <label for="pull-summary" class="page-subtitle"><i :class="this.summaryPulled ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>Récapitulatif</label>
                    </div>
                    <div class="wrap-content-summary">
                        <img v-if="!form.description"
                             class="image-no-data"
                             src="/assets/img/undraw_no_data_qbuo.svg"
                             alt="Illustration montrant qu'aucune donnée n'a encore été saisie"/>

                        <div class="wrap-button-submit">
                            <button type="button" class="cta" disabled>
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
        </main>
    </div>
</template>

<script>
    import Identification from './Quotation/Identification';
    import Description from './Quotation/Description';
    import Printing from './Quotation/Printing';
    import Finishing from './Quotation/Finishing';
    import Packing from './Quotation/Packing';

    export default {
        components: {
            Identification,
            Description,
            Printing,
            Finishing,
            Packing,
        },
        data() {
            return {
                step: 1,
                steps: [
                    {
                        img: "/assets/img/workflow/undraw_man_eiev.svg",
                        title: "Identité du donneur d'ordre",
                    },
                    {
                        img: "/assets/img/workflow/undraw_to_do_list_a49b.svg",
                        title: "Descriptif du produit",
                    },
                    {
                        img: "/assets/img/workflow/undraw_printing_invoices_5r4r.svg",
                        title: "Impression et support",
                    },
                    {
                        img: "/assets/img/workflow/undraw_files1_9ool.svg",
                        title: "Finition",
                    },
                    {
                        img: "/assets/img/workflow/undraw_collecting_fjjl.svg",
                        title: "Conditionnement et expédition",
                    },
                    {
                        img: "/assets/img/workflow/undraw_empty_cart_co35.svg",
                        title: "Bilan",
                    }
                ],
                progress: 16.666,
                summaryPulled: false,
                form: {
                    description: "",
                }
            }
        },
        mounted() {

        },
        methods: {
            prev() {
                this.step--;
                this.progress = (this.step * 100) / 6;
            },
            next() {
                this.step++;
                this.progress = (this.step * 100) / 6;
            },
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

    .wrap-summary {
        width: 100%;
        height: 100%;
        position: fixed;
        top: calc(100vh - 13.5rem);
        background-color: #fff;
        z-index: 1;
        border-radius: 3rem 3rem 0 0 ;
        margin: 0 -2rem;
        padding: 1rem 2rem;
        box-shadow: 0 0 .5rem rgba($primary-color-dark, 0.2);
        transition: 0.5s;

        &.pull {
            top: 8.4rem;
        }

        .head-summary {
            text-align: center;

            .pull-summary {
                position: absolute;
                left: -9999px;

                & + label {
                    width: 100%;
                    transition: 0.4s;

                    [class^="fa"] {
                        display: block;
                        width: 100%;
                        color: $secondary-color;
                    }
                }

                &:checked {
                    & + label {
                        /*color: $white;
                        background-color: $secondary-color;
                        border: .15rem solid $secondary-color;
                        transform: rotate(180deg);*/
                    }
                }
            }
        }

        .wrap-content-summary {
            margin-top: 2rem;

            .image-no-data {
                display: block;
                width: 100%;
                max-width: 41rem;
                margin: 0 auto;
            }
        }
    }

    @media screen and (min-width: 680px) {
        .wrap-main-content {
            .wrap-head-page {
                .wrap-main-header {
                    display: flex;
                    align-items: center;

                    .go-back {
                        margin-right: 2rem;
                        font-size: 2.5rem;
                    }
                }
            }
        }

        .wrap-central {
            display: table;
            width: 100%;
            margin-top: 2rem;

            > * {
                display: table-cell;
                width: 100%;
            }

            .wrap-main-form {
                margin: 0;
                padding-right: 2rem;

                .wrap-step {
                    text-align: left;
                    
                    .image-step {
                        margin-left: 0;
                    }
                }
            }

            .wrap-summary {
                position: initial;
                margin: 0 0 0 2rem;
                border-radius: 2rem 1rem 3rem 1rem;

                .head-summary {
                    label {
                        [class^="fa"] {
                            display: none;
                        }
                    }
                }
            }
        }
    }

    @media screen and (min-width: 880px) {
        .wrap-central {

            .wrap-main-form {
                width: calc(100% - 45rem);
                padding-right: 4rem;
            }

            .wrap-summary {
                width: 45rem;
            }
        }
    }
</style>