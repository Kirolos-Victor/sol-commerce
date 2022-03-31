<template>
    <section class="container mx-auto flex items-center flex-wrap">
        <div class="w-full md:w-1/2 p-6" :class="{'md:order-last': content.image_right}">
            <img v-if="content.image_object" class="mb-5" :src="content.image_object.url">
        </div>
        <div class="w-full md:w-1/2 p-6">
            <h4 v-if="content.preheading" class="uppercase text-sm tracking-wider">{{ content.preheading }}</h4>
            <h2 v-if="content.heading" class="font-serif text-3xl tracking-wider mt-2 mb-4">{{ content.heading }}</h2>
            <div v-if="content.content" v-html="content.content" class="mb-5"></div>
            <button-text v-if="content.button_text && content.button_url" :href="content.button_url">{{ content.button_text }}</button-text>
        </div>
    </section>
</template>

<script>
import ButtonText from '@/Components/ButtonText'

export default {
	props: ['content'],

	components: {
		ButtonText,
	},
}
</script>
