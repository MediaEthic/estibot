<template>
    <nav v-if="pagination.last_page > 1"
         class="wrap-pagination">
        <ul class="list-paginate">
            <li class="paginate controls-paginate"
                :class="[{ disabled: !pagination.previous_page }]">
                <button type="button" class="link-paginate" @click="fetchResults('previousPage')">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </li>
            <li class="paginate">
                Page {{ pagination.current_page }} sur {{ pagination.last_page }}
            </li>
            <li class="paginate controls-paginate"
                :class="[{ disabled: !pagination.next_page }]">
                <button type="button" class="link-paginate" @click="fetchResults('nextPage')">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </li>
        </ul>
    </nav>
</template>

<script>
    export default {
        name: "pagination",
        props: {
            pagination: {
                type: Object,
                required: true,
                default: () => {}
            },
        },
        data() {
            return {

            }
        },
        methods: {
            fetchResults(action) {
                let page = this.pagination.current_page;
                if (action === "previousPage") {
                    page -= 1;
                } else {
                    page += 1;
                }
                this.$emit('fetchResults', parseInt(page));
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '~@/_variables.scss';

    .wrap-pagination {
        display: flex;
        justify-content: center;
        width: 100%;
        margin-top: 1rem;

        .list-paginate {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;

            .controls-paginate {
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 3.5rem;
                height: 3.5rem;
                margin: 0 1rem;
                border: .15rem solid $primary-color;
                border-radius: 50%;
                font-size: 2rem;
                color: $primary-color;
                text-align: center;
                line-height: 3.25rem;
                transition: all .4s;

                &:hover {
                    background-color: rgba($secondary-color, 0.25);
                    border: .15rem solid $secondary-color;
                    color: $secondary-color;

                    .link-paginate {
                        color: $secondary-color;
                    }
                }

                .link-paginate {
                    cursor: pointer;
                    color: inherit;
                }

                &.disabled {
                    display: none;
                }
            }
        }

    }
</style>
