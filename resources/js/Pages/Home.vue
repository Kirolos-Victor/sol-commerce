<template>
    <layout>

		<section class="mb-20 md:mb-40">

			<div class="relative">
				<swiper class="swiper-hero" :slides-per-view="1" :navigation="{ nextEl: '.swiper-hero .swiper-button-next', prevEl: '.swiper-hero .swiper-button-prev' }" @swiper="onSwiper" @slideChange="onSlideChange">
					<swiper-slide>
						<div class="home-slide bg-cover bg-center" style="background-image: url(https://solcleanse.com/wp-content/uploads/2020/06/organic-cold-pressed-juice-cleanse.jpg)"></div>
					</swiper-slide>
					<swiper-slide>
						<div class="home-slide bg-cover bg-center" style="background-image: url(https://solcleanse.com/wp-content/uploads/2020/06/organic-cold-pressed-juice-cleanse.jpg)"></div>
					</swiper-slide>
					<div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
				</swiper>
			</div>

			<div class="carousel-overlay py-10 px-10 border-b border-darkbox bg-light md:border z-10 text-center">
				<h4 class="uppercase text-sm tracking-wider">Nourish & Transform the deepest parts of you</h4>
				<h2 class="font-serif text-3xl tracking-wider mt-2 mb-4">Organic Cleanse Programs</h2>
				<button-text href="/category/organic-cleanses">Order Now</button-text>
			</div>

			<!-- <div class="md:mt-20 py-20 md:py-40 text-center text-secondary">
				<a href="#" class="uppercase" @click.prevent="scrollToElement('explore')">
					<div class="tracking-widest text-sm mb-2">Explore</div>
					<svg width="40" class="explore-icon inline-block fill-current text-secondary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 48.57"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path d="M23.26,48.57a15.33,15.33,0,0,1-.48-2.39c-.05-1.09.08-2.19.14-3.29s.14-2.37.23-3.55c0-.53.14-1.06.2-1.58a1.19,1.19,0,0,0,0-.53c-.35-.87-.7-1.75-1.1-2.6a21.68,21.68,0,0,1-1.44-5.39,6.08,6.08,0,0,0-.4-1.18,15.71,15.71,0,0,1-.72-1.64,27.19,27.19,0,0,0-2-4.32,8.87,8.87,0,0,0-5.19-4.4A11.89,11.89,0,0,0,8.27,17C6.15,17,4,16.85,1.9,16.73a5,5,0,0,1-1.48-.41C.24,16.25,0,15.9,0,15.8c.11-.27.34-.64.56-.68A10.85,10.85,0,0,1,2.77,15a13.38,13.38,0,0,0,5.17-.7,33,33,0,0,1,4.48-1.47,9.32,9.32,0,0,1,5.23.21,6,6,0,0,1,2.8,2.82c.8,1.33.82,2.89,1.27,4.32a8.23,8.23,0,0,1,.05,1.8c.12,1.56.27,3.12.39,4.68.09,1.06.11,2.12.21,3.18.14,1.37.33,2.73.54,4.09,0,.18.27.32.41.48.09-.22.26-.43.24-.64-.11-1.56-.3-3.12-.38-4.68C23.07,27,23,25,23,23c0-1.68-.07-3.37,0-5a74.57,74.57,0,0,0-.49-8.59c-.13-1.74-.15-3.49-.22-5.24,0-.85-.1-1.7-.12-2.55a4.72,4.72,0,0,1,.07-1A2,2,0,0,1,22.51,0,2.46,2.46,0,0,1,23,.43a1,1,0,0,1,.17.56,28.52,28.52,0,0,0,.44,5.22c.17,1.89.19,3.8.22,5.7.05,2.51,0,5,.06,7.53,0,1.55,0,3.1,0,4.66,0,.41.06.83.09,1.24l.24.05c.18-.76.38-1.51.51-2.27.26-1.48.38-3,.72-4.44a18,18,0,0,1,3.2-6.83,27.56,27.56,0,0,1,5.63-5.63,21.23,21.23,0,0,0,4.52-5.13c.18-.27.57-.65.76-.59.45.13.47.61.39,1.11-.14.89-.25,1.78-.31,2.67a9.17,9.17,0,0,1-1.21,3.57c-.5,1-1.11,1.94-1.69,2.89a16.85,16.85,0,0,0-2.24,6.84A33,33,0,0,1,34,21.17a8.16,8.16,0,0,1-1,2.55A40.71,40.71,0,0,1,29.82,28a47.09,47.09,0,0,1-4.1,4.25,3.84,3.84,0,0,0-1.19,2.79c-.18,2.16-.34,4.33-.41,6.5-.07,1.79,0,3.59,0,5.39a6.56,6.56,0,0,1-.46,1.6Zm1.29-16.51a3.1,3.1,0,0,0,.47-.27c1-.93,2-1.87,3-2.81a9.79,9.79,0,0,0,1.1-1.13c.78-1,1.48-2.11,2.28-3.11a8.43,8.43,0,0,0,1.81-3.84c.42-2.31.77-4.64,1.25-6.93a10.39,10.39,0,0,1,1.78-4.3,29.76,29.76,0,0,0,2.3-3.82,5.05,5.05,0,0,0,.36-2.94c-1.26,1.27-2.54,2.38-3.64,3.67-1.33,1.56-2.52,3.26-3.77,4.91a36.34,36.34,0,0,0-4.47,8,32.06,32.06,0,0,0-2.28,9.43c-.06.6-.13,1.2-.16,1.8C24.53,31.08,24.55,31.49,24.55,32.06ZM7,15.05l0,.22c1.53.49,3.06,1,4.6,1.47a11.38,11.38,0,0,1,6.45,4.35A42.39,42.39,0,0,1,20.29,25c.1.18.21.35.41.67a5.7,5.7,0,0,0,.11-.66,21.18,21.18,0,0,0-.22-5.68,20.36,20.36,0,0,0-1.09-3.76,3.32,3.32,0,0,0-3.25-2.16,30.2,30.2,0,0,0-3.79.48C10.64,14.23,8.84,14.66,7,15.05Z"/></g></g></svg>
				</a>
			</div> -->

		</section>

		<!--
		Alternatively if you want to just have a single hero
		<section class="w-full mx-auto bg-nordic-gray-light flex pt-12 md:pt-0 md:items-center bg-cover bg-right" style="max-width:1600px; height: 32rem; background-image: url('https://images.unsplash.com/image-1422190441165-ec2956dc9ecc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1600&q=80');">
			<div class="container mx-auto">
				<div class="flex flex-col w-full lg:w-1/2 justify-center items-start  px-6 tracking-wide">
					<h1 class="text-black text-2xl my-4">Stripy Zig Zag Jigsaw Pillow and Duvet Set</h1>
					<a class="text-xl inline-block no-underline border-b border-gray-600 leading-relaxed hover:text-black hover:border-black" href="#">products</a>
				</div>
			</div>
		</section>
		-->

		<!-- <div ref="explore"></div> -->

		<section class="mb-10 md:mb-20 container mx-auto flex flex-wrap">
			<div class="w-full sm:w-1/2 xl:w-1/4 p-6 text-center" v-for="category in categories" :key="category.id">
				<inertia-link v-if="category.image_object" :href="'/category/'+category.url"><img class="mb-5" :src="category.image_object.url"></inertia-link>
				<inertia-link :href="'/category/'+category.url"><h2 class="font-serif text-2xl tracking-wider mb-2">{{ category.title }}</h2></inertia-link>
			</div>
		</section>

		<standard-content class="mb-10 md:mb-20 text-center" :content="{
			preheading: 'OUR ORGANIC GOODNESS RECONNECTS YOU TO YOUR BODY + MIND',
			heading: 'Sol Cleanse All Natural Pressed Juice Cleanses',
			content: 'A juice cleanse is a unique experience that exposes your habits & shadows. Great shifts occur when we dedicate time and space for deep healing, not just from the power of plant nutrition, but also the intention and the ritual behind a cleanse.',
			button_text: 'Read More',
			button_url: '/shop'
		}">
		</standard-content>

		<section class="mb-10 container mx-auto flex flex-wrap">
			<div class="w-full md:w-1/2 p-6 md:pr-12">
				<h2 class="font-serif text-3xl tracking-wider mt-4 mb-4">Where does Sol Cleanse deliver to?</h2>

				<div class="mb-5">
					<p>Because our juices and elixirs are fresh and unpasteurised without the use of any nasty additives or preservatives, they have a four-day shelf life.</p>
					<p>Our juice temple on the Gold Coast delivers to Gold Coast, Brisbane, Northern NSW, Sunshine Coast + beyond.</p>
					<p>Our juice temple in Sydney delivers to to the Sydney CBD & Eastern Suburbs, North Sydney, St George, Inner Western Suburbs, The Shire and Wollongong. All of our non-perishable items are available for purchase Australia wide and can be purchased from either store.</p>
				</div>

				<button-text :href="'/juice-cleanse-home-delivery'">Read More</button-text>
			</div>
			<div class="w-full md:w-1/2 p-6 md:pl-12 text-center">
				<div :class="{ 'hidden': kitchen === 'Gold Coast' }"><iframe class="w-full" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3310.849326528917!2d151.1530391152115!3d-33.91927728064224!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6b12b08903feaf43%3A0x459283d40d4fb0d2!2s16%2F49%20Carrington%20Rd%2C%20Marrickville%20NSW%202204!5e0!3m2!1sen!2sau!4v1636681645542!5m2!1sen!2sau" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe></div>
				<div :class="{ 'hidden': kitchen === 'Sydney' }"><iframe class="w-full" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3517.845008193827!2d153.4686124161192!3d-28.15120615277001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6b910211b12394bf%3A0x5b8f8fc2e16a605e!2sSol%20Cleanse%20Organic%20Cold%20Pressed%20Juice!5e0!3m2!1sen!2sau!4v1621987349892!5m2!1sen!2sau" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe></div>

				<div class="mt-5">
					<a href="#" @click.prevent="kitchen = 'Gold Coast'" class="block md:inline-block mx-2 font-serif text-2xl tracking-wider" :class="{ 'text-primary': kitchen === 'Gold Coast' }">Gold Coast Kitchen</a>
					<a href="#" @click.prevent="kitchen = 'Sydney'" class="block md:inline-block mx-2 font-serif text-2xl tracking-wider" :class="{ 'text-primary': kitchen === 'Sydney' }">Sydney Kitchen</a>
				</div>
			</div>
		</section>

		<section class="mb-10 md:mb-20 container mx-auto flex flex-wrap items-center">
			<div class="w-full md:w-1/2 p-6 md:pr-12">
				<img src="https://solcleanse.com/wp-content/uploads/2019/09/Larrissa-Cunningham-Miriam-Ter-Borg-Sol-Cleanse.jpg" alt="">
			</div>
			<div class="w-full md:w-1/2 p-6 md:pl-12">
				<h2 class="font-serif text-3xl tracking-wider mt-4 mb-4">Ask our Nutritionist</h2>

				<div class="mb-5">
					<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est</p>
				</div>

				<button-text :href="'/clinical-nutritionist-consults'">Find Out More</button-text>
			</div>
		</section>

		<section class="mb-20 container mx-auto px-6 text-center">
			<h4 class="uppercase text-sm tracking-wider">SEE WHAT WE’VE BEEN UP TO</h4>
			<h2 class="font-serif text-3xl tracking-wider mt-2 mb-4">Latest from the blog</h2>

			<div class="swiper-articles relative">
				<swiper class="" :navigation="{ nextEl: '.swiper-articles .swiper-button-next', prevEl: '.swiper-articles .swiper-button-prev' }" :breakpoints="{ 1000: { slidesPerView: 4, spaceBetween: 40 }, 768: { slidesPerView: 2, spaceBetween: 20 }, 500: { slidesPerView: 1, spaceBetween: 20 } }">
					<swiper-slide v-for="article in articles" :key="article.id">
						<div class="article-image mb-5" :style="'background-image: url('+article.image_object.url+')'"></div>
						<h4 v-if="article.categories.length" class="uppercase text-sm tracking-wider mb-2">{{ article.categories[0].title }}</h4>
						<h2 class="font-serif text-2xl tracking-wider mb-4">{{ article.title }}</h2>
					</swiper-slide>
				</swiper>
				<div class="swiper-navigation-outside">
					<div class="swiper-button-next"></div>
					<div class="swiper-button-prev"></div>
				</div>
			</div>
		</section>

		<section class="mb-20 md:mb-40 container mx-auto text-center">
			<h2 class="font-serif text-3xl tracking-wider px-6 mt-2 mb-4">
				Follow us on Instagram<br>
				<a href="https://www.instagram.com/solcleanse" class="text-primary" target="_blank">@solcleanse</a>
			</h2>

			<div class="flex items-center flex-wrap">
				<div class="w-1/3 md:w-1/6 p-6 flex flex-col text-center">
					<img src="https://solcleanse.com/wp-content/uploads/2018/03/Custom-organic-juice-pack-delivered.jpg">
				</div>
				<div class="w-1/3 md:w-1/6 p-6 flex flex-col text-center">
					<img src="https://solcleanse.com/wp-content/uploads/2018/03/Custom-organic-juice-pack-delivered.jpg">
				</div>
				<div class="w-1/3 md:w-1/6 p-6 flex flex-col text-center">
					<img src="https://solcleanse.com/wp-content/uploads/2018/03/Custom-organic-juice-pack-delivered.jpg">
				</div>
				<div class="w-1/3 md:w-1/6 p-6 flex flex-col text-center">
					<img src="https://solcleanse.com/wp-content/uploads/2018/03/Custom-organic-juice-pack-delivered.jpg">
				</div>
				<div class="w-1/3 md:w-1/6 p-6 flex flex-col text-center">
					<img src="https://solcleanse.com/wp-content/uploads/2018/03/Custom-organic-juice-pack-delivered.jpg">
				</div>
				<div class="w-1/3 md:w-1/6 p-6 flex flex-col text-center">
					<img src="https://solcleanse.com/wp-content/uploads/2018/03/Custom-organic-juice-pack-delivered.jpg">
				</div>
			</div>
		</section>

		<section class="bg-newsletter">
			<div class="container max-w-screen-sm mx-auto px-6 py-20 text-center">
				<h4 class="uppercase text-sm tracking-wider">Sign up to our newsletter</h4>
				<h2 class="font-serif text-3xl tracking-wider mt-2 mb-8">Never miss a thing</h2>
				<newsletter-form></newsletter-form>
			</div>
		</section>

    </layout>
</template>

<script>
import Layout from '@/Layouts/App'
import ButtonText from '@/Components/ButtonText'

import StandardContent from '@/Sections/StandardContent'
import NewsletterForm from '@/Sections/NewsletterForm'

import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/swiper.scss';
import VueInstagram from 'vue-instagram'


export default {
	props: ['categories', 'articles'],

	components: {
		Layout,
		Swiper,
		SwiperSlide,

		ButtonText,

		StandardContent,
		NewsletterForm,
        VueInstagram
	},

	data() {
		return {
			showingNavigationDropdown: false,

			kitchen: 'Gold Coast',
		}
	},

	methods: {
		onSwiper(swiper) {
			console.log(swiper.activeIndex);
		},

		onSlideChange(swiper) {
			console.log(swiper.activeIndex);
		},

		scrollToElement(ref) {
			const el = this.$refs[ref];

			if (el) {
				el.scrollIntoView({behavior: 'smooth'});
			}
		}
	},

}
</script>

<style scoped>
.explore-icon {
	position: relative;
	transition: all 0.3s ease;
}
.explore-icon:hover {
	transform: translateY(10px);
}
</style>
