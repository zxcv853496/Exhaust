<template src = './template.html'></template>

<script>
export default {
  data() {
    return {
      howpay: 1, // 線上付款 || 貨到付款
      selection_pay: 0, //全額 訂金
      cash_active: 0, //付款方式
      installment: 3, //分幾期

      cash_on:[
        {
          id: 1,
          name: '信用卡',
        },
        {
          id: 2,
          name: '信用卡分期',
          installment: [
            '3期',
            '6期',
            '12期',
            '18期',
            '24期',
          ]
        },
        {
          id: 3,
          name: 'WebATM'
        },
        {
          id: 4,
          name: '超商代碼繳費'
        },
        {
          id: 5,
          name: '超商條碼繳費'
        },
      ]
    };
  },
  props:{
    product_data:{
      require: true,
    },
    buy_data: {
      require: true,
    }
  },
  methods: {
    selection_howpay: function (id) {
      this.howpay = id;
    },
    selection_visapay: function (id) {
      this.visapay = id;
    },
    selection_selection_pay: function (id) {
      this.selection_pay = id;
    },
    selection_cash_on: function (id) {
      this.cash_active = id;
    },
    selection_installment: function (id) {
      this.installment = id;
    },
    next_step() {
      this.$emit("pagger-add", 3);
    },
  },
};
</script>

<style>
</style>