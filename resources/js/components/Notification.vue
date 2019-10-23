<template>
    <transition name="modal-fade" mode="out-in">
        <div class="notification notification-primary notification-wrapper" role="alert">
            <div class="notification-container">
                <button type="button"
                        class="btn-close"
                        @click="close"
                        aria-label="Fermer la notification">
                </button>

                <div class="notification-body">
                    <slot name="body">
                        default body
                    </slot>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        name: 'notification',
        data() {
            return {

            }
        },
        created() {

        },
        computed: {

        },
        methods: {
            close() {
                this.$emit('close');
            },
        }
    }
</script>





<style lang="scss" scoped>
    @import '~@/_variables.scss';

    .modal-fade-enter,
    .modal-fade-leave-active {
        opacity: 0;
    }

    .modal-fade-enter-active,
    .modal-fade-leave-active {
        transition: opacity .5s ease
    }

    .notification {
        position: fixed;
        max-width: calc(100% / 2);
    }
</style>
