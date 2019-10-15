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
        bottom: 0;
        right: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        max-width: calc(100% / 2);
        padding: .75rem 1.25rem;
        margin-right: 2rem;
        margin-bottom: 2rem;
        border: .1rem solid transparent;
        border-radius: 2rem 1rem 3rem 1rem;

        &.notification-primary {
            color: $primary-color-dark;
            background-color: $primary-color-light;
            border-color: $primary-color;
        }

        &.notification-secondary {
            color: $secondary-color-dark;
            background-color: $secondary-color-light;
            border-color: $secondary-color;
        }

        .btn-close {
            -moz-appearance: none;
            -webkit-appearance: none;
            background-color: rgba(10,10,10,.2);
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
            top: .5rem;
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

        .notification-body {
            position: relative;
            padding: 1rem .5rem;
        }
    }
</style>
