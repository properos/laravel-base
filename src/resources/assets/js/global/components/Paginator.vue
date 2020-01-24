<template>
    <nav aria-label="Page navigation mb-3">
        <ul class="pagination justify-content-center">
            <li class="page-item">
                <a v-on:click.prevent="changePage(pagination.current_page - 1)" class="page-link" :class="{'disable-button' : pagination.current_page <= 1}"
                    aria-label="Previous" style="cursor: pointer;">
                    <span aria-hidden="true">« Prev</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
            <li v-for="(page, index) in pagesNumber" v-on:click.prevent="changePage(page)" class="page-item" :class="{'active' : page == pagination.current_page}" :key="index">
                <a class="page-link" style="cursor: pointer;">{{ page }}</a>
            </li>
            <li class="page-item">
                <a class="page-link" :class="{'disable-button': pagination.current_page >= pagination.last_page}" v-on:click.prevent="changePage(pagination.current_page + 1)"
                    aria-label="Next" style="cursor: pointer;">
                    <span aria-hidden="true">Next »</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
        </ul>
    </nav>
</template>
<script>
    export default {
        props: {
            pagination: {
                type: Object,
                required: true
            },
            offset: {
                type: Number,
                default: 5
            }
        },
        computed: {
            pagesNumber() {
                if (!this.pagination.to) {
                    return [];
                }
                let from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }
                let to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }
                let pagesArray = [];
                for (let page = from; page <= to; page++) {
                    pagesArray.push(page);
                }
                return pagesArray;

            }
        },
        methods: {
            changePage(page) {
                this.pagination.current_page = page;
                this.$emit('paginate');
            }
        },
        mounted() {
            /* console.log(this.pagesNumber); */
        }
    }
</script>
<style>
    .pagination-button {
        width: 28px;
        height: 28px;
        text-align: center;
        cursor: pointer;
        margin-right: 2px;
        font-size: 10px;
        font-weight: bold;
    }

    .disable-button {
        pointer-events: none;
        border-color: rgb(196, 197, 197) !important;
        color: rgb(80, 80, 80) !important;
        font-weight: bold;
        background-color: rgb(229, 230, 231) !important;
        cursor: not-allowed;
    }

    .disable-arrow {
        color: gray !important;
    }

    .center {
        margin: auto;
        width: 60%;
        padding: 10px;
    }

    @media (min-width: 576px) {
        .center {
            margin: auto;
            width: 35%;
            padding: 10px;
        }
    }

    @media (min-width: 768px) {
        .center {
            margin: auto;
            width: 25%;
            padding: 10px;
        }
    }

    @media (min-width: 992px) {
        .center {
            margin: auto;
            width: 25%;
            padding: 10px;
        }
    }

    @media (min-width: 1200px) {
        .center {
            margin: auto;
            width: 30%;
            padding: 10px;
        }
    }
</style>