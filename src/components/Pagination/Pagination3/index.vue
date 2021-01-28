<template src = './template.html'></template>

<script>
export default {
  data() {
    return {
      howpay: 1, // 線上付款 || 貨到付款
      selection_pay: 0, //全額 訂金
      cash_active: 0, //付款方式
      installment: 3, //分幾期
      total: 0,
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
      ], 
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
    buy_total(){
      return this.product_data.products[this.buy_data.product.category].price + this.product_data.case_option[this.buy_data.product.case_option].price + this.product_data.power_option[this.buy_data.product.power_option].price
    },
  },
  computed:{
    buy_product_name(){
      return this.product_data.products[this.buy_data.product.category].name
    },
    buy_product_name_price(){
      return this.product_data.products[this.buy_data.product.category].price
    },
    buy_product_case_option(){
      return this.product_data.case_option[this.buy_data.product.case_option].name
    },
    buy_product_case_option_price(){
      return this.product_data.case_option[this.buy_data.product.case_option].price
    },
    buy_product_power_option(){
      return this.product_data.power_option[this.buy_data.product.power_option].name
    },
    buy_product_power_option_price(){
      return this.product_data.power_option[this.buy_data.product.power_option].price
    },
  }
};
</script>

<style>
</style>