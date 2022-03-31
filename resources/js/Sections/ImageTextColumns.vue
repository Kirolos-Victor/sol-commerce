<template>
    <section class="container mx-auto flex items-center flex-wrap">
        <div class="w-full md:w-1/2 p-6" :class="{'md:order-last': content.image_right}">
            <img v-if="content.image_object" class="mb-5" :src="content.image_object.url">
        </div>
        <div class="flex items-center flex-wrap w-full md:w-1/2">
            <div v-for="column in content.columns" :key="column.key" class="w-full p-6 md:w-1/2" :class="{
                'text-center': content.centered,
                'xl:w-1/3': content.columns_per_row == 3
            }">
                <img v-if="column.attributes.image_object" class="mb-5" :class="{ 'mx-auto': content.centered }" :src="column.attributes.image_object.url">
                <h4 v-if="column.attributes.preheading" class="uppercase text-sm tracking-wider">{{ column.attributes.preheading }}</h4>
                <h2 v-if="column.attributes.heading" class="font-serif text-2xl tracking-wider mt-2 mb-4">{{ column.attributes.heading }}</h2>
                <div v-if="column.attributes.content" v-html="column.attributes.content" class="mb-5"></div>
                <button-text v-if="column.attributes.button_text && column.attributes.button_url" :href="column.attributes.button_url">{{ column.attributes.button_text }}</button-text>
            </div>
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
