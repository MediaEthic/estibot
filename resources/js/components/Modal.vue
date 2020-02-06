<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container" :style="{ backgroundImage: 'url(/assets/img/undraw_mail_box_kd5i.svg)' }">
                    <button type="button"
                            class="btn-close"
                            aria-label="Fermer la fenêtre modale"
                            @click="$emit('close')">
                    </button>

                    <div class="modal-header">
                        <slot name="header">
                            default header
                        </slot>
                    </div>

                    <div class="modal-body">
                        <slot name="body">
                            default body
                        </slot>
                    </div>

                    <div class="modal-footer">
                        <slot name="footer">
                            default footer
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        data() {
            return {

            }
        },
        created() {
            // let textareaList = document.getElementsByTagName("textarea");
            // for(let i = 0; i < textareaList.length; i++) {
            //     let el = textareaList[i];
            //     setTimeout(() => {
            //         el.style.cssText = 'height:auto !important; padding:0 !important;';
            //         let scrollHeight = el.scrollHeight + 10;
            //         el.style.cssText = 'height:' + scrollHeight + 'px !important; ';
            //         if (el.value === "") {
            //             el.style.cssText = 'height:100% !important; ';
            //         }
            //     }, 0);
            // }
        },
        mounted() {

        },
        computed: {

        },
        methods: {

        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';
</style>
