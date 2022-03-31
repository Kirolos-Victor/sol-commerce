<template>
    <layout>

		<div class="fixed left-5 category-sidebar-fixed">
			<ul class="">
				<li class="my-3"><inertia-link :href="'/journal'" class="" :class="{ 'text-secondary border-b border-secondary': !category }">JOURNAL</inertia-link></li>
				<li v-for="categoryList in categories" :key="categoryList.id" class="my-3">				
					<inertia-link :href="'/journal/category/'+categoryList.url" class="" :class="{ 'text-secondary border-b border-secondary': category && categoryList.id == category.id }">{{ categoryList.title }}</inertia-link>
				</li>
			</ul>
		</div>

		<section class="container mx-auto flex flex-wrap">
			<div class="w-full md:w-3/4 p-6">
				<div class="mt-20 mb-10 container max-w-screen-md ml-auto">
					<div class="mb-6 text-xs tracking-widest uppercase">
						<inertia-link href="/journal/">Journal</inertia-link>
						<span v-if="article.categories.length" class="px-3">></span>
						<inertia-link v-if="article.categories.length" :href="'/journal/'+article.categories[0].url">{{ article.categories[0].title }}</inertia-link>
					</div>

					<h1 class="font-serif text-4xl tracking-wider mb-5">{{ article.title }}</h1>
					<div class="text-xs tracking-widest uppercase">{{ moment(article.created_at).format('Do MMMM YYYY') }}</div>
				</div>
			</div>
		</section>

		<section class="mb-20 container mx-auto flex flex-wrap">
			<div class="w-full md:w-3/4 p-6">
				<div class="mb-10" :class="{ 'container max-w-screen-md ml-auto': article.contain_image }">
					<img :src="article.image_object.url" class="w-full">
				</div>

				<div class="container max-w-screen-md ml-auto">
					<div v-if="article.content" v-html="article.content" class="wysiwyg"></div>

					<div class="mt-16 flex items-center">
						<div class="uppercase text-sm tracking-wider mr-4">Share</div>
						<a :href="'https://www.facebook.com/sharer/sharer.php?u='+url" target="_blank"><img src="/assets/icons/icon-facebook.svg" class="mr-3"></a>
						<!-- <img src="/assets/icons/icon-instagram.svg" class="mr-3"> -->
						<a :href="'http://pinterest.com/pin/create/button/?url='+url+'&media=&description='+article.title" target="_blank"><img src="/assets/icons/icon-pinterest.svg" class=""></a>
					</div>

					<div class="border-darkbox border-t mt-8 pt-16 flex items-center justify-between">
						<div><inertia-link v-if="previous_article" :href="'/journal/'+previous_article.url" class="flex items-center uppercase text-sm tracking-wider"><img src="/assets/icons/icon-arrow-right.svg" class="mr-2 transform rotate-180"> Previous</inertia-link></div>
						<div><inertia-link v-if="next_article" :href="'/journal/'+next_article.url" class="flex items-center uppercase text-sm tracking-wider">Next <img src="/assets/icons/icon-arrow-right.svg" class="ml-2"></inertia-link></div>
					</div>
				</div>
			</div>
			
			<div class="w-full md:w-1/4 p-6">
				<div v-if="article.products.length" class="mb-6">
					<h4 class="uppercase text-sm tracking-wider mb-4">Featured Products</h4>
					<div v-for="product in article.products" :key="product.id">
						<div class="flex justify-between mb-3">
							<inertia-link :href="'/product/'+product.url"><img :src="product.image_object.url" width="80"></inertia-link>

							<div class="flex-1 pl-3">
								<inertia-link :href="'/product/'+product.url" class="text-xl font-serif">{{ product.title }}</inertia-link>

								<div class="text-primary">
									<div class="text-lg mr-2">&starf;&starf;&starf;&starf;&starf;</div>
									<div class="text-xs">4.9 stars from 106 reviews</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<ul class="category-sidebar"> 
					<li class="my-3"><inertia-link :href="'/journal'" class="" :class="{ 'text-secondary border-b border-secondary': !category }">JOURNAL</inertia-link></li>
					<li v-for="categoryList in categories" :key="categoryList.id" class="my-3">				
						<inertia-link :href="'/journal/category/'+categoryList.url" class="" :class="{ 'text-secondary border-b border-secondary': category && categoryList.id == category.id }">{{ categoryList.title }}</inertia-link>
					</li>
				</ul>
			</div>
		</section>

		<section class="mb-20 container mx-auto flex flex-wrap text-center" v-if="related_articles">
			<div class="w-full"><h2 class="font-serif text-3xl tracking-wider mb-4">Related articles</h2></div>
			<div v-for="related_article in related_articles" :key="related_article.id" class="w-full md:w-1/2 md:w-1/4 p-6 flex flex-col text-center">
				<inertia-link :href="'/journal/'+article.url">
					<div class="article-image mb-5" :style="'background-image: url('+related_article.image_object.url+')'"></div>
					<h4 v-if="related_article.categories.length" class="uppercase text-sm tracking-wider mb-2">{{ related_article.categories[0].title }}</h4>
					<h2 class="font-serif text-2xl tracking-wider mb-4">{{ related_article.title }}</h2>
				</inertia-link>
			</div>
		</section>

    </layout>
</template>

<script>
import Layout from '@/Layouts/App'

import moment from 'moment'

export default {
	props: ['article', 'categories', 'previous_article', 'next_article', 'related_articles'],

	computed: {

    },

	components: {
		Layout,
	},

	data() {
		return {
			url: window.location
		}
	},

	created() {
		this.moment = moment;
	},

	methods: {

	}
}
</script>

<style scoped>
.category-sidebar-fixed {
	top: 20vh;
}

@media (min-width: 1801px) {
	.category-sidebar {
		display: none;
	}
}
@media (max-width: 1800px) {
	.category-sidebar-fixed {
		display: none;
	}
}
</style>