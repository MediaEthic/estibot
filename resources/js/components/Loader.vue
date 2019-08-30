<template>
    <div class="wrap-loader">
        <img class="image-loading"
             src="/assets/img/undraw_loading_frh4.svg"
             alt="Illustration du contenu qui charge"/>
    </div>
</template>

<script>
    export default {

    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';

    .image-loading {
        display: block;
        width: 100%;
        max-width: 32rem;
        margin: 0 auto;
    }
</style>