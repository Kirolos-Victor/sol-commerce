<template>
    <layout>

		<div v-for="(section, index) in page.content" :key="section.layout" :class="index === 0 && section.layout === 'hero_banner' ? '' : 'mt-20'">
			<hero-banner v-if="section.layout === 'hero_banner'" :content="section.attributes" class="mb-20"></hero-banner>
			<standard-content v-if="section.layout === 'standard_content'" :content="section.attributes" class="mb-20" :productNutritionist="productNutritionist" :pageTitle="page.title"></standard-content>
			<image-text v-if="section.layout === 'image_text'" :content="section.attributes" class="mb-20"></image-text>
			<image-text-columns v-if="section.layout === 'image_text_columns'" :content="section.attributes" class="mb-20"></image-text-columns>
			<columns v-if="section.layout === 'columns'" :content="section.attributes" class="mb-20"></columns>
			<tabs v-if="section.layout === 'tabs'" :content="section.attributes" class="-mt-10 mb-20"></tabs>
			<item-carousel v-if="section.layout === 'item_carousel'" :content="section.attributes" class="mb-20"></item-carousel>
			<contact-form v-if="section.layout === 'contact_form'" :content="section.attributes" class="mb-20 container max-w-screen-md mx-auto p-6"><div class="test"></div></contact-form>
		</div>

    </layout>
</template>

<script>
import Layout from '@/Layouts/App'

import HeroBanner from '@/Sections/HeroBanner'
import StandardContent from '@/Sections/StandardContent'
import ImageText from '@/Sections/ImageText'
import ImageTextColumns from '@/Sections/ImageTextColumns'
import Columns from '@/Sections/Columns'
import Tabs from '@/Sections/Tabs'
import ItemCarousel from '@/Sections/ItemCarousel'
import ContactForm from '@/Sections/ContactForm'

export default {
	props: ['page','productNutritionist'],

	computed: {

    },

	components: {
		Layout,

		HeroBanner,
		StandardContent,
		ImageText,
		ImageTextColumns,
		Columns,
		Tabs,
		ItemCarousel,
		ContactForm
	},

	data() {
		return {

		}
	},

	created() {
		this.track();
	},

	methods: {
		track() {
			dataLayer.push({'klav_page': {
				url: window.location.protocol+'//'+window.location.hostname+'/'+this.page.url
			}});

			dataLayer.push({'event': 'klaviyo_page'});
		}
	}
}
</script>
