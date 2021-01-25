<template src='./template.html'>
  
</template>

<script>


export default {
    computed:{
    },
    data() {
        return {
            howpay: 0,
            visapay: 1,
        }
    },
    methods: {
        selection_howpay: function(id){
            this.howpay = id;
        },
        selection_visapay: function(id){
            this.visapay = id;
        }
    }
}
</script>

<style>

</style>