<template src="./template.html"></template>

<script>
export default {
  name: "Dialog",
  props: {
    dialog: { require: true }
  },
  methods: {
    CloseDialog() {
      this.$emit("set-dialog", [false, ""])
      this.$router.replace({ 'query': null });
    }
  }
}
</script>

<style lang="scss" src="./_yxDialog.scss"></style>