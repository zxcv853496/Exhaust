<template src="./template.html"></template>

<script>
export default {
  props: {
    scooter_data: {
      require: true,
    },
    product_data: {
      require: true,
    },
    power_data: {
      require: true,
    },
    case_data: {
      require: true,
    },
    buy_data: {
      require: true,
    },
    total_price: {
      require: true,
    },
    order_total_price: {
      require: true,
    },
  },
  data() {
    return {
      cash_on: [
        // {
        //   id: 1,
        //   name: '貨到付款',
        // },
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
          name: 'WebATM',
        },
        {
          id: 5,
          name: 'ATM',
        },
        {
          id: 6,
          name: '超商代碼繳費',
        },
        {
          id: 7,
          name: '超商條碼繳費',
        },
      ],
    };
  },
  methods: {
    UpdateData(key, val) {
      let tmp_data = Object.assign({}, this.buy_data.pay);
      tmp_data[key] = val;
      key == 'pay_way' ? (tmp_data.installment = 3) : '';
      key == 'installment'
        ? (tmp_data.percent = this.cash_on[1].installment.filter(
            (item) => item.id == val
          )[0].percent)
        : '';
      key == 'pay_option' && val == '訂金付款'
        ? ((tmp_data.pay_way = '信用卡一次付清'), (tmp_data.installment = 3))
        : '';
      this.$emit('update-buy-data', ['pay', tmp_data]);
    },
    perv_step() {
      this.$emit('scroll-to-top');
      this.$emit('pagger-add', 1);
    },
    next_step() {
      this.$emit('scroll-to-top');
      this.$emit('send-order');
    },
  },
  computed: {
    pay_way_list() {
      let tmp_list = JSON.parse(JSON.stringify(this.cash_on));
      if (this.buy_data.pay.pay_option == '訂金付款') {
        tmp_list.splice(1, 1);
      }
      return tmp_list;
    },
    buy_scooter_price() {
      return this.buy_data.product.scooter == ''
        ? 0
        : this.scooter_data[this.buy_data.product.scooter_brand].filter(
            (item) => item.title == this.buy_data.product.scooter
          )[0].price;
    },
    buy_product_name_price() {
      return this.buy_data.product.title == ''
        ? 0
        : this.product_data.filter(
            (item) => item.name == this.buy_data.product.title
          )[0].price;
    },
    buy_product_case_option_price() {
      if (this.buy_data.product.case_option != '') {
        return this.case_data.filter(
          (item) => item.name == this.buy_data.product.case_option
        )[0].price;
      } else {
        return 0;
      }
    },
    buy_product_power_option_price() {
      if (this.buy_data.product.power_option != '') {
        return this.power_data.filter(
          (item) => item.name == this.buy_data.product.power_option
        )[0].price;
      } else {
        return 0;
      }
    },
    total_price_with_installment() {
      let percent = this.cash_on
        .filter((item) => item.id == 3)[0]
        .installment.filter((item) => item.id == this.installment)[0].percent;
      return this.total_price + Math.ceil(this.total_price * percent);
    },
  },
};
</script>

<style></style>
