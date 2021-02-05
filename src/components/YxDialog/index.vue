<template src="./template.html"></template>

<script>
export default {
  name: "Dialog",
  methods: {
    CloseDialog() {
      this.GLOBAL.setDialog(false , '')
      // this.$store.commit("SetDialog", [false, ""])
      this.$router.replace({ 'query': null });
    }
  },
  computed: {
    dialog() {
      return this.GLOBAL.state.dialog.status
    }
  }
}
</script>

<style lang="scss" src="./_yxDialog.scss"></style>