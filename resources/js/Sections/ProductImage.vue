<template>
    <div  @mouseover="hoverMethod" @mouseout="hoverImage=false;">
        <img v-if="product.image_object && hoverImage===false" :src="product.image_object.url" class="mb-4">
        <img v-if="hoverImage === true" :src="product.hover_image_object.url" class="mb-4">
    </div>
</template>

<script>
export default {
    props:['product'],
    name: "ProductImage",
    data(){
        return{
            hoverImage:false,
        }
    },
    methods:{
        hoverMethod(){
            if(this.product.hover_image_object != null){
                this.hoverImage=true;

            }
        }
    }
}
</script>

<style scoped>

</style>
