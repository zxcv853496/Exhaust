<template src='./template.html'></template>

<script>
export default {
  data() {
    return {
      menuopen: false,
    }
  },
  methods: {
    ScrollTo(val) {
      this.menuopen = false
      let el_top = document.getElementById(val).offsetTop;
      window.scrollTo({
        top: el_top - 80,
        left: 0,
        behavior: "smooth"
      })
    }
  }
}
</script>

<style>
</style>