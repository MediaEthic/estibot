<template>
    <div class="wrap-spinner">
        <img class="main-image"
             src="/assets/img/undraw_Artificial_intelligence_oyxx.svg"
             alt="Illustration de la page de connexion"/>

        <div class="wrap-quote">
            <i class="fas fa-angle-double-left"></i>
            <p class="quote page-subtitle"><q>{{ quote.quote }}</q></p>
            <p class="author baseline-main-title">{{ quote.author }}</p>
            <i class="fas fa-angle-double-right"></i>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'quote',
        ],
        data() {
            return {

            }
        },
        created() {
            // this.$store.dispatch('getQuote');
        },
        computed: {
            // quote() {
            //     return this.$store.state.quote;
            // }
        },
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';

    .wrap-spinner {
        height: 100vh;
        display: flex;
        flex-flow: row wrap;
        justify-content: center;
        align-items: center;
        padding: 4rem;

        img {
            width: 100%;
            margin-bottom: 2rem;
        }

        .wrap-quote {
            width: 100%;
            position: relative;
            margin: 3rem;

            [class^='fa'] {
                font-size: 4rem;
                color: $secondary-color-light;
                position: absolute;
                z-index: -1;

                &:nth-of-type(1) {
                    top: -3rem;
                    left: -2rem;
                }

                &:nth-of-type(2) {
                    bottom: 0;
                    right: -2rem;
                }
            }

            .author {
                font-family: $font-family-secondary-light;
            }
        }

        .author {
            /*font-variant: ;*/
        }
    }

</style>