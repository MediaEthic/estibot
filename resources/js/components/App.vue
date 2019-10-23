<template>
    <div v-if="!loading" class="wrap-padding">
        <nav v-if="!isMobile" class="wrap-main-navigation">
            <router-link :to="{ name: 'quotations.index' }"
                         tag="a"
                         title="Retour sur la page d'accueil">
                <img src="/assets/img/logo-ethic-software.png"
                 alt="Logotype Ethic Software"
                 class="main-logo" />
            </router-link>
            <form v-if="loggedIn" @submit.prevent="logout">
                <button type="submit" class="button button-small button-outline-secondary button-submit-secondary">
                    Déconnexion
                </button>
            </form>
        </nav>

        <transition :name="transitionName"
                mode="out-in">
            <router-view :user="user"></router-view>
        </transition>

        <footer v-if="isMobile" class="wrap-main-mobile-footer">
            <nav>
                <ul class="wrap-main-menu">
                    <li v-for="(route, key) in routes">
                        <router-link :to="{ name: route.path }"
                                     :key="key"
                                     class="link-menu">
                            <i :class="route.icon"></i>
                            {{route.name}}
                        </router-link>
                    </li>
                </ul>
            </nav>
        </footer>

        <footer v-else class="wrap-main-footer">
            <p>Éthic Software - Copyright © Tous droits réservés</p>
        </footer>
    </div>
    <Spinner v-else :quote="quote" />
</template>

<script>
    import '!!vue-style-loader!css-loader!cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css'
    import Spinner from './Spinner.vue';
    const DEFAULT_TRANSITION = 'fade';

    export default {
        components: {
            Spinner
        },
        data() {
            return {
                loading: false,
                transitionName: DEFAULT_TRANSITION,
                routes: [
                    {
                        name: 'Accueil',
                        path: 'quotations.index',
                        icon: 'fas fa-home'
                    },
                    {
                        name: 'Devis',
                        path: 'quotations.create',
                        icon: 'fas fa-plus-circle'
                    },
                    {
                        name: 'Profil',
                        path: 'profile',
                        icon: 'fas fa-user'
                    },
                ]
            }
        },
        created() {
            // this.loading = true;

            this.$store.dispatch('getQuote').then(() => {
                this.isViewed();
            });

            this.$router.beforeEach((to, from, next) => {
                let transitionName = to.meta.transitionName || from.meta.transitionName;

                if (transitionName === 'slide') {
                    const toDepth = to.path.split('/').length;
                    const fromDepth = from.path.split('/').length;
                    transitionName = toDepth < fromDepth ? 'slide-right' : 'slide-left';
                }

                this.transitionName = transitionName || DEFAULT_TRANSITION;

                next();
            });
        },
        computed: {
            quote() {
                return this.$store.state.quote;
            },
            user() {
                return this.$store.state.user;
            },
            loggedIn() {
                return this.$store.getters.loggedIn;
            }
        },
        methods: {
            isViewed() {
                let quote = this.quote.quote;
                let numberWords = quote.split(' ');
                let readTime = numberWords.length * 300;

                setTimeout(() => {
                    this.loading = false;
                }, readTime);
            },
            logout () {
                this.$store.dispatch('logout',).then(resp => {
                    this.$router.push({ name: "login" });
                }).catch(error => {
                    this.$router.push({ name: "login" });
                });
            },
        }
    }
</script>

<style lang="scss">
    /*@import url('https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css');*/
    @import '~@/_variables.scss';

    body {
        font-family: $font-family-primary;
        font-weight: $regular;
        font-size: $font-size-base;
        line-height: $line-height-base;
        color: $grey-dark;
        max-width: 150rem;
        margin: 0 auto;
    }

    a {
        font-family: $font-family-primary;
        color: $primary-color;
        text-transform: uppercase;
        transition: all 0.4s;
        -webkit-transition: all 0.4s;
        -o-transition: all 0.4s;
        -moz-transition: all 0.4s;

        &:link,
        &:visited {
            text-decoration: none;
        }

        &:hover,
        &:active,
        &:focus {
            font-weight: $bold;
            color: $secondary-color;
        }
    }

    .wrap-padding {
        width: 100%;
        min-height: 100%;
        padding: 2rem;

        .wrap-main-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;

            .main-logo {
                width: 15rem;
            }
        }

        > div {
            min-height: 100%;
        }

        .wrap-main-content {
            margin-bottom: 8rem !important;
        }
    }

    .mobile-hidden {
        display: none;
    }

    .no-footer + .wrap-main-mobile-footer {
        display: none;
    }

    .page-main-title {
        font-weight: $bold;
        font-size: 3rem;
        line-height: 3.4rem;
        color: $primary-color;
        letter-spacing: -0.02em;
    }

    .baseline-main-title {
        font-family: $font-family-secondary-light;
        font-size: 1.6rem;
        line-height: 2rem;
        color: $grey-dark;
        letter-spacing: 0.1em;
    }

    .page-subtitle {
        font-weight: $bold;
        font-size: 2rem;
        line-height: 2.4rem;
        color: $primary-color-dark;
    }

    .price-quotation {
        font-family: $font-family-secondary-medium;
        font-size: 1.8rem;
        line-height: 2.2rem;
        color: $primary-color;
        letter-spacing: 0.1em;
    }

    .tag {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: .5rem 1rem;
        min-width: 3.5rem;

        font-family: $font-family-secondary-medium;
        font-size: 1.4rem;
        line-height: 1.8rem;
        color: $secondary-color-dark;
        background-color: $secondary-color-light;
        border-radius: 1rem;
        letter-spacing: 0.1em;
        white-space: nowrap;

        .tag-info {
            color: $secondary-color-dark;
            background-color: $secondary-color-light;
        }
    }

    .info {
        width: 100%;
        font-size: 1.4rem;
        line-height: 1.8rem;

        &.info-error{
            color: $secondary-color;
        }
    }


    // Transitions
    .fade-enter-active,
    .fade-leave-active {
        transition-duration: 0.3s;
        transition-property: opacity;
        transition-timing-function: ease;
    }

    .fade-enter,
    .fade-leave-active {
        opacity: 0
    }


    .slide-fade-enter-active {
        transition: all .3s ease;
    }
    .slide-fade-leave-active {
        transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }
    .slide-fade-enter, .slide-fade-leave-to
        /* .slide-fade-leave-active below version 2.1.8 */ {
        transform: translateX(10px);
        opacity: 0;
    }


    .slide-left-enter-active,
    .slide-left-leave-active,
    .slide-right-enter-active,
    .slide-right-leave-active {
        transition-duration: 0.5s;
        transition-property: height, opacity, transform;
        transition-timing-function: cubic-bezier(0.55, 0, 0.1, 1);
        overflow: hidden;
    }

    .slide-left-enter,
    .slide-right-leave-active {
        opacity: 0;
        transform: translate(2em, 0);
    }

    .slide-left-leave-active,
    .slide-right-enter {
        opacity: 0;
        transform: translate(-2em, 0);
    }


    // Modal component
    .modal-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .5);
        display: table;
        transition: opacity .3s ease;
    }

    .modal-wrapper {
        display: table-cell;
        vertical-align: middle;
        padding: 0 10rem;
    }

    .modal-container {
        position: relative;
        width: 100%;
        max-width: 150rem;
        margin: 0 auto;
        padding: 3rem;
        background-position: bottom right;
        background-size: auto 25rem;
        background-repeat: no-repeat;
        background-color: #fff;
        border-top: 3rem solid $primary-color;
        border-radius: 2rem 1rem 3rem 1rem;
        box-shadow: 0 0 1rem rgba($primary-color-dark, .4);
        transition: all .3s ease;

        .btn-close {
            -moz-appearance: none;
            -webkit-appearance: none;
            background-color: $primary-color-dark;
            border: none;
            border-radius: 290486px;
            cursor: pointer;
            pointer-events: auto;
            display: inline-block;
            flex-grow: 0;
            flex-shrink: 0;
            font-size: 1rem;
            width: 2rem;
            height: 2rem;
            max-height: 2rem;
            max-width: 2rem;
            min-height: 2rem;
            min-width: 2rem;
            outline: 0;
            vertical-align: top;
            position: absolute;
            right: .5rem;
            top: -2.5rem;
            font-weight: $bold;
            color: #fff;

            &:before {
                background-color: #fff;
                content: "";
                display: block;
                position: absolute;
                top: 50%;
                left: 50%;
                height: .2rem;
                width: 50%;
                -webkit-transform: translateX(-50%) translateY(-50%) rotate(45deg);
                transform: translateX(-50%) translateY(-50%) rotate(45deg);
                -webkit-transform-origin: center center;
                transform-origin: center center;
            }

            &:after {
                background-color: #fff;
                content: "";
                display: block;
                position: absolute;
                top: 50%;
                left: 50%;
                height: 50%;
                width: .2rem;
                -webkit-transform: translateX(-50%) translateY(-50%) rotate(45deg);
                transform: translateX(-50%) translateY(-50%) rotate(45deg);
                -webkit-transform-origin: center center;
                transform-origin: center center;
            }
        }

        .modal-header {
            padding-bottom: 1rem;
            border-bottom: .1rem solid $primary-color-dark;
        }

        .modal-body {
            margin: 20px 0;
        }

        .modal-default-button {
            float: right;
        }
    }


    /*
     * The following styles are auto-applied to elements with
     * transition="modal" when their visibility is toggled
     * by Vue.js.
     *
     * You can easily play with the modal transition by editing
     * these styles.
     */

    .modal-enter {
        opacity: 0;
    }

    .modal-leave-active {
        opacity: 0;
    }

    .modal-enter .modal-container,
    .modal-leave-active .modal-container {
        -webkit-transform: scale(1.1);
        transform: scale(1.1);
    }



    :-webkit-autofill {
        animation-name: onAutoFillStart;
    }
    :not(:-webkit-autofill) {
        animation-name: onAutoFillCancel;
    }
    @keyframes onAutoFillStart {
        from {
        }
        to {
        }
    }
    @keyframes onAutoFillCancel {
        from {
        }
        to {
        }
    }


    // css spinner
    .lds-ring {
        display: inline-block;
        position: relative;
        width: 2.5rem;
        height: 2.5rem;
    }
    .lds-ring div {
        box-sizing: border-box;
        display: block;
        position: absolute;
        width: 2.5rem;
        height: 2.5rem;
        border: .3rem solid $primary-color;
        border-radius: 50%;
        animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        border-color: $primary-color transparent transparent transparent;
    }
    .lds-ring div:nth-child(1) {
        animation-delay: -0.45s;
    }
    .lds-ring div:nth-child(2) {
        animation-delay: -0.3s;
    }
    .lds-ring div:nth-child(3) {
        animation-delay: -0.15s;
    }
    @keyframes lds-ring {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }





    .wrap-main-mobile-footer {
        background-color: #fff;
        box-shadow: 0 0 .5rem rgba($grey-dark, 0.1);
        border-radius: 3rem 3rem 0 0;
        width: 100%;
        height: 7rem;
        position: fixed;
        left: 0;
        bottom: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 5;

        > * {
            width: 100%;
        }

        .wrap-main-menu {
            display: flex;
            justify-content: space-around;
            width: 100%;
            padding: 0 1.5rem;

            .link-menu {
                &:hover,
                &:active,
                &:focus {
                    padding: 1rem 1.5rem;
                    background-color: rgba($secondary-color-light, 0.25);
                    border-radius: 4rem;
                }

                .fas {
                    margin-right: .5rem;
                }
            }

            .router-link-exact-active {
                font-weight: $bold;
                color: $secondary-color;
                padding: 1rem 1.5rem;
                background-color: rgba($secondary-color-light, 0.25);
                border-radius: 4rem;

                [class^='fa'] {
                    margin-right: 1rem;
                }
            }
        }
    }

    .wrap-main-footer {
        font-size: 1.4rem;
        line-height: 1.8rem;
        text-align: center;
        border-top: .075rem solid $grey-dark;
        padding: 1.5rem 4rem 0 4rem;
    }

    @media screen and (min-width: 680px) {
        .wrap-padding {
            padding: 3rem;
        }

        .mobile-hidden {
            display: initial;
        }
    }
</style>
