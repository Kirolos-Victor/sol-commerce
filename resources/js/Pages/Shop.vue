<template>
    <layout>
	
		<!-- <location-box v-if="!cart || !cart.location_id"></location-box> -->
		<location-popup :product="product"></location-popup>

		<!-- <div class="fixed left-5 category-sidebar">
			<ul class="">
				<li class="my-3"><inertia-link :href="'/shop'" class="" :class="{ 'text-secondary border-b border-secondary': !category }">SHOP</inertia-link></li>

				<li v-for="categoryList in categories" :key="categoryList.id" class="my-3">				
					<inertia-link :href="'/category/'+categoryList.url" class="" :class="{ 'text-secondary border-b border-secondary': category && categoryList.id == category.id }">{{ categoryList.title }}</inertia-link>

					<ul class="ml-5" v-if="categoryList.subcategories">
						<li v-for="subcategoryList in categoryList.subcategories" :key="subcategoryList.id" class="my-2">
							<inertia-link :href="'/category/'+subcategoryList.url" class="" :class="{ 'text-secondary border-b border-secondary': category && subcategoryList.id == category.id }">{{ subcategoryList.title }}</inertia-link>
						</li>
					</ul>
				</li>
			</ul>
		</div> -->

		<section class="mt-20 mb-10 container max-w-screen-md mx-auto px-6 text-center">
			<h1 class="font-serif text-4xl tracking-wider mt-2 mb-4">
				<span v-if="search">Search results for {{ search }}</span>
				<span v-else-if="category">{{ category.title }}</span>
				<span v-else>Shop</span>
			</h1>

			<div v-if="category" v-html="category.description"></div>

			<form v-if="search" @submit.prevent="processSearch">
				<input id="search" type="text" placeholder="Search products" v-model="form.search" class="text-sm bg-transparent border-black p-0 py-1 border-0 border-b w-64 focus:border-black focus:ring-0" required />
				<button type="submit" class="-ml-3">
					<svg xmlns="http://www.w3.org/2000/svg" width="16.779" height="17.601" viewBox="0 0 16.779 17.601">
						<g id="Icon_feather-search" data-name="Icon feather-search" transform="translate(-12.546 -11.546)">
							<path id="Path_46" data-name="Path 46" d="M16.179,10.34A5.84,5.84,0,1,1,10.34,4.5a5.84,5.84,0,0,1,5.84,5.84Z" transform="translate(8.796 7.796)" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
							<path id="Path_47" data-name="Path 47" d="M30.34,30.34l-5.365-5.365" transform="translate(-2.075 -2.253)" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
						</g>
					</svg>
				</button>
			</form>

		</section>

		<section class="mb-10 container mx-auto max-w-screen-lg flex flex-wrap">
			<div v-for="product in products" :key="product.id" class="w-full md:w-1/3 p-6 flex flex-col text-center">
				<product-grid :product="product"></product-grid>
			</div>
		</section>

		<section v-if="category && category.footer_quote" class="py-12 container mx-auto max-w-screen-lg px-6 text-center">
			<div class="font-serif text-xl tracking-wider mb-5" v-html="category.footer_quote"></div>
			<div class="uppercase tracking-widest text-xs">Sol Cleanse</div>
		</section>

    </layout>
</template>

<script>
import Layout from '@/Layouts/App'
import ProductGrid from '@/Sections/ProductGrid'
import LocationPopup from '@/Sections/LocationPopup'
import ButtonPrimary from '@/Components/ButtonPrimary'

export default {
	props: ['products', 'categories', 'category'],

	computed: {
		cart() {
			return this.$page.props.cart
		},
		search() {
			return this.$page.props.search
		}
    },

	components: {
		Layout,
		ProductGrid,
		LocationPopup,
		ButtonPrimary,
	},

	data() {
		return {
			form: this.$inertia.form({
				search: ''
			})
		}
	},

	created() {
		// if (this.search) {
		// 	this.form.search = this.search;
		// }

		this.track();
	},

	methods: {
		track() {
			if (this.category) {
				dataLayer.push({'klav_cat': {
					"CategoryName": this.category.title,
					"CategoryID": this.category.id,
					"ImageURL": this.category.image_object.url,
					"URL": window.location.protocol+'//'+window.location.hostname+'/'+this.category.url
				}});

				dataLayer.push({'event': 'klaviyo_cat'});
			}
		},

		formatPrice(value) {
			return value.toFixed(2)
		},

		processSearch() {
			this.$inertia.visit('/shop?search='+this.form.search);
		}
	}
}
</script>

<style scoped>
.category-sidebar {
	top: 20vh;
}
</style>