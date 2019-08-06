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

        <main>
            <form @submit.prevent="login" class="main-form" autocomplete="off">
                <fieldset>
                    <legend class="page-main-title mobile-hidden">Accès membre</legend>
                    <p class="baseline-main-title mobile-hidden">Entrez vos identifiants et poursuivez l’aventure parmi nous...</p>

                    <div class="wrap-form-main">
                        <div class="wrap-field validate-field">
                            <input v-model.trim="form.email"
                                   class="field "
                                   :class="{ hasValue: form.email }"
                                   name="email"
                                   type="email"
                                   @animationstart="checkAnimation"
                                   autofocus
                                   required>
                            <span class="focus-field"></span>
                            <label class="label-field">Adresse e-mail</label>
                            <span class="symbol-left-field"><i class="fas fa-at"></i></span>
                        </div>

                        <div class="wrap-field">
                            <span class="btn-right-field" @click="switchVisibility">
                                <i :class="this.passwordFieldType === 'password' ? 'far fa-eye' : 'far fa-eye-slash'"></i>
                            </span>
                            <input v-model.trim="form.password"
                                   class="field"
                                   :class="{ hasValue: form.password }"
                                   name="password"
                                   :type="passwordFieldType"
                                   @animationstart="checkAnimation"
                                   required>

                            <span class="focus-field"></span>
                            <label class="label-field">Mot de passe</label>
                            <span class="symbol-left-field"><i class="fas fa-lock"></i></span>
                        </div>

                        <div class="info info-error" v-if="infoError">Mauvais identifiant et/ou mot de passe.</div>

                        <button type="submit" class="wrap-button-submit">
                            <a href="#" class="cta">
                                <span>Connexion</span>
                                <svg width="13px" height="10px" viewBox="0 0 13 10">
                                    <path d="M1,5 L11,5"></path>
                                    <polyline points="8 1 12 5 8 9"></polyline>
                                </svg>
                            </a>
                        </button>
                    </div>
                </fieldset>
            </form>
        </main>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    email: '',
                    password: '',
                },
                infoError: false,
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
                this.infoError = false;
                this.$store.dispatch('login', {
                    email: this.form.email,
                    password: this.form.password,
                }).then(resp => {
                    this.$router.push({ name: "home" });
                }).catch(error => {
                    this.infoError = true;
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

            .wrap-button-submit {
                display: block;
                margin: 3rem auto 0 auto;
                border: 0;
                background: transparent;
            }
        }
    }

    :-webkit-autofill {
        animation-name: onAutoFillStart;
    }
    :not(:-webkit-autofill) {
        animation-name: onAutoFillCancel;
    }
    @keyframes onAutoFillStart {
        from {
        }
        to {
        }
    }
    @keyframes onAutoFillCancel {
        from {
        }
        to {
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
                border-radius: 2rem 0 3rem;
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