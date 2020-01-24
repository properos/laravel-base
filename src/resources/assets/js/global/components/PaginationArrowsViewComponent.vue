<template>
    <ul class="pagination" :class="p_classes">
        <li class="page-item prev" v-bind:class="{'disabled':disable_prev}">
            <a @click="onPrev()" class="page-link">
                <i class="fa fa-chevron-left"></i>
            </a>
        </li>
        <li class="page-item next" v-bind:class="{'disabled':disable_next}">
            <a @click="onNext()" class="page-link">
                <i class="fa fa-chevron-right"></i>
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
    p_classes: {
      type: String,
      default: "float-right"
    }
  },
  data() {
    return {
      disable_prev: false,
      disable_next: false
    };
  },
  watch:{
    pagination(pagination){
      this.init(pagination);
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
    onNext() {
      this.$emit("onNext", "next");
    },
    onPrev() {
      this.$emit("onPrev", "prev");
    }
  },
  mounted() {
    this.init(this.pagination);
  }
};
</script>