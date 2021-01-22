<template src='./template.html'>
</template>

<script>
export default {
    data() {
        return{
            product: 0,
            accessory: 0,
            setting: 0,
        }
    },
    methods: {
        product_select: function(id){
            this.product = id;
        },
        option_select: function(id){
            this.accessory = id;
        },
        setting_select: function(id){
            this.setting = id;
        }
    }
}
</script>
