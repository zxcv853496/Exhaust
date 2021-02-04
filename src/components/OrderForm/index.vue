<template src="./template.html"></template>

<script>
import Pagination1 from "../Pagination/Pagination1/index";
import Pagination2 from "../Pagination/Pagination2/index";
import Pagination3 from "../Pagination/Pagination3/index";
export default {
  name: "OrderForm",
  components: {
    Pagination1,
    Pagination2,
    Pagination3,
  },
  data() {
    return {
      pager: 0,
      product_data: {
        products: [
          {
            id: 1,
            name: "雷神黑鐵排氣管",
            price: 3000,
          },
          {
            id: 2,
            name: "雷神白鐵排氣管",
            price: 4000,
          },
        ],
        power_option: [
          {
            id: 1,
            name: "適用於原廠引擎",
            price: 0,
          },
          {
            id: 2,
            name: "適用於改缸引擎",
            price: 1000,
          },
        ],
        case_option: [
          {
            id: 1,
            name: "雷神高品質碳纖維防燙蓋",
            price: 0,
          },
          {
            id: 2,
            name: "雷神特製彩鈦防燙蓋",
            price: 300,
          },
          {
            id: 3,
            name: "KOSO風動鋭行防燙蓋",
            price: 500,
          },
        ],
      },
      buy_data: {
        product: {
          category: 1,
          power_option: 1,
          case_option: 1,
          model: "",
        },
        user: {
          name: '',
          address: '',
          phone: '',
          email: '',
          connection: '上午',
          remarks: '',
        },
        pay: {
          pay_way: "貨到付款",
          pay_option: "全額付款",
          installment: 3,
          percent: 0.03
        }
      },
    }
  },
  methods: {
    paggeradd(val) {
      if (val == 0) {
        this.$refs.ProductForm.SetData(this.buy_data.product)
      }
      else if (val == 1) {
        this.$refs.UserForm.SetData(this.buy_data.user)
      }
      else if (val == 2) {
        this.$refs.PayForm.SetData(this.buy_data.pay)
      }
      this.pager = val
    },
    UpdateBuyData([key, val]) {
      this.buy_data[key] = val
    },
    SendOrder() {
      let user_data = this.buy_data.user
      let installment = 0
      let installment_price = 0
      let total_price = this.total_price
      if (user_data.remarks == "") {
        user_data.remarks = "-"
      }
      if (this.buy_data.pay.pay_way == "信用卡分期付款") {
        installment = this.buy_data.pay.installment
        installment_price = Math.ceil(this.total_price * this.buy_data.pay.percent)
      }
      if (this.buy_data.pay.pay_option == '訂金付款') {
        total_price = Math.ceil(this.total_price / 2)
      }


      let data = {
        user: {
          name: user_data.name,
          phone: user_data.phone,
          email: user_data.email,
          address: user_data.address,
          commit: user_data.remarks,
          phone_time: '',
        },
        product: {
          name: this.product_name,
          model: this.buy_data.product.model,
          front_option: "前段下繞",
          power_option: this.power_option_name,
          case_option: this.case_option_name,
        },
        pay: {
          payway: this.buy_data.pay.pay_way,
          pay_option: this.buy_data.pay.pay_option,
          staging: installment,
          staging_price: installment_price,
          total_price: total_price + parseInt(installment_price),
        },
      }
      this.$store.commit('SetLoading', true)

      const vm = this;
      vm.$gtm.trackEvent({
        event: 'order_create',
        category: '訂單事件', // 類別 字元(必填)
        action: 'create', // 動作 字元(必填)
        label: "建立訂單", // 標籤 字元(選填)
        value: data.pay.total_price, // 標籤 數字(選填)
        product_price: data.pay.total_price,
        product_name: data.product.name,
        case_option: data.product.case_option,
        power_option: data.product.power_option,
        pay_way: data.pay.pay_way,
        pay_option: data.pay.pay_option
      });

      if (this.buy_data.pay.pay_way == '貨到付款') {
        this.SendCODOrder(data)
      }
      else {
        this.SendNewebPayOrder(data)
      }
    },
    SendCODOrder(data) {
      this.axios
        .post(
          process.env.VUE_APP_BASE_API + 'COD_Payment.php',
          JSON.stringify(data)
        )
        .then((response) => {
          this.$store.commit('SetLoading', false)
          this.$router.replace({
            'query': {
              'status': "order_finish",
              'order_no': response.data
            }
          });
          this.CheckOrderRecord()
          this.$store.commit("SetDialog", [true, "感謝您的訂購！您的訂單編號為:<br><strong>" + this.$route.query.order_no + "</strong><br>若有任何問題請恰粉絲專頁私訊"])
        })
    },
    SendNewebPayOrder(data) {
      this.axios
        .post(
          process.env.VUE_APP_BASE_API + 'encrypt.php',
          JSON.stringify(data)
        )
        .then((response) => {
          this.$store.commit('SetLoading', true)
          let newebpay_data = JSON.parse(response.data.msg)
          let method = 'post'
          let params = {
            MerchantID: newebpay_data.MerchantID,
            TradeInfo: newebpay_data.TradeInfo,
            TradeSha: newebpay_data.TradeSha,
            Version: newebpay_data.Version,
          }
          var form = document.createElement('form')
          form.setAttribute('method', method)
          form.setAttribute(
            'action',
            'https://core.newebpay.com/MPG/mpg_gateway'
            //'https://ccore.newebpay.com/MPG/mpg_gateway'
          )

          Object.keys(params).forEach((item) => {
            let hiddenField = document.createElement('input')
            hiddenField.setAttribute('type', 'hidden')
            hiddenField.setAttribute('name', item)
            hiddenField.setAttribute('value', params[item])

            form.appendChild(hiddenField)
          })

          document.body.appendChild(form)
          form.submit()
        })
    },
    ScrollToFormTop() {
      this.menuopen = false
      let el_top = document.getElementById('OrderForm').offsetTop;
      window.scrollTo({
        top: el_top - 80,
        left: 0,
        behavior: "smooth"
      })
    }
  },
  computed: {
    total_price() {
      let product_price = this.product_data.products.filter(item => this.buy_data.product.category == item.id)[0].price
      let power_option_price = this.product_data.power_option.filter(item => this.buy_data.product.power_option == item.id)[0].price
      let case_option_price = this.product_data.case_option.filter(item => this.buy_data.product.case_option == item.id)[0].price
      return parseInt(product_price) + parseInt(power_option_price) + parseInt(case_option_price)
    },
    product_name() {
      return this.product_data.products.filter(item => item.id == this.buy_data.product.category)[0].name
    },
    power_option_name() {
      return this.product_data.power_option.filter(item => item.id == this.buy_data.product.power_option)[0].name
    },
    case_option_name() {
      return this.product_data.case_option.filter(item => item.id == this.buy_data.product.case_option)[0].name
    }
  },
}
</script>