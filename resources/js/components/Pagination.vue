<template>
    <nav v-if="pagination.last_page > 1"
         class="pagination-container">
        <ul class="wrap-pagination">
            <li class="pagination-item" v-if="!isInFirstPage">
                <button
                    type="button"
                    @click="onClickFirstPage"
                    :disabled="isInFirstPage"
                    class="pagination-controls"
                >
                    <i class="fas fa-angle-double-left"></i>
                </button>
            </li>

            <li class="pagination-item" v-if="!isInFirstPage">
                <button
                    type="button"
                    @click="onClickPreviousPage"
                    :disabled="isInFirstPage"
                    class="pagination-controls"
                >
                    <i class="fas fa-angle-left"></i>
                </button>
            </li>

            <li v-for="page in pages"
                class="pagination-item"
            >
                <button
                    type="button"
                    @click="onClickPage(page.name)"
                    :disabled="page.isDisabled"
                    :class="{ active: isPageActive(page.name) }"
                    class="pagination-controls"
                >
                    {{ page.name }}
                </button>
            </li>

            <li class="pagination-item" v-if="!isInLastPage">
                <button
                    type="button"
                    @click="onClickNextPage"
                    :disabled="isInLastPage"
                    class="pagination-controls"
                >
                    <i class="fas fa-angle-right"></i>
                </button>
            </li>

            <li class="pagination-item" v-if="!isInLastPage">
                <button
                    type="button"
                    @click="onClickLastPage"
                    :disabled="isInLastPage"
                    class="pagination-controls"
                >
                    <i class="fas fa-angle-double-right"></i>
                </button>
            </li>
        </ul>
    </nav>
</template>

<script>
    export default {
        name: "pagination",
        props: {
            maxVisibleButtons: {
                type: Number,
                required: false,
                default: 3
            },
            pagination: {
                type: Object,
                required: true,
                default: () => {}
            },
        },
        data() {
            return {
                pageRequested: 1,
                hasFocus: false
            }
        },
        computed: {
            startPage() {
                // When on the first page
                if (this.pagination.current_page === 1) {
                    console.log("this.pagination.current_page === 1");
                    return 1;
                } else if (this.pagination.current_page === this.pagination.last_page) { // When on the last page
                    console.log("this.pagination.current_page === this.pagination.last_page");
                    return this.pagination.last_page - this.maxVisibleButtons + 1;
                } else { // When in between
                    console.log("this.pagination.current_page - 1");
                    return this.pagination.current_page - 1;
                }
            },
            pages() {
                const range = [];
                for (let i = this.startPage;
                     i <= Math.min(this.startPage + this.maxVisibleButtons - 1, this.pagination.last_page);
                     i+= 1 ) {
                    range.push({
                        name: i,
                        isDisabled: i === this.pagination.current_page
                    });
                }

                return range;
            },
            isInFirstPage() {
                return this.pagination.current_page === 1;
            },
            isInLastPage() {
                return this.pagination.current_page === this.pagination.last_page;
            },
        },
        methods: {
            checkAnimation({ target, animationName }) {
                if(animationName.startsWith("onAutoFillStart")) {
                    target.classList.add("hasValue");
                }
            },
            onClickFirstPage() {
                this.$emit('pagechanged', 1);
            },
            onClickPreviousPage() {
                this.$emit('pagechanged', this.pagination.current_page - 1);
            },
            onClickPage(page) {
                console.log(page);
                this.$emit('pagechanged', page);
            },
            onClickNextPage() {
                this.$emit('pagechanged', this.pagination.current_page + 1);
            },
            onClickLastPage() {
                this.$emit('pagechanged', this.pagination.last_page);
            },
            isPageActive(page) {
                return this.pagination.current_page === page;
            },


            fetchResults(action) {
                let currentPage = this.pagination.current_page;
                if (action === "previousPage") {
                    this.pageRequested = currentPage - 1;
                } else if (action === "nextPage") {
                    this.pageRequested = currentPage + 1;
                } else {
                    this.pageRequested = action;
                }
                this.$emit('fetchResults', parseInt(this.pageRequested));
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';

    .pagination-container {
        width: 100%;

        .wrap-pagination {
            display: flex;
            justify-content: center;
            margin-top: 1rem;

            .pagination-item {
                background-color: $primary-color-light;

                &:first-child {
                    border-top-left-radius: 2rem;
                    border-bottom-left-radius: 2rem;
                }

                &:last-child {
                    border-top-right-radius: 2rem;
                    border-bottom-right-radius: 2rem;
                }

                .pagination-controls {
                    cursor: pointer;
                    padding: 1rem 1.5rem;
                    color: $primary-color;
                    transition: all .4s;

                    &.active {
                        background-color: $primary-color-dark;
                        color: $white;
                        border-radius: 50%;
                    }

                    &:hover {
                        background-color: $primary-color;
                        color: $primary-color-light;
                        border-radius: 50%;
                    }
                }
            }
        }
    }
</style>
