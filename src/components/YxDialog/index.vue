<template src="./template.html"></template>

<script>
export default {
  name: "Dialog",
  methods: {
    CloseDialog() {
      this.$store.commit("SetDialog", [false, ""])
      this.$router.replace({ 'query': null });
    }
  },
  computed: {
    dialog() {
      return this.$store.state.dialog
    }
  }
}
</script>

<style lang="scss" src="./_yxDialog.scss"></style>