<template>
    <layout>
	
		<section v-if="featured_article" class="bg-lightbox mb-20 py-10">
			<div class="container mx-auto flex flex-wrap items-center">
				<div class="w-full md:w-1/2 p-6 md:pr-12">
					<inertia-link :href="'/journal/'+featured_article.url"><img :src="featured_article.image_object.url" alt=""></inertia-link>
				</div>
				<div class="w-full md:w-1/2 p-6 md:pl-12">
					<h4 class="uppercase text-sm tracking-wider mb-2">Featured Article</h4>
					<inertia-link :href="'/journal/'+featured_article.url"><h2 class="font-serif text-3xl tracking-wider mb-5">{{ featured_article.title }}</h2></inertia-link>

					<div class="mb-5">
						<p v-html="featured_article.excerpt"></p>
					</div>

					<button-text :href="'/journal/'+featured_article.url">Read More</button-text>
				</div>
			</div>
		</section>

		<div class="fixed left-5 category-sidebar-fixed">
			<ul class="">
				<li class="my-3"><inertia-link :href="'/journal'" class="" :class="{ 'text-secondary border-b border-secondary': !category }">JOURNAL</inertia-link></li>
				<li v-for="categoryList in categories" :key="categoryList.id" class="my-3">				
					<inertia-link :href="'/journal/category/'+categoryList.url" class="" :class="{ 'text-secondary border-b border-secondary': category && categoryList.id == category.id }">{{ categoryList.title }}</inertia-link>
				</li>
			</ul>
		</div>

		<section class="mt-20 mb-10 container max-w-screen-md mx-auto px-6 text-center">
       		<h4 class="uppercase text-sm tracking-wider">See what we've been up to</h4>
			<h1 class="font-serif text-4xl tracking-wider mt-2 mb-4">{{ category ? category.title : 'Journal' }}</h1>

			<ul class="category-sidebar flex justify-center"> 
				<li v-for="categoryList in categories" :key="categoryList.id" class="my-1 mx-2">				
					<inertia-link :href="'/journal/category/'+categoryList.url" class="" :class="{ 'text-secondary border-b border-secondary': category && categoryList.id == category.id }">{{ categoryList.title }}</inertia-link>
				</li>
			</ul>

			<div v-if="category" v-html="category.description"></div>
		</section>

		<section class="mb-10 container mx-auto max-w-screen-lg flex flex-wrap">
			<div v-for="article in articles" :key="article.id" class="w-full sm:w-1/2 md:w-1/3 p-6 flex flex-col text-center">
				<inertia-link :href="'/journal/'+article.url">
					<div class="article-image mb-5" :style="'background-image: url('+article.image_object.url+')'"></div>
					<h4 v-if="article.categories.length" class="uppercase text-sm tracking-wider mb-2">{{ article.categories[0].title }}</h4>
					<h2 class="font-serif text-2xl tracking-wider mb-4">{{ article.title }}</h2>
					<div class="">
						<p>{{ truncate(article.excerpt) }}</p>
					</div>
				</inertia-link>
			</div>
		</section>

    </layout>
</template>

<script>
import Layout from '@/Layouts/App'
import ButtonText from '@/Components/ButtonText'

export default {
	props: ['featured_article', 'articles', 'categories', 'category'],

	components: {
		Layout,
		ButtonText
	},

	data() {
		return {

		}
	},

	created() {

	},

	methods: {
		truncate(text) {
			return _.truncate(text, {
			'length': 100,
			})
		}
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