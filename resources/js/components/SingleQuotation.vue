<template>
    <div>
        {{ quotation }}}
    </div>
</template>

<script>
    export default {
        data() {
            return {

            }
        },
        created() {
            console.log(this.$route.params.id);
            this.$store.dispatch('getQuotation', {
                id: this.$route.params.id
            });
        },
        computed: {
            quotation() {
                console.log(this.$store.state.quotation);
                return this.$store.state.quotation;
            }
        },
        methods: {
            clearSearch() {
                this.search = "";
            },
            getHumanDate(date) {
                return moment(date, 'YYYY-MM-DD').format('DD/MM/YYYY');
            },
        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';

</style>
