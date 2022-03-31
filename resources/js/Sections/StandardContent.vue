<template>
    <section class="container max-w-screen-lg mx-auto px-6" :class="{ 'text-center': content.centered }">
        <h4 v-if="content.preheading" class="uppercase text-sm tracking-wider">{{ content.preheading }}</h4>

        <h2 v-if="content.heading" class="font-serif text-3xl tracking-wider mt-2 mb-4" :id="content.heading.replace(/[^A-Z0-9]/ig, '')">{{ content.heading }}</h2>

        <div v-if="content.content" v-html="content.content" class="mb-5 wysiwyg"></div>
        <div v-show="pageTitle ==='Clinical Nutritionist Consults'">
            <div class="text-center mb-5 mt-10">
                <div class="mx-2 font-serif text-3xl tracking-wider text-primary">Reviews</div>
            </div>
            <product-review :product="productNutritionist"></product-review>
        </div>


        <button-text v-if="content.button_text && content.button_url" :href="content.button_url">{{ content.button_text }}</button-text>
    </section>
</template>

<script>
import ButtonText from '@/Components/ButtonText'
import ProductReview from "@/Sections/ProductReview";

export default {
	props: ['content','productNutritionist','pageTitle'],

	components: {
        ProductReview,
		ButtonText,
	},

}
</script>
