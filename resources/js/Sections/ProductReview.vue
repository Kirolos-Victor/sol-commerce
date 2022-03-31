<template>
    <div v-if="product && product.reviews" v-for="(review,index) in product.reviews" :key="review.id" class="bg-lightbox p-8 pb-6 mb-8" v-show="index < reviews">
        <div class="flex justify-between items-center mb-5">
            <div class="uppercase tracking-widest">{{ review.user != null?review.user.name:review.backup_name}} – {{ moment(review.created_at).format('Do MMM YYYY') }}</div>
            <div class="text-primary text-lg"><star-rating v-model:rating="review.rating" :read-only="true" :active-color="'#AA947C'" :show-rating="false" :star-size="20" :inline="true"></star-rating></div>
        </div>
        <div v-html="review.review"></div>
    </div>
    <button class="uppercase tracking-widest border-b font-semibold border-secondary pb-1 text-xs hover:text-secondary" v-show="readMore" @click.prevent="readMoreReviews()">Read More</button>
</template>

<script>
import StarRating from 'vue-star-rating'
import moment from 'moment'
export default {
    props:['product'],
    name: "ProductReview",
    components:{
        StarRating
    },
    data(){
        return{
            readMore:true,
            reviews:5,
        }
    },
    methods:{
        readMoreReviews(){
            this.reviews=this.product.reviews.length;
            this.readMore=false;
        }
    },
    created() {
        this.moment = moment;
        if (this.product && this.product.reviews.length <= 5){
            this.readMore=false;
        }
    }
}
</script>

<style scoped>

</style>
