<template>
    <div class="wrap-padding">
        <router-view></router-view>
        <footer class="wrap-main-footer">
            <ul class="wrap-main-menu">
                <li v-for="(route, key) in routes">
                    <router-link :to="{ name : route.path }"
                                 :key="key"
                                 class="link-menu">
                        <i :class="route.icon"></i>
                        {{route.name}}
                    </router-link>
                </li>
            </ul>
        </footer>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                routes: [
                    {
                        name: 'Accueil',
                        path: 'home',
                        icon: 'fas fa-home'
                    },
                    {
                        name: 'Devis',
                        path: 'quotation',
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
        mounted() {

        }
    }
</script>

<style lang="scss">
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
            padding: 1rem 1.5rem;
            background-color: rgba($secondary-color-light, 0.25);
            border-radius: 4rem;
        }
    }

    .wrap-padding {
        width: 100%;
        padding: 2rem;
    }

    .mobile-hidden {
        display: none;
    }

    .no-footer + .wrap-main-footer {
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

    .wrap-main-footer {
        background-color: #fff;
        box-shadow: 0 0 .5rem rgba($grey-dark, 0.1);
        width: 100%;
        height: 7rem;
        position: fixed;
        left: 0;
        bottom: 0;
        display: flex;
        justify-content: center;
        align-items: center;

        .wrap-main-menu {
            display: flex;
            justify-content: space-around;
            width: 100%;
            padding: 0 1.5rem;

            .link-menu {
                .fas {
                    margin-right: .5rem;
                }
            }

            .router-link-active {
                font-weight: $bold;
                color: $secondary-color;
                padding: 1rem 1.5rem;
                background-color: rgba($secondary-color-light, 0.25);
                border-radius: 4rem;

                .fas {
                    margin-right: 1rem;
                }
            }
        }
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