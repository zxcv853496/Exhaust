<template src="./template.html"></template>

<script>
export default {
  props: {
    product_data: {
      require: true,
    },
    power_data: {
      require: true,
    },
    case_data: {
      require: true,
    },
    scooter_data: {
      require: true,
    },
    order_data: {
      require: true,
    },
    total_price: {
      require: true,
    },
  },
  data() {
    return {
      errors: [],
    };
  },
  methods: {
    DataVaild() {
      this.errors = [];
      if (this.order_data.title == '') {
        this.errors.push('title');
      }
      if (this.order_data.scooter_brand == '') {
        this.errors.push('scooter_brand');
      }
      if (this.order_data.scooter == '') {
        this.errors.push('scooter');
      }
      if (this.order_data.power_option == '') {
        this.errors.push('power_option');
      }
      if (this.order_data.case_option == '') {
        this.errors.push('case_option');
      }

      if (this.errors.length <= 0) {
        this.NextStep();
      }
    },
    GetError(val){
      return this.errors.indexOf(val)!=-1
    },
    UpdateData(key, val) {
      let tmp_data = Object.assign({}, this.order_data);
      console.log(tmp_data);
      tmp_data[key] = val;
      key == 'scooter_brand' ? (tmp_data.scooter = '') : '';
      console.log(tmp_data);
      this.$emit('update-buy-data', ['product', tmp_data]);
    },
    NextStep() {
      this.$emit('scroll-to-top');
      this.$emit('pagger-add', 1);
    },
  },
};
</script>
