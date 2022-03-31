<template>
    <layout>

		<location-popup :product="product"></location-popup>

		<section class="container mx-auto flex flex-wrap my-20">
			<div class="w-full md:w-2/3 p-6 flex flex-wrap">
				<div class="w-full md:w-1/5 pr-6">
					<swiper
						@swiper="setThumbsSwiper"
						:direction="'vertical'"
						:slides-per-view="'auto'"
						:freeMode="true"
						:autoHeight="true"
						watch-slides-visibility
						watch-slides-progress
						class="vertical-swiper"
					>
						<swiper-slide class="mb-4">
							<img :src="product.image_object.url">
						</swiper-slide>
						<swiper-slide v-for="variant in product.variants.filter(variant => variant.image)" :key="variant.id" class="mb-4">
							<img :src="variant.image_object.url">
						</swiper-slide>
						<swiper-slide v-for="(image, index) in product.images_object" :key="index">
							<img :src="image.url">
						</swiper-slide>
					</swiper>
				</div>
				<div class="w-full md:w-4/5">
				  	<swiper
					  	:thumbs="{ swiper: thumbsSwiper }"
						:slides-per-view="1"
						:navigation="{ nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' }"
					>
						<swiper-slide>
							<img :src="product.image_object.url">
						</swiper-slide>
						<swiper-slide v-for="variant in product.variants.filter(variant => variant.image)" :key="variant.id">
							<img :src="variant.image_object.url">
						</swiper-slide>
						<swiper-slide v-for="(image, index) in product.images_object" :key="index">
							<img :src="image.url">
						</swiper-slide>
						<div class="swiper-button-next"></div>
						<div class="swiper-button-prev"></div>
					</swiper>
				</div>
			</div>

			<div class="w-full md:w-1/3 p-6">
				<div class="mb-1">{{ this.product.categories.map(category => category.title).join(', ') }}</div>
				<h1 class="font-serif text-4xl mb-3">{{ product.title }}</h1>

				<div class="flex items-center text-primary mb-4" v-if="product.reviews && product.reviews.length > 0">
					<div class="text-lg mr-2"><star-rating v-model:rating="product.rating" :read-only="true" :active-color="'#AA947C'" :show-rating="false" :star-size="20" :inline="true" :increment="0.1"></star-rating></div>
					<div class="text-xs mt-2">{{ product.rating.toFixed(1) }} stars from {{ product.reviews.length }} review{{ product.reviews.length > 1 ? 's' : '' }}</div>
				</div>

           	 	<div v-if="product.perishable && cart && cart.location_id && (!product.locations.some(location => location.id === cart.location_id) || cart.non_perishable)" class="italic font-serif text-xl">Not available in your area</div>

				<div v-else class="mb-8">
					<form @submit.prevent="addToCart" v-if="!product.enquire_only">

						<!-- options -->
						<div v-if="product.product_type == 'Variable'" class="mb-2">
							<form-select id="product-option" name="variant_id" v-model="form.variant_id" @change="selectVariant" class="text-primary uppercase bg-transparent text-xs tracking-widest font-semibold">
								<!-- <option value="">Select option</option> -->
								<option v-for="variant in product.variants" :key="variant.id" :value="variant.id">{{ variant.option_value }}</option>
							</form-select>
							<div v-if="form.errors.variant_id" class="text-sm text-red-500 mt-1">{{ form.errors.variant_id }}</div>

							<!--
							old buttons for options
							<button
								v-for="variant in product.variants" :key="variant.id"
								@click.prevent="selectedVariant = variant; form.variant_id = variant.id"
								class="mr-2 mb-2 text-center uppercase border border-primary text-xs tracking-widest font-semibold py-2 px-4 outline-none focus:outline-none transition duration-300 ease-in-out"
								:class="{
									'text-white bg-primary': form.variant_id == variant.id,
									'text-primary bg-transparent': form.variant_id != variant.id,
								}"
							>
								{{ variant.option_value }}
							</button>
							-->
						</div>

						<!-- subscriptions -->
						<div v-if="form.variant_id && selectedVariant && selectedVariant.subscription_options.length">
							<div class="flex mb-2">
								<button
									@click.prevent="form.subscription = false; form.subscription_option_id = '';"
									class="w-full mr-1 text-center uppercase border border-primary text-xs tracking-widest font-semibold py-2 px-4 outline-none focus:outline-none transition duration-300 ease-in-out"
									:class="{
										'text-white bg-primary': !form.subscription,
										'text-primary bg-transparent': form.subscription,
									}"
								>
									Buy once
								</button>
								<button
									@click.prevent="form.subscription = true; form.subscription_option_id = selectedVariant.subscription_options[0].id;"
									class="w-full ml-1 text-center uppercase border border-primary text-xs tracking-widest font-semibold py-2 px-4 outline-none focus:outline-none transition duration-300 ease-in-out"
									:class="{
										'text-white bg-primary': form.subscription,
										'text-primary bg-transparent': !form.subscription,
									}"
								>
									Subscribe & save
								</button>
							</div>

							<div v-if="form.subscription" class="mb-2">
								<form-select id="subscription-option" name="subscription_option_id" v-model="form.subscription_option_id" class="text-primary uppercase bg-transparent text-xs tracking-widest font-semibold">
									<!-- <option value="">Select option</option> -->
									<option v-for="subscriptionOption in selectedVariant.subscription_options" :key="subscriptionOption.id" :value="subscriptionOption.id">
										Every {{ subscriptionOption.frequency }} week{{ subscriptionOption.frequency == 1 ? '' : 's' }} - ${{ formatPrice(subscriptionOption.price) }} per order
									</option>
								</form-select>
								<div v-if="form.errors.subscription_option_id" class="text-sm text-red-500 mt-1">{{ form.errors.subscription_option_id }}</div>
							</div>

							<!-- <button
								v-for="subscriptionOption in selectedVariant.subscription_options" :key="subscriptionOption.id"
								@click.prevent="form.subscription_option_id = subscriptionOption.id"
								class="w-full mb-2  text-center border border-primary text-xs tracking-widest font-semibold py-2 px-4 outline-none focus:outline-none transition duration-300 ease-in-out"
								:class="{
									'text-white bg-primary': form.subscription_option_id == subscriptionOption.id,
									'text-primary bg-transparent': form.subscription_option_id != subscriptionOption.id,
								}"
							>
								Every {{ subscriptionOption.frequency }} week{{ subscriptionOption.frequency == 1 ? '' : 's' }} - ${{ formatPrice(subscriptionOption.price) }} per delivery
							</button>

							<div class="font-bold mb-1">Or one time purchase</div>
							<button
								@click.prevent="form.subscription_option_id = ''"
								class="w-full mb-2 text-center border border-primary text-xs tracking-widest font-semibold py-2 px-4 outline-none focus:outline-none transition duration-300 ease-in-out"
								:class="{
									'text-white bg-primary': form.subscription_option_id == '',
									'text-primary bg-transparent': form.subscription_option_id != '',
								}"
							>
								One time purchase - ${{ formatPrice(selectedVariant.price) }}
							</button> -->
						</div>

						<!-- custom options -->
						<div v-if="form.variant_id && product.custom_options">
							<p class="mt-4">To create your custom cleanse please select your {{ product.custom_options_choices }} choices from the dropdown menus below.</p>
							<div class="mb-2" v-for="(custom_option, index) in form.custom_options" :key="index">
								<form-label :for="'choice_'+(index+1)" :value="'Choice '+(index+1)" />
								<form-select :id="'choice_'+(index+1)" v-model="form.custom_options[index]" class="text-primary uppercase bg-transparent text-xs tracking-widest font-semibold" required>
									<option value="">Choose item</option>
									<option v-for="product_item in product.product_items" :key="product_item.id" :value="product_item.title">{{ product_item.title }}</option>
								</form-select>
							</div>
						</div>

						<div class="p-4 mb-2 bg-lightbox" v-if="product.perishable && (!cart || !cart.location_id)">
							<location-form :message="false" :location-error="locationError"></location-form>
						</div>

						<!-- qty & button -->
						<div v-if="form.variant_id && selectedVariant">
							<div class="flex mb-2 ">
								<div class="flex border border-primary mr-1 text-center items-center text-primary text-md">
									<span @click="form.qty > 1 ? form.qty-- : true" class="w-10 cursor-pointer">-</span>
									<span class="h-full flex items-center border-l border-r border-primary"><span class="w-10">{{ form.qty }}</span></span>
									<span @click="form.qty++" class="w-10 cursor-pointer">+</span>
								</div>

								<button-primary v-if="form.variant_id" :class="{ 'disabled' : product.perishable && (!cart || !cart.location_id) }" class="w-full ml-1" style="padding-top: 0.5rem; padding-bottom: 0.5rem; justify-content: space-between;">Add to cart <span v-if="selectedVariant">${{ formatPrice(variantPrice()) }}</span></button-primary>
							</div>

							<div class="text-xs">or 4 payments from ${{ formatPrice(variantPrice() / 4) }} with Afterpay <img class="inline-block" src="/assets/icons/afterpay.svg"></div>
						</div>
					</form>

					<div v-else>
						<div class="uppercase tracking-widest text-sm mb-3">Contact Us</div>
						<contact-form :subject="'Enquiry for '+this.product.title" alert="Thanks for enquiring about this product. We'll be in touch soon." :grid="false"></contact-form>
					</div>
				</div>

				<!-- accordion -->
				<div class="border-b border-primary">
					<div v-for="(tab, index) in product.tabs" :key="index" class="border-t border-primary py-2">
						<a href="#" class="flex justify-between items-center text-primary uppercase text-xs py-2" @click.prevent="tab.open = !tab.open">
							<span>{{ tab.title }}</span>
							<img v-if="!tab.open" src="/assets/icons/plus.svg">
							<img v-else src="/assets/icons/minus-thin.svg">
						</a>

						<div v-if="tab.open && tab.title === 'Boost With'" class="mt-1 mb-2 flex justify-center flex-wrap">
							<div v-for="(recommended_product, index) in recommended_products" :key="recommended_product.id" class="w-1/2 flex flex-col text-center" :class="{ 'pr-6': index == 0, 'pl-6': index == 1 }">
								<product-grid :product="recommended_product" :simple="true"></product-grid>
							</div>
						</div>

						<div v-html="tab.content" v-else-if="tab.open" class="mt-1 mb-2"></div>
					</div>
				</div>
			</div>
		</section>

		<div v-for="section in product.content" :key="section.key">
			<hero-banner v-if="section.layout === 'hero_banner'" :content="section.attributes" class="mb-20"></hero-banner>
			<standard-content v-if="section.layout === 'standard_content'" :content="section.attributes" class="mb-20"></standard-content>
			<image-text v-if="section.layout === 'image_text'" :content="section.attributes" class="mb-20"></image-text>
			<image-text-columns v-if="section.layout === 'image_text_columns'" :content="section.attributes" class="mb-20"></image-text-columns>
			<columns v-if="section.layout === 'columns'" :content="section.attributes" class="mb-20"></columns>
			<tabs v-if="section.layout === 'tabs'" :content="section.attributes" class="mb-20"></tabs>
			<item-carousel v-if="section.layout === 'item_carousel'" :content="section.attributes" :swiper-key="section.key" class="mb-20"></item-carousel>
		</div>

		<section class="mb-20 container max-w-screen-lg mx-auto px-6">
			<div class="text-center mb-5">
				<a href="#" @click.prevent="tab = 'Reviews'" class="mx-2 font-serif text-3xl tracking-wider" :class="{ 'text-primary': tab === 'Reviews' }">Reviews</a>
				<a href="#" @click.prevent="tab = 'FAQ'" class="mx-2 font-serif text-3xl tracking-wider" :class="{ 'text-primary': tab === 'FAQ' }">FAQ's</a>
			</div>

			<div :class="{ 'hidden': tab !== 'Reviews' }">
                <product-review :product="product"></product-review>

            </div>
			<div :class="{ 'hidden': tab !== 'FAQ' }">
				<div class="bg-lightbox p-8 pb-6 mb-8" v-for="(faq, index) in product.faq" :key="index">
					<a href="#" @click.prevent="faq.show ? faq.show = false : faq.show = true" class="flex justify-between items-center mb-5">
						<div class="uppercase tracking-widest">{{ faq.attributes.heading }}</div>
						<img v-if="!faq.show" src="/assets/icons/plus.svg">
						<img v-else src="/assets/icons/minus-thick.svg">
					</a>
					<div v-if="faq.show" v-html="faq.attributes.content"></div>
				</div>
				<div class="bg-lightbox p-8 pb-6 mb-8" v-if="product.categories.length" v-for="(faq, index) in product.categories[0].faq" :key="index">
					<a href="#" @click.prevent="faq.show ? faq.show = false : faq.show = true" class="flex justify-between items-center mb-5">
						<div class="uppercase tracking-widest">{{ faq.attributes.heading }}</div>
						<img v-if="!faq.show" src="/assets/icons/plus.svg">
						<img v-else src="/assets/icons/minus-thick.svg">
					</a>
					<div v-if="faq.show" v-html="faq.attributes.content"></div>
				</div>
			</div>
		</section>

		<!-- <section class="mb-10 container mx-auto" v-if="recommended_products.length">
			<h2 class="text-center justify-center font-serif text-3xl tracking-wider mb-4 px-6">{{ product.product_recommendations_heading }}</h2>
			<div class="flex justify-center flex-wrap">
				<div v-for="recommended_product in recommended_products" :key="recommended_product.id" class="w-full md:w-1/4 p-6 flex flex-col text-center">
					<product-grid :product="recommended_product"></product-grid>
				</div>
			</div>
		</section> -->

		<section v-if="product.categories.length > 0 && product.categories[0].footer_quote" class="py-12 container mx-auto max-w-screen-lg px-6 text-center">
			<div class="font-serif text-xl tracking-wider mb-5" v-html="product.categories[0].footer_quote"></div>
			<div class="uppercase tracking-widest text-xs">Sol Cleanse</div>
		</section>

    </layout>
</template>

<script>
import emitter from "@/emitter";

import Layout from '@/Layouts/App'
import ButtonPrimary from '@/Components/ButtonPrimary'
import ButtonText from '@/Components/ButtonText'
import LocationPopup from '@/Sections/LocationPopup'
import LocationForm from '@/Sections/LocationForm'
import ContactForm from '@/Sections/ContactForm.vue';
import ProductGrid from '@/Sections/ProductGrid'

import HeroBanner from '@/Sections/HeroBanner'
import StandardContent from '@/Sections/StandardContent'
import ImageText from '@/Sections/ImageText'
import ImageTextColumns from '@/Sections/ImageTextColumns'
import Columns from '@/Sections/Columns'
import Tabs from '@/Sections/Tabs'
import ItemCarousel from '@/Sections/ItemCarousel'

import FormLabel from '@/Components/FormLabel'
import FormInput from '@/Components/FormInput'
import FormSelect from '@/Components/FormSelect'

import SwiperCore, { Thumbs } from 'swiper';
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/swiper.scss';


import ProductReview from "@/Sections/ProductReview";

// install Swiper's Thumbs component
SwiperCore.use([Thumbs]);

export default {
	props: ['product', 'recommended_products', 'cart'],

	computed: {
		// cart() {
		// 	return this.$page.props.cart
		// }
    },

	components: {
        ProductReview,
		Layout,
		ButtonPrimary,
		ButtonText,
		LocationPopup,
		LocationForm,
		ContactForm,
		ProductGrid,

		Swiper,
		SwiperSlide,

		HeroBanner,
		StandardContent,
		ImageText,
		ImageTextColumns,
		Columns,
		Tabs,
		ItemCarousel,

		FormLabel,
		FormInput,
		FormSelect
	},

	data() {
		return {

			thumbsSwiper: null,

			selectedVariant: this.product.variants[0],
			locationError: false,

			form: this.$inertia.form({
				variant_id: this.product.variants[0].id,
				subscription: false,
				subscription_option_id: '',
				qty: 1,
				mode: 'add',

				custom_options: [],
			}),

			tab: 'Reviews'
		}
	},

	created() {
		this.track();

		if (this.product.custom_options) {
			for (let i = 1; i <= this.product.custom_options_choices; i++) {
				this.form.custom_options.push('');
			}
		}

	},

	methods: {
		track() {
			// track product view
			dataLayer.push({'klav_product': {
				"ProductName": this.product.title,
				"ProductID": this.product.id,
				"SKU": this.selectedVariant.sku,
				"Categories": this.product.categories.map(category => category.title),
				"ImageURL": this.product.image_object.url,
				"URL": window.location.protocol+'//'+window.location.hostname+'/'+this.product.url,
				"Price": this.selectedVariant.price,
				//"CompareAtPrice": 14.99
			}});

			dataLayer.push({'klav_viewed_item': {
				"Title": this.product.title,
				"ItemId": this.product.id,
				"Categories": this.product.categories.map(category => category.title),
				"ImageUrl": this.product.image_object.url,
				"Url": window.location.protocol+'//'+window.location.hostname+'/'+this.product.url,
				"Metadata": {
					"Price": this.selectedVariant.price,
					//"CompareAtPrice": item.CompareAtPrice
				}
			}});

			dataLayer.push({'event': 'klaviyo_product'});
		},

		setThumbsSwiper(swiper) {
			this.thumbsSwiper = swiper;
		},

		formatPrice(value) {
			return value.toFixed(2)
		},

		selectVariant() {
			this.selectedVariant = this.product.variants.find(v => v.id == this.form.variant_id);

			if (this.form.subscription) {
				this.form.subscription_option_id = this.selectedVariant.subscription_options[0].id;
			} else {

			}
		},

		variantPrice() {
			if (this.form.subscription_option_id && this.selectedVariant && this.selectedVariant.subscription_options.length) {
				return this.selectedVariant.subscription_options.find(subscriptionOption => subscriptionOption.id == this.form.subscription_option_id).price;
			} else {
				return this.selectedVariant.price;
			}
		},

		addToCart() {
			// show error
			if (this.product.perishable && (!this.cart || !this.cart.location_id)) {
				this.locationError = true;
				return false;
			} else {
				this.locationError = false;
			}

			var dataLayerTemp = dataLayer;

			this.form.post('/cart', {
				preserveScroll: true,
				onSuccess: () => {
					emitter.emit('showCartSidebar');

					// track add to cart
					dataLayerTemp.push({'klav_cart': {
						"ProductName": this.product.title,
						"ProductID": this.product.id,
						"SKU": this.selectedVariant.sku,
						"ImageURL": this.product.image_object.url,
						"URL": window.location.protocol+'//'+window.location.hostname+'/'+this.product.url,
						"Price": this.selectedVariant.price,
					}});
					dataLayerTemp.push({'event': 'klaviyo_cart'});
				}
			});
		},

	}
}
</script>

<style>
.vertical-swiper {
	height: 100%;
}
</style>
