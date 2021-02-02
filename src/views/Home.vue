<template>
  <div class="home">
    <Dialog :dialog="dialog" v-on:set-dialog="SetDialog" />
    <Loading :loading="loading" />

    <MainBanner />

    <Compare />

    <ProductPreview />

    <OrderForm v-on:set-loading="SetLoading" v-on:set-dialog="SetDialog" />
  </div>
</template>

<script>
import MainBanner from '../components/MainBanner/index'
import Compare from '../components/Compare/index'
import ProductPreview from '../components/ProductPreview/index'
import OrderForm from '../components/OrderForm/index'
import Loading from '../components/YxLoading/index'
import Dialog from '../components/YxDialog/index'

export default {
  name: "Home",
  components: {
    Compare,
    Loading,
    Dialog,
    MainBanner,
    ProductPreview,
    OrderForm
  },
  data() {
    return {
      loading: false,
      dialog: {
        msg: "",
        status: false
      }
    };
  },
  methods: {
    SetLoading(action) {
      this.loading = action
    },
    SetDialog([action, msg]) {
      this.dialog.msg = msg
      this.dialog.status = action
    },
  },
  mounted() {
    if (this.$route.query.status) {
      if (this.$route.query.status == "order_finish") {
        this.SetDialog([true, "感謝您的訂購！您的訂單編號為:<br>" + this.$route.query.order_no + "<br>若有任何問題請恰粉絲專頁私訊"])
      }
      else if (this.$route.query.status == "payerror") {
        this.SetDialog([true, "付款時發生錯誤，若問題持續發生請至粉絲專頁私訊。"])
      }
    }
  },
};
</script>
