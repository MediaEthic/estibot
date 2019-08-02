<template>
    <form @submit.prevent="logout">
        <button type="submit">Déconnexion</button>
    </form>
</template>

<script>
    export default {
        data() {
            return {

            }
        },
        methods: {
            logout () {
                this.$store.dispatch('logout',).then(resp => {
                    this.$router.push({ name: "login" });
                }).catch(error => {
                    this.$router.push({ name: "login" });
                });
            }
        }
    }
</script>

<style lang="scss">
    @import '~@/_variables.scss';


</style>