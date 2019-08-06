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
            <div class="wrap-list-quotations">
                <article class="wrap-quotation"
                         :style="{ backgroundImage: 'url(' + randomBgImage() + ')' }">
                    <div class="head-quotation">
                        <h2 class="page-subtitle">Devis <span class="number-quotation">#000021</span></h2>
                        <div class="tag tag-info">P</div>
                    </div>
                    <p class="baseline-main-title"><time datetime="2019-07-02">12/07/2019</time></p>
                    <p class="third-quotation">M. Jean Durand</p>
                    <div class="wrap-end-quotation">
                        <p class="price-quotation">123.94<span class="symbol-price">€</span></p>
                        <div class="wrap-actions-quotation">
                            <input id="options-toggler" class="options-toggler" type="checkbox">
                            <label for="options-toggler" class="fas fa-cog"></label>
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
        </main>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                search: "",
            }
        },
        created() {

        },
        computed: {

        },
        methods: {
            clearSearch() {
                this.search = "";
            },

            randomBgImage() {
                let random_images_array = ["undraw_Credit_card_3ed6.svg", "undraw_make_it_rain_iwk4.svg", "undraw_printing_invoices_5r4r.svg", "undraw_Savings_dwkw.svg"];

                return "/assets/img/quotations/" + random_images_array[Math.floor(Math.random() * random_images_array.length)];
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';

    .options-toggler {
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

        .wrap-quotation {
            display: flex;
            flex-flow: column wrap;
            width: 100%;
            min-height: 16.5rem;

            //background: bg-color bg-image position/bg-size bg-repeat bg-origin bg-clip bg-attachment initial|inherit;
            background-position: center right;
            background-size: auto 13.5rem;
            background-repeat: no-repeat;
            background-color: #fff;
            box-shadow: 0 0 .5rem rgba($primary-color-dark, 0.2);
            border-radius: 2rem 0 3rem 0;
            padding: 1.5rem 2rem;

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
                /*margin: 2rem 0;*/
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
                    [type="checkbox"]:not(:checked),
                    [type="checkbox"]:checked {
                        position: absolute;
                        left: -9999px;
                    }

                    .wrap-see-more {
                        font-size: 2rem;
                        color: $secondary-color;
                        background-color: #fff;
                        border: .15rem solid $secondary-color;
                        border-radius: 50%;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        width: 3.5rem;
                        height: 3.5rem;
                    }
                }
            }
        }
    }
</style>