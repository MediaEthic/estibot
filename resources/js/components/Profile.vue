<template>
<!--    <div v-if="windowWidth >= 576" class="wrap-main-navigation">-->

<!--    </div>-->
    <div class="wrap-component">
        <Loader v-if="isLoading" />

        <div class="wrap-head-page">
            <header class="wrap-main-header">
                <h1 class="page-main-title">Compte utilisateur</h1>
            </header>
        </div>

        <main class="wrap-main-content">
            <div class="wrap-image">
                <img v-if="company.logo"
                     class="main-image"
                     :src="`/assets/img/${company.logo}`"
                     alt="Illustration de la page de connexion"/>
                <img v-else
                     class="main-image"
                     src="/assets/img/undraw_profile_6l1l.svg"
                     alt="Illustration de la page de connexion"/>
            </div>

            <div class="wrap-central">
                <ValidationObserver class="left-part" tag="div" v-slot="{ invalid, passes }">
                    <form class="wrap-main-form" enctype="multipart/form-data" @submit.prevent="passes(saveUser)">
                        <fieldset>

                            <h2 class="page-subtitle">Paramètres utilisateur</h2>
                            <p class="baseline-main-title">{{ user.name }} {{ user.surname }}</p>

                            <section>
                                <ImageUploader :multiple="false"
                                               :label="`Glissez votre signature ici`"
                                               v-on:files="setLogo" />
                            </section>
                        </fieldset>
                    </form>
                </ValidationObserver>

                <div class="right-part" v-if="user.admin">
                    <ValidationObserver tag="div" v-slot="{ invalid, passes }">
                        <form enctype="multipart/form-data" @submit.prevent="passes(saveCompany)">
                            <fieldset>
                                <h2 class="page-subtitle">Paramètres entreprise</h2>
                                <ImageUploader :multiple="false"
                                               :label="`Glissez votre logo ici`"
                                               v-on:files="setLogo" />
                                <ValidationProvider tag="div"
                                                    class="wrap-field h-50"
                                                    rules="required"
                                                    name="winder"
                                                    v-slot="{ errors }">
                                    <span class="btn-right-field" v-if="workstationsAreLoading">
                                        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                    </span>
                                    <select v-model="company.winder"
                                            @focus="form.hasFocus = true"
                                            @blur="form.hasFocus = false"
                                            @animationstart="checkAnimation"
                                            class="field select"
                                            :class="{ hasValue: company.winder, 'input-error': errors[0] }"
                                            required>
                                        <option value="null">Choisir un poste</option>
                                        <option v-for="workstation in workstations"
                                                v-bind:value="workstation.id">
                                            {{ workstation.id }} - {{ workstation.name }}
                                        </option>
                                    </select>
                                    <span class="focus-field"></span>
                                    <label class="label-field">Bobineuse</label>
                                    <span class="symbol-left-field"><i class="fas fa-tape"></i></span>
                                    <span class="v-validate">{{ errors[0] }}</span>
                                </ValidationProvider>

                                <ValidationProvider tag="div"
                                                    class="wrap-field h-50"
                                                    name="prepress"
                                                    v-slot="{ errors }">
                                    <span class="btn-right-field" v-if="workstationsAreLoading">
                                        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                                    </span>
                                    <select v-model="company.prepress"
                                            @focus="form.hasFocus = true"
                                            @blur="form.hasFocus = false"
                                            @animationstart="checkAnimation"
                                            class="field select"
                                            :class="{ hasValue: company.prepress, 'input-error': errors[0] }">
                                        <option value="null">Choisir un poste</option>
                                        <option v-for="workstation in workstations"
                                                v-bind:value="workstation.id">
                                            {{ workstation.id }} - {{ workstation.name }}
                                        </option>
                                    </select>
                                    <span class="focus-field"></span>
                                    <label class="label-field">Prépresse</label>
                                    <span class="symbol-left-field"><i class="fas fa-pen-nib"></i></span>
                                    <span class="v-validate">{{ errors[0] }}</span>
                                </ValidationProvider>

                                <button type="submit" :disabled="invalid"
                                        class="button button-small button-primary"
                                        style="margin-left: auto">
                                    Sauvegarder
                                    <i class="fas fa-save"></i>
                                </button>
                            </fieldset>
                        </form>
                    </ValidationObserver>

                    <ValidationObserver tag="div" v-slot="{ invalid, passes }">
                        <form enctype="multipart/form-data" @submit.prevent="passes(saveCompany)" v-if="user.admin">
                        </form>
                    </ValidationObserver>
                </div>
            </div>
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
    import Loader from './Loader';
    import ImageUploader from './ImageUploader';

    export default {
        components: {
            Loader,
            ImageUploader,
        },
        props: {
            user: {
                type: Object,
                required: false,
                default: () => {}
            },
        },
        data() {
            return {
                isLoading: false,
                workstationsAreLoading: false,
                workstations: [],
                form: {
                    logo: [],
                    hasFocus: false,
                }
            }
        },
        created() {
            if (this.user.admin) {
                this.isLoading = true;
                this.$store.dispatch("getCompany").then(res => {
                    console.log(res.data);
                    this.workstations = res.data;
                    this.isLoading = false;
                }).catch(() => {
                    this.isLoading = false;
                });

                this.workstationsAreLoading = true;
                this.$store.dispatch("getWorkstations").then(res => {
                    console.log(res.data);
                    this.workstations = res.data;
                    this.workstationsAreLoading = false;
                }).catch(() => {
                    this.workstationsAreLoading = false;
                });
            }
        },
        computed: {
            windowWidth() {
                return this.$store.state.windowWidth;
            },
            company() {
                return this.$store.state.company;
            },
            // user() {
            //     return this.$store.state.user;
            // },
        },
        methods: {
            checkAnimation({ target, animationName }) {
                if (animationName.startsWith("onAutoFillStart")) {
                    target.classList.add("hasValue");
                }
            },
            setLogo(files) {
                this.form.logo = files;
            },
            saveUser() {

            },
            saveCompany() {
                let formData = new FormData();
                formData.append('prepress', this.company.prepress);
                formData.append('winder', this.company.winder);
                this.form.logo.forEach(file => {
                    formData.append('images[]', file, file.name);
                    console.log(file.name);
                });

                this.$store.dispatch('saveCompany', {
                    formData: formData,
                }).then(resp => {
                    this.$toast.success({
                        title: "Paramètres enregistrés",
                        message: "Les paramètres ont bien été modifiés"
                    });
                }).catch(error => {
                    this.$toast.error({
                        title: "Erreur",
                        message: "Oups, un problème est survenu pour modifier les paramètres"
                    });
                });
            },
            logout() {
                this.$store.dispatch('logout').then(resp => {
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

    .wrap-main-content {
        .wrap-image {
            width: 100%;
            margin: 2rem auto;
            text-align: center;

            .main-image {
                width: 15rem;
            }
        }
    }

    @media (min-width: 680px) {
        .wrap-main-content {
            .wrap-image {
                text-align: left;
            }
        }
    }
</style>
