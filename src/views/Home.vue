<template>
  <div class="home">
    <Dialog />
    <Loading />

    <MainBanner />

    <Compare />

    <ProductPreview />

    <OrderForm />
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
    };
  },
  methods: {
  },
  async mounted() {
    if (this.$route.query.status) {
      if (this.$route.query.status == "order_finish") {
        this.CheckOrderRecord()
        this.$store.commit("SetDialog", [true, "感謝您的訂購！您的訂單編號為:<br><strong>" + this.$route.query.order_no + "</strong><br>若有任何問題請恰粉絲專頁私訊"])
      }
      else if (this.$route.query.status == "payerror") {
        this.$store.commit("SetDialog", [true, "付款時發生錯誤，若問題持續發生請至粉絲專頁私訊。"])
      }
    }
  },
};
</script>
