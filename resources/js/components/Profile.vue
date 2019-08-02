<template>
    <form @submit.prevent="login">
        <div class="page-title-primary">Ethic Estibot</div>
        <h1 class="baseline-title-primary">Solution de devis simple et rapide</h1>

        <div class="info info-error" v-if="infoError">La connexion a échoué. Veuillez réessayer.</div>

        <div>
            <div class="wrap-field validate-field">
                <input v-model.trim="email"
                       class="field"
                       name="email"
                       type="email"
                       required>
                <span class="focus-field"></span>
                <span class="label-field">Adresse e-mail</span>
            </div>

            <div class="wrap-field">
                <input v-model.trim="password"
                       class="field"
                       name="password"
                       type="password"
                       placeholder="Password"
                       required>
            </div>

            <div class="form-block form__actions">
                <button class="button-submit">Connexion</button>
            </div>
        </div>
    </form>
</template>

<script>
    export default {
        data() {
            return {
                infoError: false,
                email: '',
                password: ''
            }
        },
        beforeCreate () {
            // if (store.state.isLogged) {
            //     router.push('/')
            // }
        },
        methods: {
            login () {
                // this.infoError = false;
                // this.$http.post('https://your-api-url.com/login', {
                //     email: this.email,
                //     password: this.password
                // }).then((response) => {
                //     localStorage.setItem('token', response.body.token);
                //     store.commit('LOGIN_USER')
                //     router.push('/')
                // }, () => {
                //     this.infoError = true;
                //     this.password = '';
                // })
            }
        }
    }
</script>

<style lang="scss">
    @import '~@/_variables.scss';

    h1 {
    }
</style>