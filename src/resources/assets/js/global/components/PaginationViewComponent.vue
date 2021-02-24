<template>
    <ul class="pagination" :class="p_classes">
        <li class="page-item prev" v-bind:class="{'disabled':disable_prev}">
            <a @click="onFirst()" class="page-link">
                «
            </a>
        </li>
        <li class="page-item" v-for="n in pagesNumber" :key="n" v-bind:class="{'active':(n == pagination.current_page)}">
            <a @click="onShowPage(n)" class="page-link">{{n}}</a>
        </li>
        <li class="page-item next" v-bind:class="{'disabled':disable_next}">
            <a @click="onLast()" class="page-link">
                »
            </a>
        </li>
    </ul>
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
            default: 2
        },
        p_classes: {
            type: String,
            default: "float-right"
        }
    },
    data() {
        return {
            disable_prev: false,
            disable_next: false,
            min: 0,
            max: 0
        };
    },
    watch:{
        pagination(pagination){
            this.init(pagination);
        }
    },
    computed: {
        pagesNumber() {
            let from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }
            let to = from + this.offset*2;
            if (to >= this.pagination.pages) {
                to = this.pagination.pages;
                from = (to - this.offset*2) > 1 ?(to - this.offset*2):1;
            }

            let pagesArray = [];
            for (let page = from; page <= to; page++) {
                pagesArray.push(page);
            }
            return pagesArray;
        }
    },
    methods: {
        init(pagination) {
            this.disable_prev = false;
            this.disable_next = false;
            if (pagination.current_page === 1) {
                this.disable_prev = true;
            }
            if (
                pagination.pages === pagination.current_page ||
                pagination.pages === 0
            ) {
                this.disable_next = true;
            }
        },
        onFirst() {
            this.$emit("onFirst", "first");
        },
        onLast() {
            this.$emit("onLast", "last");
        },
        onShowPage(page) {
            this.$emit("onShowPage", page);
        }
    },
    mounted() {
        this.init(this.pagination);
    }
};
</script>