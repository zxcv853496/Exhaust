<template src="./template.html"></template>

<script>
export default {
  props: {
    total_price: {
      require: true,
    },
    order_data: {
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
      this.$emit('scroll-to-top');
      this.errors = [];
      if (this.validNotEmpty(this.order_data.address) != true) {
        this.errors.push('address');
      }
      if (this.validNotEmpty(this.order_data.facebook_name) != true) {
        this.errors.push('facebook_name');
      }
      if (this.validName(this.order_data.name) != true) {
        this.errors.push('name');
      }
      if (this.validPhone(this.order_data.phone) != true) {
        this.errors.push('phone');
      }
      if (this.validEmail(this.order_data.email) != true) {
        this.errors.push('email');
      }
      if (this.validNotEmpty(this.order_data.connection) != true) {
        this.errors.push('connection');
      }

      if (this.errors.length <= 0) {
        this.NextStep();
      }
    },
    GetError(val) {
      return this.errors.indexOf(val) != -1;
    },
    UpdateData(key, val) {
      let tmp_data = Object.assign({}, this.order_data);
      tmp_data[key] = val;
      this.$emit('update-buy-data', ['user', tmp_data]);
    },
    perv_step() {
      this.$emit('scroll-to-top');
      this.$emit('pagger-add', 0);
    },
    NextStep() {
      this.$emit('pagger-add', 2);
    },
  },
};
</script>

<style></style>
