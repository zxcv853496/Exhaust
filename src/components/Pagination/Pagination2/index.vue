<template src='./template.html'></template>

<script>
export default {
  components: {},
  data() {
    return {
      connection_time: 1, //聯絡時間
      user_data:{
        name: '',
        address: '',
        phone: '',
        email: '',
        connection: '',
        remarks: '',
      }
    };
  },

  methods: {
    product_connection_time: function (id) {
      if (id == 1){
        this.user_data.connection = '上午'
      }
      if (id == 2){
        this.user_data.connection = '中午'
      }
      if (id == 3){
        this.user_data.connection = '下午'
      }
    },
    perv_step() {
      this.$emit("pagger-add", 0);
    },
    next_step() {
      let user_data = {
        name : this.user_data.name,
        address : this.user_data.address,
        phone : this.user_data.phone,
        email : this.user_data.email,
        connection : this.user_data.connection,
        remarks : this.user_data.remarks,
      }
      this.$emit("user-data", ["user",user_data])
      this.$emit("pagger-add", 2);
    },
  },
};
</script>

<style>
</style>