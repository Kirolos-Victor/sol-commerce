<template>
    <section class="container mx-auto px-6">

        <h2 v-if="content.heading" class="font-serif text-3xl tracking-wider mt-2 mb-4">{{ content.heading }}</h2>

        <div :class="'swiper-inclusions relative swiper-'+swiperKey">
            <swiper :slides-per-view="1" :scrollbar="{ draggable: true }" :navigation="{ nextEl: '.swiper-'+swiperKey+' .swiper-button-next', prevEl: '.swiper-'+swiperKey+' .swiper-button-prev' }">
                <swiper-slide v-for="slide in content.slides" :key="slide.key">
                    <div class="flex flex-wrap items-center">
                        <div class="w-full md:w-1/4 flex flex-wrap pr-6">
                            <img v-if="slide.attributes.image_object" class="mr-5" :src="slide.attributes.image_object.url">
                        </div>
                        <div class="w-full md:w-3/4 pl-6 py-6 md:pr-20">
                            <h2 class="font-serif text-2xl tracking-wider mb-2">{{ slide.attributes.heading }}</h2>
                            <h4 class="uppercase text-sm tracking-wider mb-2">{{ slide.attributes.subheading }}</h4>
                            <div class="mb-2" v-html="slide.attributes.content"></div>
                            <div class="mb-2"><strong class="font-bold">Size:</strong> {{ slide.attributes.size }}</div>

                            <div v-if="slide.attributes.nutritional_info">
                                <span class="cursor uppercase tracking-widest border-b font-semibold border-secondary pb-1 text-xs hover:text-secondary" @click.prevent="openModal(slide.attributes)">Nutritional Info</span>
                            </div>
                        </div>
                    </div>
                </swiper-slide>
            </swiper>
            <div class="swiper-navigation-outside">
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>

        <dialog-modal :show="modalOpen" max-width="lg" @close="modalOpen = false">
            <template #title>
                <div class="mt-2 text-xs tracking-wider">NUTRITIONAL INFORMATION</div>
                <div class="mt-2 font-serif text-2xl tracking-wider">{{ modalItem.heading }}</div>
            </template>

            <template #content>
                <table class="nutritional-info border-collapse border border-primary w-full text-primary mb-4 text-right">
                    <tr v-for="(row, rowIndex) in modalItem.nutritional_info" :key="rowIndex">
                        <td v-for="(col, colIndex) in row" :key="colIndex" class="border-t border-b border-primary p-1">{{ col }}</td>
                    </tr>
                </table>
            </template>

            <template #footer>
                
            </template>
        </dialog-modal>

    </section>
</template>

<script>
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/swiper.scss';

import ButtonText from '@/Components/ButtonText'
import DialogModal from '@/Components/DialogModal'

export default {
	props: ['content', 'swiperKey'],

	components: {
		Swiper,
		SwiperSlide,
		ButtonText,

        DialogModal
	},

    data() {
		return {
            modalOpen: false,
            modalItem: ''
        }
    },

    methods: {
        openModal(item) {
            this.modalOpen = true;
            this.modalItem = item;
            console.log(this.modalItem);
        }
    }
}
</script>

<style scoped>
.nutritional-info tr td:first-child {
    text-align: left;
}
</style>