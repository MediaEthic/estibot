<template>
    <div>
        <main class="wrap-main-content">
            <div class="wrap-head-page">
                <header class="wrap-main-header">
                    <h1 class="page-main-title">Devis</h1>
                    <div class="tag tag-info">{{ quotations.total }}</div>
                </header>

                <div class="wrap-filters">
                    <div class="wrap-field h-50">
                        <span v-if="search" class="btn-right-field" @click="clearSearch">
                            <i class="fas fa-times"></i>
                        </span>
                        <input v-model.trim="search"
                               class="field"
                               :class="{ hasValue: search }"
                               name="search"
                               type="search"
                               autocomplete="off">

                        <span class="focus-field"></span>
                        <label class="label-field">Rechercher un client</label>
                        <span class="symbol-left-field"><i class="fas fa-search"></i></span>
                    </div>

                    <div class="wrap-icon-filter">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                </div>
            </div>

            <div v-if="!loading">
                <div v-if="quotations.data && quotations.data.length > 0" class="wrap-list-quotations">
                    <router-link v-if="!isMobile"
                                 class="create-new-quotation"
                                 tag="div"
                                 :to="{ name: 'quotations.create' }">
                        <i class="far fa-plus-square"></i>
                        <p class="text-new-quotation">Créer un<br> nouveau devis</p>
                    </router-link>

                    <router-link tag="article"
                                 v-for="quotation in quotations.data"
                                 :to="{
                                     name: 'quotations.show',
                                     params: {
                                        id: quotation.id
                                     }
                                 }"
                                 :key="quotation.id"
                                 class="wrap-quotation"
                                 :style="{ backgroundImage: 'url(/assets/img/quotations/' + quotation.image + ')' }">
                        <router-link tag="div"
                                     :to="{ name: 'quotations.show', params: { id: quotation.id }}">
                            <div class="head-quotation">
                                <h2 class="page-subtitle">Devis <span class="number-quotation">#{{ quotation.id }}</span></h2>
                                <div class="tag tag-info">{{ thirdType(quotation.third_type) }}</div>
                            </div>
                            <p class="baseline-main-title"><time :datetime="quotation.created_at">{{ getHumanDate(quotation.created_at) }}</time></p>
                            <p class="third-quotation">{{ quotation.third.name }}</p>
                            <div class="wrap-end-quotation">
                                <p class="price-quotation">{{ quotation.thousand.toFixed(2) }}<span class="symbol-price">€</span></p>
                                <div class="wrap-actions-quotation">
                                    <input :id="'options-toggler' + quotation.id" class="options-toggler" type="checkbox">
                                    <label :for="'options-toggler' + quotation.id" class="fas fa-cog"></label>
                                    <ul class="list-actions">
                                        <li class="action-item"><a href="#" class="fas fa-print"></a></li>
                                        <li class="action-item"><a href="#" class="fas fa-edit"></a></li>
<!--                                        <li class="action-item"><a href="#" class="fas fa-copy"></a></li>-->
                                        <li class="action-item" @click="destroyQuotation(quotation.id)"><i class="fas fa-trash-alt"></i></li>
                                    </ul>
                                </div>
                            </div>
                        </router-link>
                    </router-link>

                    <nav v-if="pagination.last_page > 1"
                         class="wrap-pagination">
                        <ul class="list-paginate">
                            <li class="paginate controls-paginate"
                                :class="[{ disabled: !pagination.previous_page }]">
                                <button type="button" class="link-paginate" @click="fetchQuotations(pagination.previous_page)">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                            </li>
                            <li class="paginate">
                                Page {{ pagination.current_page }} sur {{ pagination.last_page }}
                            </li>
                            <li class="paginate controls-paginate"
                                :class="[{ disabled: !pagination.next_page }]">
                                <button type="button" class="link-paginate" @click="fetchQuotations(pagination.next_page)">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </li>
                        </ul>
                    </nav>
                </div>
                <router-link v-else
                             :to="{ name: 'quotations.create' }"
                             tag="div"
                             class="wrap-empty-result">
                    <div class="wrap-image">
                        <img class="image-empty-result"
                             src="/assets/img/undraw_welcome_3gvl.svg"
                             alt="Illustration montrant qu'il n'existe encore aucun devis"/>

                        <h2 class="page-subtitle">Bonjour, <br>bienvenue sur Estibot</h2>
                        <p class="baseline-main-title">Commencez par créer un nouveau devis.</p>
                    </div>

                    <router-link class="create-new-quotation"
                                 tag="div"
                                 :to="{ name: 'quotations.create' }">
                        <i class="far fa-plus-square"></i>
                        <p class="text-new-quotation">Créer un<br> nouveau devis</p>
                    </router-link>
                </router-link>
            </div>

            <div v-else class="wrap-list-quotations">
                <Loader />
                <router-link v-if="!isMobile"
                             class="create-new-quotation"
                             tag="div"
                             :to="{ name: 'quotations.create' }">
                    <i class="far fa-plus-square"></i>
                    <p class="text-new-quotation">Créer un<br> nouveau devis</p>
                </router-link>

                <article v-for="index in 15"
                         :key="index"
                         class="wrap-quotation"
                         :style="{ backgroundImage: 'url(' + randomBgImage() + ')' }">
                    <div class="">
                        <div class="head-quotation">
                            <h2 class="page-subtitle">Devis <span class="number-quotation">#00000{{ index }}</span></h2>
                            <div class="tag tag-info">P</div>
                        </div>
                        <p class="baseline-main-title"><time datetime="2019-08-08">08/08/2019</time></p>
                        <p class="third-quotation">Nom du donneur d'ordre</p>
                        <div class="wrap-end-quotation">
                            <p class="price-quotation">123.94<span class="symbol-price">€</span></p>
                            <div class="wrap-actions-quotation">
                                <input :id="'options-toggler' + index" class="options-toggler" type="checkbox">
                                <label :for="'options-toggler' + index" class="fas fa-cog"></label>
                                <ul class="list-actions">
                                    <li class="action-item"><i class="fas fa-print"></i></li>
                                    <li class="action-item"><i class="fas fa-edit"></i></li>
                                    <li class="action-item"><i class="fas fa-copy"></i></li>
                                    <li class="action-item"><i class="fas fa-trash-alt"></i></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </main>
    </div>
</template>

<script>
    import Loader from './Loader';
    import moment from 'moment';

    export default {
        props: {
            dataSuccessMessage: {
                type: String,
            },
        },
        components: {
            Loader
        },
        data() {
            return {
                loading: true,
                search: "",
                pagination: {},
            }
        },
        computed: {
            quotations: {
                get() {
                    return this.$store.state.quotations;
                },
                set() {
                    return this.$store.state.quotations;
                }
            }
        },
        created() {
            console.log("Home component created");
            if (this.dataSuccessMessage !== undefined) {
                this.$toast.success({
                    title: this.dataSuccessMessage,
                    message: "Votre devis a bien été supprimé"
                });
            }

            this.$store.dispatch('getQuotations', {
                url: '/api/auth/quotations'
            }).then(res => {
                this.makePagination(this.quotations);
            });
        },
        methods: {
            clearSearch() {
                this.search = "";
            },
            getHumanDate(date) {
                return moment(date, 'YYYY-MM-DD').format('DD/MM/YYYY');
            },
            thirdType(thirdType) {
                if (thirdType === "new") {
                    return "P";
                }
            },
            makePagination(meta) {
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page: meta.next_page_url,
                    previous_page: meta.prev_page_url,
                };

                this.pagination = pagination;
                this.loading = false;
            },
            fetchQuotations(page_url) {
                this.loading = true;
                page_url = page_url || "/api/auth/quotations";
                this.$store.dispatch("getQuotations", {
                    url: page_url
                }).then(res => {
                    this.quotations = this.$store.state.quotations;
                    this.makePagination(this.$store.state.quotations);
                }).catch(err => console.log(err));
            },
            randomBgImage() {
                let random_images_array = ["undraw_Credit_card_3ed6.svg", "undraw_make_it_rain_iwk4.svg", "undraw_printing_invoices_5r4r.svg", "undraw_Savings_dwkw.svg"];
                // return "/assets/img/quotations/" . random_images_array[Math.floor(Math.random() * random_images_array.length)];
                // TODO : POSING PROBLEM
                return "/assets/img/quotations/undraw_Credit_card_3ed6.svg";
            },
            destroyQuotation(id) {
                this.$store.dispatch("destroyQuotation", {
                    id: id
                }).then(res => {
                    console.log(res);
                    // this.quotations = this.$store.state.quotations;
                    // this.makePagination(this.$store.state.quotations);
                }).catch(err => console.log(err));
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';

    .wrap-main-content {
        max-width: 120rem;
        min-height: calc(100vh - 15.4rem);
        margin: 0 auto;

        .wrap-head-page {
            .wrap-main-header {
                display: flex;
                justify-content: space-between;
                align-items: end;
                margin-bottom: 3rem;

                .page-main-title {
                    margin-right: 1rem;
                }
            }

            .wrap-filters {
                display: flex;
                justify-content: center;
                align-items: center;

                .wrap-field {
                    margin-bottom: 0;
                }

                .wrap-icon-filter {
                    font-size: 3rem;
                    color: $secondary-color;
                    margin-left: 2rem;
                }
            }
        }

        .wrap-list-quotations {
            display: flex;
            flex-flow: column wrap;
            justify-content: center;
            align-items: center;
            margin-top: 2rem;

            .create-new-quotation {
                display: none;
            }

            .wrap-quotation {
                width: 100%;
                min-height: 16.5rem;

                cursor: pointer;
                background-position: center right;
                background-size: auto 13.5rem;
                background-repeat: no-repeat;
                background-position-x: 9rem;
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

                > * {
                    display: flex;
                    flex-flow: column wrap;
                    background-color: rgba(#fff, .75);
                    min-height: 16.5rem;
                    padding: 1.5rem 2rem;

                    > * {
                        width: 100%;
                        flex-grow: 1;
                    }
                }

                .head-quotation {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                }
                .third-quotation {
                    color: $primary-color-dark;
                }

                .wrap-end-quotation {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-end;

                    .wrap-actions-quotation {
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

                                    &:nth-child(1) > * {
                                        transform: rotate(0deg);
                                    }

                                    &:nth-child(2) > * {
                                        transform: rotate(-60deg);
                                    }

                                    &:nth-child(3) > * {
                                        transform: rotate(-120deg);
                                    }

                                    &:nth-child(4) > * {
                                        transform: rotate(-180deg);
                                    }

                                    > * {
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
                                            transform: rotate(0deg) translateX(0) translateY(-6.5rem);
                                        }

                                        &:nth-child(2) {
                                            transform: rotate(60deg) translateX(-6rem) translateY(2rem);
                                        }

                                        &:nth-child(3) {
                                            transform: rotate(120deg) translateX(3rem) translateY(4rem);
                                        }

                                        &:nth-child(4) {
                                            transform: rotate(180deg) translateX(0) translateY(-3rem);
                                        }

                                        > * {
                                            pointer-events: auto;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            .wrap-pagination {
                display: flex;
                justify-content: center;
                width: 100%;
                margin-top: 1rem;

                .list-paginate {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    width: 100%;

                    .controls-paginate {
                        cursor: pointer;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        width: 3.5rem;
                        height: 3.5rem;
                        margin: 0 1rem;
                        border: .15rem solid $primary-color;
                        border-radius: 50%;
                        font-size: 2rem;
                        color: $primary-color;
                        text-align: center;
                        line-height: 3.25rem;
                        transition: all .4s;

                        &:hover {
                            background-color: rgba($secondary-color, 0.25);
                            border: .15rem solid $secondary-color;
                            color: $secondary-color;

                            .link-paginate {
                                color: $secondary-color;
                            }
                        }

                        .link-paginate {
                            cursor: pointer;
                            color: inherit;
                        }

                        &.disabled {
                            display: none;
                        }
                    }
                }

            }
        }

        .wrap-empty-result {
            display: flex;
            flex-flow: row wrap;
            justify-content: center;
            align-content: center;
            min-height: calc(100vh - 22.4rem);
            margin-top: 2rem;
            text-align: center;

            .wrap-image {
                width: 100%;

                .image-empty-result {
                    width: 100%;
                    max-width: 10rem;
                    display: block;
                    margin: 0 auto 2rem auto;
                }
            }
        }

        .create-new-quotation {
            cursor: pointer;
            background-color: #fff;
            color: $secondary-color;
            border: .3rem dashed $secondary-color;
            border-radius: 2rem 1rem 3rem 1rem;
            text-align: center;
            display: flex;
            flex-flow: row wrap;
            justify-content: center;
            align-content: center;
            padding: 2rem 3rem;
            margin: 5rem auto 0 auto;
            max-width: 20rem;
            -webkit-transition: all .4s;
            -moz-transition: all .4s;
            -ms-transition: all .4s;
            -o-transition: all .4s;
            transition: all .4s;

            &:hover {
                transform: scale(1.1);
            }

            i {
                font-size: 4rem;
                margin-bottom: 1rem;
            }

            .text-new-quotation {
                width: 100%;
                text-transform: uppercase;
            }
        }
    }

    @media (max-width: 321px) and (orientation: portrait) {
        .wrap-empty-result {
            .create-new-quotation {
                margin-top: 1rem;
                padding: 1rem 2rem;
            }
        }
    }

    @media (min-width: 680px) {
        .wrap-main-content {
            .wrap-head-page {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 3rem;

                .wrap-main-header {
                    justify-content: left;
                    margin-bottom: 0;
                }
            }

            .wrap-list-quotations {
                flex-flow: row wrap;

                > .create-new-quotation,
                > .wrap-quotation {
                    max-width: 28rem;
                    margin: 1rem;
                }

                > .wrap-quotation {
                    > * {
                        background-color: rgba(#fff, .75);
                    }
                }
                > .create-new-quotation {
                    display: flex;
                    width: 28rem;
                    min-height: 16.5rem;
                }
            }

            .wrap-empty-result {
                .wrap-image {
                    .image-empty-result {
                        max-width: 20rem !important;
                    }
                }

                .create-new-quotation {
                    max-width: 28rem !important;
                    height: 18rem;
                }
            }
        }



    }

    @media (min-width: 680px) and (orientation: landscape) {

    }
</style>
