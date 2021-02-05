<template src = './template.html'></template>

<script>
export default {
  props: {
    product_data: {
      require: true,
    },
    buy_data: {
      require: true,
    },
    total_price: {
      require: true
    }
  },
  data() {
    return {
      pay_way: 1,
      pay_option: "全額付款",
      installment: 3,
      total: 0,
      cash_on: [
        {
          id: 1,
          name: '貨到付款',
        },
        {
          id: 2,
          name: '信用卡一次付清',
        },
        {
          id: 3,
          name: '信用卡分期付款',
          installment: [
            { id: 3, name: '3期', percent: 0.03 },
            { id: 6, name: '6期', percent: 0.035 },
            { id: 12, name: '12期', percent: 0.07 },
            { id: 18, name: '18期', percent: 0.09 },
            { id: 24, name: '24期', percent: 0.12 },
          ],
        },
        {
          id: 4,
          name: 'WebATM'
        },
        {
          id: 5,
          name: 'ATM'
        },
        {
          id: 6,
          name: '超商代碼繳費'
        },
        {
          id: 7,
          name: '超商條碼繳費'
        },
      ],
    };
  },
  methods: {
    SetData(data) {
      this.pay_way = this.cash_on.filter(item => item.name == data.pay_way)[0].id
      this.pay_option = data.pay_option
      this.installment = data.installment
    },
    UpdateData() {
      let pay_data = {
        pay_way: this.cash_on.filter(item => item.id == this.pay_way)[0].name,
        pay_option: this.pay_option,
        installment: this.installment,
        percent: this.cash_on.filter(item => item.id == 3)[0].installment.filter(item => item.id == this.installment)[0].percent
      }
      this.$emit("update-buy-data", ["pay", pay_data])
    },
    perv_step() {
      this.$emit("scroll-to-top")
      this.UpdateData()
      this.$emit("pagger-add", 1);
    },
    next_step() {
      this.$emit("scroll-to-top") 
      this.UpdateData()
      this.$emit("send-order")
    }
  },
  watch: {
    pay_option() {
      if (this.pay_option == '訂金付款') {
        this.pay_way = 2
      }
      else {
        this.pay_way = 1
      }
    }
  },
  computed: {
    buy_product_name() {
      return this.product_data.products.filter(item => item.id == this.buy_data.product.category)[0].name
    },
    buy_product_name_price() {
      return this.product_data.products.filter(item => item.id == this.buy_data.product.category)[0].price
    },
    buy_product_case_option() {
      return this.product_data.case_option.filter(item => item.id == this.buy_data.product.case_option)[0].name
    },
    buy_product_case_option_price() {
      return this.product_data.case_option.filter(item => item.id == this.buy_data.product.case_option)[0].price
    },
    buy_product_power_option() {
      return this.product_data.power_option.filter(item => item.id == this.buy_data.product.power_option)[0].name
    },
    buy_product_power_option_price() {
      return this.product_data.power_option.filter(item => item.id == this.buy_data.product.power_option)[0].price
    },
    total_price_with_installment() {
      let percent = this.cash_on.filter(item => item.id == 3)[0].installment.filter(item => item.id == this.installment)[0].percent
      return this.total_price + Math.ceil(this.total_price * percent)
    }
  }
};
</script>

<style>
</style>