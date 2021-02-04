<template src='./template.html'></template>

<script>
export default {
  props: {
    product_data: {
      require: true,
    },
  },
  data() {
    return {
      product: 1,
      power_option: 1,
      case_option: 1,
      model: "",
      errors: []
    };
  },
  methods: {
    SetData(data) {
      this.product = data.category
      this.power_option = data.power_option
      this.case_option = data.case_option
      this.model = data.model
    },
    DataVaild() {
      this.errors = []
      if (this.validNotEmpty(this.model) != true) {
        this.errors.push({
          name: "model",
          msg: this.validNotEmpty(this.model)
        })
      }

      if (this.errors.length <= 0) {
        this.NextStep()
      }
    },
    NextStep() {
      let buy_data = {
        category: this.product,
        power_option: this.power_option,
        case_option: this.case_option,
        model: this.model,
      }
      this.$emit("scroll-to-top")
      this.$emit("update-buy-data", ["product", buy_data])
      this.$emit("pagger-add", 1)
    }
  },
  computed: {
    total_price() {
      let product_price = this.product_data.products.filter(item => this.product == item.id)[0].price
      let power_option_price = this.product_data.power_option.filter(item => this.power_option == item.id)[0].price
      let case_option_price = this.product_data.case_option.filter(item => this.case_option == item.id)[0].price
      return parseInt(product_price) + parseInt(power_option_price) + parseInt(case_option_price)
    }
  }
};
</script>
