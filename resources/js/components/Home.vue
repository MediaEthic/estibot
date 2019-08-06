<template>
    <div>
        <header class="wrap-main-header">
            <h1 class="page-main-title">Devis</h1>
            <div class="tag tag-info">14</div>
        </header>

        <main>
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
            <div v-if="quotations.data && quotations.data.length > 0" class="wrap-list-quotations">
                <article v-for="quotation in quotations.data"
                         :key="quotation.id"
                         class="wrap-quotation"
                         :style="{ backgroundImage: 'url(/assets/img/quotations/' + quotation.image + ')' }">
                    <div class="head-quotation">
                        <h2 class="page-subtitle">Devis <span class="number-quotation">#{{ quotation.id }}</span></h2>
                        <div class="tag tag-info">{{ thirdType(quotation.third_type) }}</div>
                    </div>
                    <p class="baseline-main-title"><time :datetime="quotation.created_at">{{ getHumanDate(quotation.created_at) }}</time></p>
                    <p class="third-quotation">{{ quotation.third.name }}</p>
                    <div class="wrap-end-quotation">
                        <p class="price-quotation">{{ quotation.price.toFixed(2) }}<span class="symbol-price">€</span></p>
                        <div class="wrap-actions-quotation">
                            <input :id="'options-toggler' + quotation.id" class="options-toggler" type="checkbox">
                            <label :for="'options-toggler' + quotation.id" class="fas fa-cog"></label>
                            <ul class="list-actions">
                                <li class="action-item"><a href="#" class="fas fa-print"></a></li>
                                <li class="action-item"><a href="#" class="fas fa-edit"></a></li>
                                <li class="action-item"><a href="#" class="fas fa-copy"></a></li>
                                <li class="action-item"><a href="#" class="fas fa-trash-alt"></a></li>
                            </ul>
                        </div>
                    </div>
                </article>
            </div>
            <router-link v-else
                         :to="{ name: 'quotation' }"
                         tag="div"
                         class="wrap-empty-result">
                <img class="image-empty-result"
                     src="/assets/img/undraw_welcome_3gvl.svg"
                     alt="Illustration montrant qu'il n'existe encore aucun devis"/>
                <h2 class="page-subtitle">Bonjour {{ user }}, <br>bienvenue sur Estibot</h2>
                <p class="baseline-main-title">Commencez par créer un nouveau devis.</p>
                <div class="create-new-quotation">
                    <i class="far fa-plus-square"></i>
                    <p class="text-new-quotation">Créer un<br> nouveau devis</p>
                </div>
            </router-link>
        </main>
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        props: [
            'user',
        ],
        data() {
            return {
                search: "",
                pagination: {},
            }
        },
        created() {
            this.$store.dispatch('getQuotations', {
                url: '/api/auth/quotations'
            }).then(res => {
                this.makePagination(this.quotations);
            })
        },
        computed: {
            quotations() {
                return this.$store.state.quotations;
            }
        },
        methods: {
            clearSearch() {
                this.search = "";
            },
            getHumanDate(date) {
                return moment(date, 'YYYY-MM-DD').format('DD/MM/YYYY');
            },
            makePagination(meta) {
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page: meta.next_page_url,
                    previous_page_url: meta.prev_page_url,
                };

                this.pagination = pagination;
            },
            thirdType(thirdType) {
                if (thirdType === "new") {
                    return "P";
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';

    .wrap-main-header {
        display: flex;
        justify-content: space-between;
        align-items: end;
    }

    .wrap-filters {
        margin-top: 3rem;
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

    .wrap-list-quotations {
        display: flex;
        flex-flow: column wrap;
        justify-content: center;
        align-items: center;
        margin-top: 2rem;
        margin-bottom: 8rem;

        .wrap-quotation {
            display: flex;
            flex-flow: column wrap;
            width: 100%;
            min-height: 16.5rem;

            background-position: center right;
            background-size: auto 13.5rem;
            background-repeat: no-repeat;
            background-color: #fff;
            box-shadow: 0 0 .5rem rgba($primary-color-dark, 0.2);
            border-radius: 2rem 1rem 3rem 1rem;
            padding: 1.5rem 2rem;
            margin-bottom: 1.5rem;

            > * {
                width: 100%;
                flex-grow: 1;
            }

            .head-quotation {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
            }
            .third-quotation {
                color: $primary-color-dark;
            }

            .price-quotation {
                font-family: $font-family-secondary-medium;
                font-size: 1.8rem;
                line-height: 2.2rem;
                color: $primary-color;
                letter-spacing: 0.1em;
            }

            .wrap-end-quotation {
                display: flex;
                justify-content: space-between;
                align-items: flex-end;

                .wrap-actions-quotation {
                    .options-toggler {
                        position: absolute;
                        left: -9999px;

                        &+ label {
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

                        &~ .list-actions {
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

                                &:nth-child(1) a {
                                    transform: rotate(0deg);
                                }

                                &:nth-child(2) a {
                                    transform: rotate(-60deg);
                                }

                                &:nth-child(3) a {
                                    transform: rotate(-120deg);
                                }

                                &:nth-child(4) a {
                                    transform: rotate(-180deg);
                                }
                            }

                            a {
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
                                    box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.3);
                                    color: white;
                                    background: rgba(255, 255, 255, 0.3);
                                    font-size: 44.44px;
                                }
                            }
                        }

                        &:checked {
                            &+ label {
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

                                    a {
                                        pointer-events:auto;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    .wrap-empty-result {
        margin-top: 2rem;
        text-align: center;

        .image-empty-result {
            width: 100%;
            max-width: 10rem;
            display: block;
            margin: 0 auto;
            margin-bottom: 2rem;
        }

        .create-new-quotation {
            color: $secondary-color;
            border: .3rem dashed $secondary-color;
            border-radius: 2rem 1rem 3rem 1rem;
            text-align: center;
            padding: 2rem 3rem;
            margin: 0 auto;
            margin-top: 2rem;
            max-width: 20rem;

            i {
                font-size: 4rem;
                margin-bottom: 1rem;
            }

            .text-new-quotation {
                text-transform: uppercase;
            }
        }
    }
</style>