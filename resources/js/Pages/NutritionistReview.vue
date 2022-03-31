<template>
    <layout>

        <section class="mt-20 mb-10 container max-w-screen-md mx-auto px-6 text-center">
			<h1 class="font-serif text-3xl tracking-wider mt-2 mb-4">Review Your Nutritionist Consult</h1>
		</section>

    	<section class="mb-20 container mx-auto px-6" style="max-width: 500px;">
            <form @submit.prevent="reviewOrder" class="w-full">
                <div class="mb-5">
                    <form-label for="email">
                        Email
                    </form-label>
                    <input v-model="reviewForm.email"  type="text" class="w-full border-primary focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-25" id="email">
                    <form-input-error :message="reviewForm.errors.email" />

                </div>
                <div class="mb-5">
                    <form-label for="name">
                        Name
                    </form-label>
                    <input  v-model="reviewForm.name" type="text" class="w-full border-primary focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-25" id="name">
                    <form-input-error :message="reviewForm.errors.name" />

                </div>
                <div class="mb-3">
                    <form-label for="rating" value="Rating" />
                    <!-- <form-input id="rating" type="number" v-model="reviewForm.rating" /> -->
                    <star-rating v-model:rating="reviewForm.rating" :show-rating="false" :star-size="30" :padding="5"></star-rating>
                    <form-input-error :message="reviewForm.errors.rating" />
                </div>

                <div class="mb-2 ">
                    <form-label for="review" value="Review" />
                    <textarea id="review" v-model="reviewForm.review" class="w-full border-primary focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-25" rows="5" />
                    <form-input-error :message="reviewForm.errors.review" />
                </div>

                <button-primary :class="{ 'opacity-25': reviewForm.processing }" :disabled="reviewForm.processing">Submit Review</button-primary>

                  <div class="mt-6"> {{ $page.props.flash.success }}</div>
            </form>

        </section>

    </layout>

</template>

<script>
import Layout from '@/Layouts/App'

import moment from "moment";
import StarRating from "vue-star-rating";
import ButtonPrimary from '@/Components/ButtonPrimary.vue'

import FormLabel from '@/Components/FormLabel'
import FormInput from '@/Components/FormInput'
import FormInputError from '@/Components/FormInputError'

export default {
    name: "NutritionistReview",
    components:{
        Layout,
        ButtonPrimary,
        FormLabel,
        FormInput,
        FormInputError,

        StarRating
    },
    data() {
        return {
            reviewForm: this.$inertia.form({
                email:'',
                name:'',
                review: '',
                rating: '',
            })
        }
    },
    methods:{
        reviewOrder() {
            this.reviewForm.post('/nutritionist-consults-review/submit', {
                preserveScroll: true,
                onSuccess: () => {
                    this.reviewForm.reset();

                },
                // onFinish: () => this.reviewForm.reset(),
            })
        }
    },
    created() {
        this.moment = moment;

    }
}
</script>

<style scoped>

</style>
