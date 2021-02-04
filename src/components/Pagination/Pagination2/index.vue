<template src='./template.html'></template>

<script>
export default {
  props: {
    total_price: {
      require: true
    }
  },
  data() {
    return {
      user_data: {
        name: '',
        address: '',
        phone: '',
        email: '',
        connection: '上午',
        remarks: '',
      },
      errors: []
    };
  },

  methods: {
    SetData(data) {
      this.user_data.name = data.name
      this.user_data.address = data.address
      this.user_data.email = data.email
      this.user_data.connection = data.connection
      this.user_data.remarks = data.remarks
    },
    DataVaild() {
      this.$emit("scroll-to-top")
      this.errors = []
      if (this.validNotEmpty(this.user_data.address) != true) {
        this.errors.push({
          name: "address",
          msg: this.validNotEmpty(this.user_data.address)
        })
      }
      if (this.validName(this.user_data.name) != true) {
        this.errors.push({
          name: "name",
          msg: this.validName(this.user_data.name)
        })
      }
      if (this.validPhone(this.user_data.phone) != true) {
        this.errors.push({
          name: "phone",
          msg: this.validPhone(this.user_data.phone)
        })
      }
      if (this.validEmail(this.user_data.email) != true) {
        this.errors.push({
          name: "email",
          msg: this.validEmail(this.user_data.email)
        })
      }

      if (this.errors.length <= 0) {
        this.NextStep()
      }
    },
    UpdateData() {
      let user_data = {
        name: this.user_data.name,
        address: this.user_data.address,
        phone: this.user_data.phone,
        email: this.user_data.email,
        connection: this.user_data.connection,
        remarks: this.user_data.remarks,
      }
      this.$emit("update-buy-data", ["user", user_data])
    },
    perv_step() {
      this.UpdateData()
      this.$emit("scroll-to-top")
      this.$emit("pagger-add", 0);
    },
    NextStep() {
      this.UpdateData()
      this.$emit("pagger-add", 2);
    },
  },
};
</script>

<style>
</style>