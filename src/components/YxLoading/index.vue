<template src="./template.html"></template>

<script>
// import { store } from "../../common/store";
export default {
  name: "Loading",
  data() {
    return {
    }
  },
  computed: {
    loading() {
      return this.GLOBAL.state.loading;
    },
  },
}
</script>

<style lang="scss" src="./_yxLoading.scss"></style>