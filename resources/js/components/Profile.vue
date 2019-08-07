<template>
    <div class="wrap-component">
        <header class="wrap-main-header">
            <h1 class="page-main-title">Compte utilisateur</h1>
            <!--<div class="tag tag-info">{{ quotations.total }}</div>-->
        </header>

        <main class="wrap-main-content">
            <div class="wrap-image">
                <img class="main-image"
                     src="/assets/img/undraw_profile_6l1l.svg"
                     alt="Illustration de la page de connexion"/>
            </div>

            <!--<h2 class="page-subtitle">Bonjour {{ user }}</h2>-->
            <!--<p class="baseline-main-title">Commencez par créer un nouveau devis.</p>-->

            <!--<div class="wrap-field h-50">-->
                    <!--<span v-if="search" class="btn-right-field" @click="clearSearch">-->
                        <!--<i class="fas fa-times"></i>-->
                    <!--</span>-->
                <!--<input v-model.trim="search"-->
                       <!--class="field"-->
                       <!--:class="{ hasValue: search }"-->
                       <!--name="search"-->
                       <!--type="search"-->
                       <!--autocomplete="off">-->

                <!--<span class="focus-field"></span>-->
                <!--<label class="label-field">Rechercher un client</label>-->
                <!--<span class="symbol-left-field"><i class="fas fa-search"></i></span>-->
            <!--</div>-->

            <form @submit.prevent="logout">
                <button type="submit" class="wrap-button-submit">
                    <a href="#" class="cta">
                        <span>Déconnexion</span>
                        <svg width="13px" height="10px" viewBox="0 0 13 10">
                            <path d="M1,5 L11,5"></path>
                            <polyline points="8 1 12 5 8 9"></polyline>
                        </svg>
                    </a>
                </button>
            </form>
        </main>
    </div>
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

<style lang="scss" scoped>
    @import '~@/_variables.scss';

    .wrap-component {
        min-height: calc(100vh - 9rem);
    }

    .wrap-main-content {
        display: flex;
        flex-flow: row wrap;
        justify-content: center;
        align-content: space-around;
        min-height: calc(100vh - 12.4rem);

        .wrap-image {
            width: 100%;
            margin-top: 3rem;

            .main-image {
                width: 100%;
            }
        }
    }
</style>