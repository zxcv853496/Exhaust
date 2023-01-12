<template src="./template.html"></template>

<script>
import Pagination1 from '../Pagination/Pagination1/index';
import Pagination2 from '../Pagination/Pagination2/index';
import Pagination3 from '../Pagination/Pagination3/index';
import product_data from '@/assets/config/product.json';
import power_data from '@/assets/config/power_option.json';
import case_data from '@/assets/config/case_option.json';
import scooter_data from '@/assets/config/scooter.json';
export default {
  name: 'OrderForm',
  components: {
    Pagination1,
    Pagination2,
    Pagination3,
  },
  data() {
    return {
      pager: 0,
      product_data: product_data,
      power_data: power_data,
      case_data: case_data,
      scooter_data: scooter_data,
      buy_data: {
        product: {
          title: '雷神黑鐵排氣管+卡夢護片',
          power_option: '靜音回壓',
          scooter_brand: '',
          scooter: '',
        },
        user: {
          name: '',
          facebook_name: '',
          address: '',
          phone: '',
          email: '',
          connection: '上午',
          remarks: '',
        },
        pay: {
          ship_option: '宅配到府',
          pay_way: 'Bobopay無卡分期',
          pay_option: '全額付款',
          installment: 3,
          percent: 0.03,
        },
      },
    };
  },
  methods: {
    paggeradd(val) {
      this.pager = val;
    },
    UpdateBuyData([key, val]) {
      this.$set(this.buy_data, key, val);
    },
    SendOrder() {
      let user_data = this.buy_data.user;
      let installment = 0;
      let installment_price = 0;
      let total_price = this.order_total_price;
      if (user_data.remarks == '') {
        user_data.remarks = '-';
      }
      if (
        this.buy_data.pay.pay_way == '信用卡分期付款' ||
        this.buy_data.pay.pay_way == 'Bobopay無卡分期'
      ) {
        installment = this.buy_data.pay.installment;
        installment_price = Math.ceil(
          this.total_price * this.buy_data.pay.percent
        );
      }
      if (this.buy_data.pay.pay_option == '訂金付款') {
        total_price = 1000;
      }

      let data = {
        user: {
          name: user_data.name,
          facebook_name: user_data.facebook_name,
          phone: user_data.phone,
          email: user_data.email,
          address: user_data.address,
          commit: user_data.remarks,
          phone_time: user_data.connection,
        },
        product: {
          name: this.buy_data.product.title,
          model:
            this.buy_data.product.scooter_brand +
            ' - ' +
            this.buy_data.product.scooter,
          front_option: '',
          power_option: this.buy_data.product.power_option,
          case_option: '',
        },
        pay: {
          payway: this.buy_data.pay.pay_way,
          pay_option: this.buy_data.pay.pay_option,
          staging: installment,
          staging_price: installment_price,
          total_price: total_price,
        },
      };
      this.GLOBAL.setLoading(true);

      const vm = this;
      vm.$gtm.trackEvent({
        event: 'order_create',
        category: '訂單事件', // 類別 字元(必填)
        action: 'create', // 動作 字元(必填)
        label: '建立訂單', // 標籤 字元(選填)
        value: data.pay.total_price, // 標籤 數字(選填)
        product_price: data.pay.total_price,
        product_name: data.product.name,
        power_option: data.product.power_option,
        pay_way: data.pay.pay_way,
        pay_option: data.pay.pay_option,
      });

      if (this.buy_data.pay.pay_way == '貨到付款') {
        this.SendCODOrder(data);
      } else if (this.buy_data.pay.pay_way == 'Bobopay無卡分期') {
        this.SendBobopayOrder(data);
      } else {
        this.SendNewebPayOrder(data);
      }
    },
    SendBobopayOrder(data) {
      this.axios
        .post(
          process.env.VUE_APP_BASE_API + 'BOBOPAY_Payment.php',
          JSON.stringify(data)
        )
        .then((response) => {
          this.GLOBAL.setLoading(false);
          this.$router.replace({
            query: {
              status: 'order_finish',
              order_no: response.data,
            },
          });
          this.CheckOrderRecord();
          this.GLOBAL.setDialog(
            true,
            '感謝您的訂購！您的訂單編號為:<br><strong>' +
              this.$route.query.order_no +
              '</strong><br>近日請留意您的電話與簡訊，將有專人與您聯繫'
          );
        });
    },
    SendCODOrder(data) {
      this.axios
        .post(
          process.env.VUE_APP_BASE_API + 'COD_Payment.php',
          JSON.stringify(data)
        )
        .then((response) => {
          this.GLOBAL.setLoading(false);
          this.$router.replace({
            query: {
              status: 'order_finish',
              order_no: response.data,
            },
          });
          this.CheckOrderRecord();
          this.GLOBAL.setDialog(
            true,
            '感謝您的訂購！您的訂單編號為:<br><strong>' +
              this.$route.query.order_no +
              '</strong><br>若有任何問題請恰粉絲專頁私訊'
          );
        });
    },
    SendNewebPayOrder(data) {
      this.axios
        .post(
          process.env.VUE_APP_BASE_API + 'encrypt.php',
          JSON.stringify(data)
        )
        .then((response) => {
          this.GLOBAL.setLoading(true);
          // this.$store.commit('SetLoading', true)
          let newebpay_data = JSON.parse(response.data.msg);
          let method = 'post';
          let params = {
            MerchantID: newebpay_data.MerchantID,
            TradeInfo: newebpay_data.TradeInfo,
            TradeSha: newebpay_data.TradeSha,
            Version: newebpay_data.Version,
          };
          var form = document.createElement('form');
          form.setAttribute('method', method);
          form.setAttribute(
            'action',
            'https://core.newebpay.com/MPG/mpg_gateway'
            //'https://ccore.newebpay.com/MPG/mpg_gateway'
          );

          Object.keys(params).forEach((item) => {
            let hiddenField = document.createElement('input');
            hiddenField.setAttribute('type', 'hidden');
            hiddenField.setAttribute('name', item);
            hiddenField.setAttribute('value', params[item]);

            form.appendChild(hiddenField);
          });

          document.body.appendChild(form);
          form.submit();
        });
    },
    ScrollToFormTop() {
      this.menuopen = false;
      let el_top = document.getElementById('OrderForm').offsetTop;
      window.scrollTo({
        top: el_top - 80,
        left: 0,
        behavior: 'smooth',
      });
    },
  },
  computed: {
    total_price() {
      let product_price = 0;
      let scooter_price = 0;
      let power_option_price = 0;

      if (this.buy_data.product.title != '') {
        product_price = this.product_data.filter(
          (item) => this.buy_data.product.title == item.name
        )[0].price;
      }
      if (this.buy_data.product.scooter != '') {
        scooter_price = this.scooter_data[
          this.buy_data.product.scooter_brand
        ].filter((item) => item.title == this.buy_data.product.scooter)[0]
          .price;
      }
      if (this.buy_data.product.power_option != '') {
        power_option_price = this.power_data.filter(
          (item) => this.buy_data.product.power_option == item.name
        )[0].price;
      }

      // if (this.buy_data.product.case_option != '') {
      //   case_option_price = this.case_data.filter(
      //     (item) => this.buy_data.product.case_option == item.name
      //   )[0].price;
      // }

      return (
        parseInt(product_price) +
        parseInt(scooter_price) +
        parseInt(power_option_price)
      );
    },
    order_total_price() {
      let installment_price = 0;
      let ship_price = 0;
      if (this.buy_data.pay.ship_option == '宅配到府') {
        ship_price = 200;
      }
      if (this.buy_data.pay.pay_way == '信用卡分期付款') {
        installment_price = Math.ceil(
          this.total_price * this.buy_data.pay.percent
        );
      }
      return this.total_price + installment_price + ship_price;
    },
  },
};
</script>
