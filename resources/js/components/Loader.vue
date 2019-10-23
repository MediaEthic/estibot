<template>
    <transition name="fade" mode="out-in">
        <div class="wrap-loader">
            <div class="overlay"></div>
            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
    <!--        <img class="image-loading"-->
    <!--             src="/assets/img/undraw_loading_frh4.svg"-->
    <!--             alt="Illustration du contenu qui charge"/>-->
        </div>
    </transition>
</template>

<script>
    export default {

    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';

    .wrap-loader {
        .overlay {
            z-index: 9999;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba($white, .75);
        }

        .lds-ring {
            z-index: 10000;
            position: fixed;
            top: 50%;
            left: 50%;
        }
    }

    /*.image-loading {*/
    /*    display: block;*/
    /*    width: 100%;*/
    /*    max-width: 32rem;*/
    /*    margin: 0 auto;*/
    /*}*/
</style>
