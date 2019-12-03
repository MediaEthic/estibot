<template>
    <div class="wrap-full-content no-footer">
        <div class="wrap-illustration">
            <header class="wrap-main-header">
                <h2 class="page-main-title">Ethic Estibot</h2>
                <h1 class="baseline-main-title">Solution de devis simple et rapide</h1>
            </header>

            <div class="wrap-image">
                <img class="main-image"
                     src="/assets/img/undraw_Artificial_intelligence_oyxx.svg"
                     alt="Illustration de la page de connexion"/>
            </div>
        </div>

        <ValidationObserver tag="main" v-slot="{ invalid, passes }">
            <form @submit.prevent="passes(login)" class="main-form" autocomplete="off">
                <fieldset>
                    <legend class="page-main-title mobile-hidden">Accès membre</legend>
                    <p class="baseline-main-title mobile-hidden">Entrez vos identifiants et poursuivez l’aventure parmi nous...</p>

                    <div class="wrap-form-main">
                        <transition name="fade">
                            <div v-if="serverError" class="notification notification-secondary notification-wrapper" role="alert">
                                <div class="notification-container">
                                    <div class="notification-body">
                                        {{ serverError[0] }}
                                    </div>
                                </div>
                            </div>
                        </transition>

                        <ValidationProvider class="wrap-field"
                                            name="username"
                                            v-slot="{ errors }">
                            <input v-model.trim="form.username"
                                   class="field"
                                   :class="{ 'hasValue': form.username, 'input-error': errors[0] }"
                                   type="text"
                                   @animationstart="checkAnimation"
                                   autofocus
                                   required>
                            <span class="focus-field"></span>
                            <label class="label-field">Identifiant</label>
                            <span class="symbol-left-field"><i class="fas fa-at"></i></span>
                            <span class="v-validate">{{ errors[0] }}</span>
                        </ValidationProvider>

                        <ValidationProvider class="wrap-field"
                                            name="password"
                                            v-slot="{ errors }">
                            <span class="btn-right-field" @click="switchVisibility">
                                <i :class="passwordFieldType === 'password' ? 'far fa-eye' : 'far fa-eye-slash'"></i>
                            </span>
                            <input v-model.trim="form.password"
                                   class="field"
                                   :class="{ 'hasValue': form.password, 'input-error': errors[0] }"
                                   :type="passwordFieldType"
                                   @animationstart="checkAnimation"
                                   required>

                            <span class="focus-field"></span>
                            <label class="label-field">Mot de passe</label>
                            <span class="symbol-left-field"><i class="fas fa-lock"></i></span>
                            <span class="v-validate">{{ errors[0] }}</span>
                        </ValidationProvider>

                        <button :disabled="invalid" type="submit" class="wrap-button-submit">
                            <a href="#" class="cta" v-if="!loading">
                                <span>Connexion</span>
                                <svg width="13px" height="10px" viewBox="0 0 13 10">
                                    <path d="M1,5 L11,5"></path>
                                    <polyline points="8 1 12 5 8 9"></polyline>
                                </svg>
                            </a>
                            <div v-else class="lds-ring"><div></div><div></div><div></div><div></div></div>
                        </button>
                    </div>
                </fieldset>
            </form>
        </ValidationObserver>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                loading: false,
                form: {
                    username: '',
                    password: '',
                },
                serverError: "",
                passwordFieldType: 'password',
            }
        },
        methods: {
            checkAnimation({ target, animationName }) {
                if(animationName.startsWith("onAutoFillStart")) {
                    target.classList.add("hasValue");
                }
            },
            switchVisibility() {
                this.passwordFieldType = this.passwordFieldType === "password" ? "text" : "password";
            },
            login () {
                this.loading = true;
                this.$store.dispatch('login', {
                    username: this.form.username,
                    password: this.form.password,
                }).then(resp => {
                    this.loading = false;
                    this.$router.push({ name: "quotations.index" });
                }).catch(error => {
                    this.loading = false;
                    this.serverError = error.response.data;
                    this.form.password = "";
                });
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';

    .wrap-full-content {
        width: 100%;
        display: flex;
        flex-flow: column wrap;

        .wrap-illustration {
            display: flex;
            flex-flow: column wrap;
            justify-content: space-around;

            .wrap-main-header {
                order: 2;
                width: 100%;
                margin: 3rem 0;
            }

            .wrap-image {
                order: 1;
                width: 100%;
                max-height: calc(100vh - 32.7rem);

                .main-image {
                    width: 100%;
                    max-height: calc(100vh - 32.7rem);
                }
            }
        }

        .main-form {
            width: 100%;
        }
    }

    // Small devices (landscape phones, 576px and up)
    @media (min-width: 576px) {  }

    // Medium devices (tablets, 768px and up)
    @media (min-width: 768px) {  }

    // Large devices (desktops, 992px and up)
    @media (min-width: 992px) {  }

    // Extra large devices (large desktops, 1200px and up)
    @media (min-width: 1200px) {  }

    @media (max-width: 321px) and (orientation: portrait) {
        .wrap-illustration {
            .wrap-main-header {
                margin: 1rem 0 !important;
            }
        }
    }

    @media (min-width: 680px) {
        .wrap-full-content {
            .wrap-illustration {
                .wrap-main-header {
                    order: 1;

                    .page-main-title {
                        font-size: 5rem;
                        line-height: 5.4rem;
                    }

                    .baseline-main-title {
                        font-size: 2rem;
                        line-height: 2.4rem;
                    }
                }

                .wrap-image {
                    order: 2;
                }
            }

            .main-form {
                margin-top: 3rem;
                padding: 5rem 4.5rem;
                border-radius: 2rem 1rem 3rem 1rem;
                box-shadow: 0 0 1rem rgba($secondary-color, 0.25);

                .wrap-form-main {
                    margin-top: 3rem;
                }
            }
        }
    }

    @media (min-width: 680px) and (orientation: portrait) {
        .wrap-full-content {
            align-items: center;
            justify-content: space-around;
            padding: 3rem 5rem;

            .wrap-illustration {
                .wrap-main-header {
                    text-align: center;
                    margin-top: 0;
                }
            }
        }
    }

    @media (min-width: 680px) and (orientation: landscape) {
        .wrap-full-content {
            flex-flow: row nowrap;
            justify-content: space-around;
            padding: 5rem 7rem;

            .wrap-illustration {
                .wrap-image {
                    max-width: 50rem;
                }
            }

            .main-form {
                width: 45rem;
                margin-left: 10rem;
            }
        }
    }
</style>
