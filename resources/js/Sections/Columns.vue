<template>
    <section class="container mx-auto">

        <div v-if="!content.carousel" class="flex items-center flex-wrap">
            <div class="w-full md:w-1/3 p-6" :class="{
                'text-center': content.centered,
                'xl:w-1/4': content.columns_per_row == 4
            }" v-for="column in content.columns" :key="column.key">
                <img v-if="column.attributes.image_object" class="mb-5" :src="+column.attributes.image_object.url">
                <h4 v-if="column.attributes.preheading" class="uppercase text-sm tracking-wider">{{ column.attributes.preheading }}</h4>
                <h2 v-if="column.attributes.heading" class="font-serif text-2xl tracking-wider mb-2">{{ column.attributes.heading }}</h2>
                <div v-html="column.attributes.content" class="mb-2"></div>
                <button-text v-if="column.attributes.button_text && column.attributes.button_url" :href="column.attributes.button_url">{{ column.attributes.button_text }}</button-text>
            </div>
        </div>

        <div v-else class="px-6 swiper-articles relative">
            <swiper :slides-per-view="content.columns_per_row" :space-between="40" :navigation="{ nextEl: '.swiper-articles .swiper-button-next', prevEl: '.swiper-articles .swiper-button-prev' }">
                <swiper-slide v-for="column in content.columns" :key="column.key">
                    <img v-if="column.attributes.image_object" class="mb-5" :src="column.attributes.image_object.url">
                    <h4 v-if="column.attributes.preheading" class="uppercase text-sm tracking-wider">{{ column.attributes.preheading }}</h4>
                    <h2 v-if="column.attributes.heading" class="font-serif text-2xl tracking-wider mb-2">{{ column.attributes.heading }}</h2>
                    <div v-html="column.attributes.content" class="mb-2"></div>
                    <button-text v-if="column.attributes.button_text && column.attributes.button_url" :href="column.attributes.button_url">{{ column.attributes.button_text }}</button-text>
                </swiper-slide>
            </swiper>
            <div class="swiper-navigation-outside">
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>

    </section>
</template>

<script>
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/swiper.scss';

import ButtonText from '@/Components/ButtonText'

export default {
	props: ['content'],

	components: {
		Swiper,
		SwiperSlide,
		ButtonText,
	},
}
</script>
