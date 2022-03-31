<template>
    <section class="">
        <div class="relative">
            <swiper class="swiper-hero" :slides-per-view="1" :navigation="{ nextEl: '.swiper-hero .swiper-button-next', prevEl: '.swiper-hero .swiper-button-prev' }">
                <swiper-slide v-for="banner in content.banners" :key="banner.key">
                    <div class="home-slide bg-cover bg-center flex items-center justify-center" :style="'background-image: url('+banner.attributes.image_object.url+')'">
                        <h2 v-if="banner.attributes.heading" class="font-serif text-5xl tracking-wider text-white ">{{ banner.attributes.heading }}</h2>
                    </div>
                </swiper-slide>
                <div v-if="content.banners.length > 1">
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </swiper>
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
