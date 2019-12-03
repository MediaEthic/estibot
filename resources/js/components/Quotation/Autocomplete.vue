<template>
    <ValidationProvider class="wrap-field h-50"
                        name="name"
                        v-slot="{ errors }">
        <span class="btn-right-field" v-if="isLoading">
            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
        </span>
        <input
            type="text"
            v-model="search"
            @input="onChange"
            @keydown.down="onArrowDown"
            @keydown.up="onArrowUp"
            @keydown.enter="onEnter"
            class="field"
            :class="[{ hasValue: search }]"
            autocomplete="off"
            @keydown.shift.tab.prevent=""
            ref="search">
        <span class="focus-field"></span>
        <label class="label-field"> {{ label }}</label>
        <span class="symbol-left-field"><i class="fas fa-user-tie"></i></span>
        <span class="v-validate">{{ errors[0] }}</span>
        <div v-show="isOpen"
             class="wrap-autocomplete-results">
            <ul class="list-autocomplete-results">
                <li v-if="isLoading"
                    class="item-autocomplete-result">
                    Loading results...
                </li>
                <li v-else
                    v-for="(suggestion, i) in matches"
                    :key="i"
                    @click="setResult(suggestion)"
                    class="item-autocomplete-result"
                    :class="{ 'is-active': i === arrowCounter }"
                >
                    {{ suggestion.name }}
                </li>
            </ul>
        </div>
    </ValidationProvider>
</template>

<script>
    import Loader from '../Loader';

    export default {
        name: "autocomplete",
        components: {
            Loader
        },
        props: {
            label: {
                type: String,
                required: true,
                default: "Nom"
            },
            focus: {
                type: Boolean,
                required: false,
                default: false
            },
            items: {
                type: Array,
                required: false,
                default: () => []
            },
            isAsync: {
                type: Boolean,
                required: false,
                default: false
            }
        },
        data() {
            return {
                isOpen: false,
                matches: [],
                search: "",
                hasValue: false,
                isLoading: false,
                arrowCounter: -1,
            }
        },
        mounted() {
            if (this.focus) {
                this.$refs.search.focus();
            }
            document.addEventListener('click', this.handleClickOutside);
        },
        watch: {
            items(value, oldValue) {
                if (this.isAsync) {
                    this.matches = value;
                    this.isOpen = true;
                    this.isLoading = false;
                }
            }
        },
        methods: {
            handleClickOutside(evt) {
                if (!this.$el.contains(evt.target)) {
                    this.isOpen = false;
                    this.arrowCounter = -1;
                }
            },
            onArrowDown() {
                if (this.arrowCounter < this.matches.length) {
                    this.arrowCounter = this.arrowCounter + 1;
                }
            },
            onArrowUp() {
                if (this.arrowCounter > 0) {
                    this.arrowCounter = this.arrowCounter - 1;
                }
            },
            onEnter() {
                this.search = this.matches[this.arrowCounter];
                this.isOpen = false;
                this.arrowCounter = -1;
            },
            onChange() {
                if (this.search.length > 2) {
                    this.$emit('search', this.search);

                    if (this.isAsync) {
                        this.isLoading = true;
                    } else {
                        this.isOpen = true;
                        this.filterResults();
                    }
                }
            },
            filterResults() {
                this.matches = this.items.filter(item => item.toUpperCase().indexOf(this.search.toUpperCase()) > -1);
            },
            setResult(result) {
                this.$emit('input', result.name);
                this.search = result.name;
                this.isOpen = false;
            }
        },
        destroyed() {
            document.removeEventListener('click', this.handleClickOutside);
        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';

    .wrap-autocomplete-results {
        position: absolute;
        top: 5rem;
        width: 100%;
        border-radius: 1rem;
        box-shadow: 0 .5rem 1rem -.125rem rgba($primary-color-dark, .1), 0 0 0 .1rem rgba($primary-color-dark, .2);
        font-size: 1rem;
        background-color: $white;

        .list-autocomplete-results {
            .item-autocomplete-result {
                color: $grey-dark;
                cursor: pointer;
                display: flex;
                justify-content: flex-start;
                align-items: center;
                padding: .5rem .75rem;

                &.is-active,
                &:hover {
                    background-color: rgba($primary-color-dark, .2);
                }

                &:not(:last-child) {
                    border-bottom: .1rem solid $primary-color-light;
                }
            }
        }
    }
</style>
