<template src='./template.html'>
  
</template>

<script>
import Pagination3 from '../Pagination3/index'
export default {
    components: {
        Pagination3,
    },
    data() {
        return{
            connection_time: 1,
            visapay: 1,
        }
    },

    methods: {
        product_connection_time: function(id){
            this.connection_time = id;
        },
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