<template src='./template.html'></template>

<script>
export default {
  props: {
    product_data: {
      require: true,
    },
  },
  data() {
    return {
      product: 0,
      accessory: 0,
      setting: 0,
      model:"",//輸入車種
    };
  },
  methods: {
    product_select: function (id) {
      this.product = id;
    },
    option_select: function (id) {
      this.accessory = id;
    },
    setting_select: function (id) {
      this.setting = id;
    },
    next_step(){
        let buy_data = {
          category:this.product-1,
          power_option:this.accessory-1,
          case_option:this.setting-1,
          model:this.model,
        }
        this.$emit("update-buy-data",["product",buy_data])
        this.$emit("pagger-add",1)
    }
  },
};
</script>
