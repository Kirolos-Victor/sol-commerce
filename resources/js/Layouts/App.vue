<template>
    <div>

		<div v-if="settings && settings.announcement" class="bg-darkbox">
			<div class="container mx-auto flex items-center justify-center mt-0 px-6 py-1 text-sm" v-html="settings.announcement"></div>
		</div>

		<header v-if="$page.props.route !== 'checkout.index'" id="header" class="bg-light w-full z-30 sticky top-0">
			<div class="w-full container mx-auto flex items-center mt-0 px-6 py-6">
				<nav class="order-1 flex flex-1 mr-auto items-center w-auto w-full menu-border">
					<ul class="flex items-center justify-between uppercase text-xs tracking-widest">
						<li class="md:hidden mr-4">
							<a href="#" @click.prevent="showMenu = true" class="">
								<img src="/assets/icons/icon-menu.svg">
							</a>
						</li>
						<li>
							<a href="#" @click.prevent="showLocationSidebar" class="">
								<img class="md:hidden" src="/assets/icons/icon-pin.svg">

								<div class="hidden md:flex md:items-center">
									<span class="text-2xl mr-1" :style="cart && cart.location ? 'color: #a7ae9e;' : 'color: #C77C65;'">&bull;</span>
									<span v-if="cart && cart.location">{{ cart.non_perishable ? 'Aust. Wide' : cart.location.name }}</span>
									<span v-else>Select your location</span>
								</div>
							</a>
						</li>
					</ul>
				</nav>

				<div class="order-2">
					<inertia-link href="/" class="flex items-center tracking-wide no-underline hover:no-underline font-bold text-xl">
						<svg style="width: 180px;" class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 73.76"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path d="M107.51,46.78c0,4.36-3.45,7.29-8.59,7.34a17,17,0,0,1-7-1.54l.34-5.42h.24a.49.49,0,0,0,.24.05l.48,1.2A7,7,0,0,0,98.35,53a6.19,6.19,0,0,0,.86,0,4.13,4.13,0,0,0,4.27-4.46c0-1.63-1.82-3.55-4.6-4.84-4-1.78-6.62-4.66-6.62-7.15,0-4,3.31-6.62,8.29-6.62a12.85,12.85,0,0,1,5.86,1.78l-.43.76h0l.39-.76-.29,4.94h-.53l-.29-1.06a6.68,6.68,0,0,0-3.45-4.22,3.68,3.68,0,0,0-1.3-.34c-2.73,0-4.6,1.54-4.6,3.89,0,1.53,2.54,3.69,6.33,5.42C105.4,41.74,107.51,44.33,107.51,46.78Z"/><path d="M131.15,29.94c7.24,0,12,4.85,12,12.09s-4.8,12-12,12.09S119.06,49.27,119.06,42,123.9,29.94,131.15,29.94ZM137.86,42c0-6.57-2.68-11-6.71-11s-6.72,4.42-6.72,11,2.69,11,6.72,11S137.86,48.6,137.86,42Z"/><path d="M166.25,53.68c.05.34.05.34,0,.34H154.49v-.67l.53-.05c2.5,0,3-.48,3-3.12V23.56c-.1-.91-.68-.81-3-1.34l-.53-.1v-.43l7.34-2.21h1v30.7c0,2.64.44,3.12,2.93,3.12l.53.05C166.3,53.35,166.3,53.35,166.25,53.68Z"/><path d="M218.66,50.28l.43.57a17,17,0,0,1-9.84,3.27c-5.9,0-9.83-4.51-9.83-11.13,0-7.82,4.84-13,12.14-13,4.26,0,7.29,2,7.57,4.94A2,2,0,0,1,217,37a2.42,2.42,0,0,1-2.4-2.35A3.48,3.48,0,0,0,211,31.28c-3.94,0-6.57,3.94-6.57,9.84s3.5,10.07,8.77,10.07C215.87,51.19,218,50.85,218.66,50.28Z"/><path d="M242.15,53.68c0,.34,0,.34,0,.34H230.39v-.67l.53-.05c2.5,0,3-.48,3-3.12V23.56c-.1-.91-.68-.81-3-1.34l-.53-.1v-.43l7.34-2.21h1v30.7c0,2.64.43,3.12,2.93,3.12l.53.05S242.19,53.35,242.15,53.68Z"/><path d="M273,50.28l.43.57a17.22,17.22,0,0,1-10,3.27c-6,0-10-4.51-10-11.13,0-7.82,4.42-13,11-13,5.37,0,9,3.69,9,9.31H258.64c-.05.57-.1,1.2-.1,1.87,0,6,3.6,10.07,9,10.07C270.2,51.19,272.4,50.85,273,50.28Zm-14.3-12,8.21-.67a2.39,2.39,0,0,0,2.06-2.4v0c0-2.35-1.92-3.94-4.75-3.94S259.36,34,258.73,38.33Z"/><path d="M300.31,54.17a3.15,3.15,0,0,1-3.41-2.4A11.92,11.92,0,0,1,290,54.12c-3.4,0-5.66-2.07-5.66-5.09,0-3.4,3.41-6.14,8.54-6.91l3.74-.52V36c0-2.83-1.05-4.7-2.63-4.7-2,0-3.55,1.59-4,3.94h-4.8c.62-3.17,4.6-5.28,9.93-5.28,3.93,0,6.57,2.83,6.57,7.1v12c0,1.68.43,2.78,1.92,2.78s2-.19,2.25-.48l.53.67C305.3,53.3,303.67,54.17,300.31,54.17Zm-3.65-3.89V42.8l-3.74.81a4.31,4.31,0,0,0-3.74,4,3.07,3.07,0,0,0,3.26,3.26A13.81,13.81,0,0,0,296.66,50.28Z"/><path d="M341.89,53.68c-.05.34-.05.34,0,.34H330.13v-.67l.53-.05c2.45,0,2.93-.48,2.93-3.12V38.33c0-3.07-1.25-5.13-4.37-5.13a18.41,18.41,0,0,0-5.13.58v16.4c0,2.64.48,3.12,3,3.12l.53.05s0,0,0,.33,0,.34,0,.34H315.84v-.67l.53-.05c2.44,0,2.92-.48,2.92-3.12V35.12c0-2-.05-1.68-2.92-2.35l-.53-.1v-.43l7.29-2.2h1v3.11a16.53,16.53,0,0,1,8.83-3.21c3.93,0,5.46,3.17,5.46,7.87V50.18c0,2.64.48,3.12,3,3.12l.53.05C341.84,53.35,341.84,53.35,341.89,53.68Z"/><path d="M368.4,46.78c0,4.36-3.45,7.29-8.59,7.34a17,17,0,0,1-7.05-1.54l.34-5.42h.24a.49.49,0,0,0,.24.05l.48,1.2A7,7,0,0,0,359.24,53a6.19,6.19,0,0,0,.86,0,4.13,4.13,0,0,0,4.27-4.46c0-1.63-1.82-3.55-4.6-4.84-4-1.78-6.62-4.66-6.62-7.15,0-4,3.31-6.62,8.3-6.62a12.84,12.84,0,0,1,5.85,1.78l-.43.76h0l.39-.76L367,36.66h-.53l-.29-1.06a6.68,6.68,0,0,0-3.45-4.22,3.7,3.7,0,0,0-1.29-.34c-2.74,0-4.61,1.54-4.61,3.89,0,1.53,2.54,3.69,6.33,5.42C366.29,41.74,368.4,44.33,368.4,46.78Z"/><path d="M399.52,50.28l.43.57a17.22,17.22,0,0,1-10,3.27c-6,0-10-4.51-10-11.13,0-7.82,4.41-13,11-13,5.37,0,9,3.69,9,9.31H385.13c-.05.57-.1,1.2-.1,1.87,0,6,3.6,10.07,9,10.07C396.69,51.19,398.9,50.85,399.52,50.28Zm-14.29-12,8.2-.67a2.38,2.38,0,0,0,2.06-2.4v0c0-2.35-1.92-3.94-4.75-3.94S385.85,34,385.23,38.33Z"/><path d="M35.33,73.76a22,22,0,0,1-.73-3.63,49.3,49.3,0,0,1,.21-5c.1-1.8.21-3.6.35-5.4.06-.8.21-1.59.3-2.4a1.76,1.76,0,0,0,0-.79c-.53-1.33-1.06-2.66-1.67-3.94a33.45,33.45,0,0,1-2.19-8.2,8.76,8.76,0,0,0-.6-1.79,23.86,23.86,0,0,1-1.1-2.49,40,40,0,0,0-3-6.56c-1.75-3.6-4.65-5.43-7.89-6.68a18.41,18.41,0,0,0-6.49-1.1c-3.23,0-6.45-.2-9.67-.38a7.69,7.69,0,0,1-2.26-.62C.36,24.67,0,24.14,0,24c.16-.41.5-1,.84-1a15.68,15.68,0,0,1,3.35-.2,20.17,20.17,0,0,0,7.85-1.08,55.1,55.1,0,0,1,6.8-2.23,14.21,14.21,0,0,1,8,.33c2,.76,3.13,2.46,4.24,4.29,1.22,2,1.25,4.38,1.93,6.55a12.31,12.31,0,0,1,.08,2.73c.18,2.37.41,4.74.6,7.11.12,1.61.15,3.22.32,4.83.21,2.08.49,4.15.8,6.22,0,.27.42.48.64.71.13-.32.39-.65.36-1-.17-2.37-.46-4.74-.58-7.11C35,41.07,35,38,34.9,34.88c0-2.55-.11-5.11,0-7.66.19-4.39-.44-8.7-.75-13-.19-2.65-.23-5.31-.34-8-.05-1.29-.14-2.58-.18-3.88A6.5,6.5,0,0,1,33.75.81,3.17,3.17,0,0,1,34.18,0a3.83,3.83,0,0,1,.74.65,1.36,1.36,0,0,1,.24.85c-.23,2.7.44,5.29.68,7.93.26,2.87.28,5.77.34,8.66.06,3.81.06,7.62.08,11.44,0,2.35,0,4.71,0,7.06,0,.63.09,1.26.14,1.89l.37.07c.26-1.15.58-2.29.78-3.45.38-2.24.57-4.53,1.08-6.74A27.32,27.32,0,0,1,43.52,18a41.94,41.94,0,0,1,8.55-8.54,32.35,32.35,0,0,0,6.86-7.8c.29-.41.87-1,1.16-.89.69.2.72.93.6,1.69-.22,1.34-.38,2.7-.48,4.05a14.15,14.15,0,0,1-1.83,5.43c-.77,1.51-1.69,2.93-2.57,4.37a25.49,25.49,0,0,0-3.4,10.4,54.55,54.55,0,0,1-.77,5.45A12.78,12.78,0,0,1,50.06,36a63.93,63.93,0,0,1-4.77,6.56A70.48,70.48,0,0,1,39.05,49a5.91,5.91,0,0,0-1.8,4.24c-.27,3.29-.51,6.58-.63,9.87-.09,2.72.06,5.46,0,8.18a9.79,9.79,0,0,1-.71,2.44Zm2-25.07a4.58,4.58,0,0,0,.71-.42q2.27-2.12,4.52-4.26A17,17,0,0,0,44.2,42.3c1.18-1.55,2.23-3.21,3.45-4.72a12.93,12.93,0,0,0,2.75-5.85c.65-3.5,1.17-7,1.9-10.52A15.76,15.76,0,0,1,55,14.69a47.36,47.36,0,0,0,3.48-5.8,7.76,7.76,0,0,0,.56-4.47C57.12,6.34,55.19,8,53.51,10c-2,2.36-3.82,4.95-5.71,7.45A55.18,55.18,0,0,0,41,29.54a48.89,48.89,0,0,0-3.46,14.32c-.09.91-.19,1.81-.25,2.73C37.25,47.2,37.29,47.82,37.29,48.69ZM10.68,22.86l.05.33c2.33.75,4.65,1.52,7,2.23,3.86,1.18,7.36,3,9.8,6.61,1.27,1.85,2.21,3.95,3.3,5.94.15.27.32.53.61,1a8.81,8.81,0,0,0,.17-1c.08-2.88.36-5.78-.33-8.62a30.8,30.8,0,0,0-1.66-5.71,5.06,5.06,0,0,0-4.93-3.29,43.21,43.21,0,0,0-5.76.74C16.16,21.61,13.43,22.26,10.68,22.86Z"/></g></g></svg>
					</inertia-link>
				</div>

				<nav class="menu-border order-3 ml-auto flex flex-1 items-center justify-end">
					<ul class="flex items-center justify-between uppercase text-xs tracking-widest">
						<li class="mr-4">
							<a href="#" @click.prevent="showSearchSidebar">
								<img class="md:hidden" src="/assets/icons/icon-search.svg">
								<span class="hidden md:block">Search</span>
							</a>
						</li>
						<li class="hidden md:inline-block mr-4">
							<inertia-link href="/account/profile" class=""><span>Account</span></inertia-link>
						</li>
						<li>
							<a href="#" @click.prevent="showCartSidebar" class="no-underline">
								<img class="md:hidden" src="/assets/icons/icon-bag.svg">
								<div class="hidden md:block">Cart ({{ totals.count }})</div>
							</a>
						</li>
					</ul>
				</nav>
			</div>

			<transition name="fade">
				<div v-if="showMenu" class="fixed top-0 right-0 h-full w-full bg-black bg-opacity-50 z-40 transition-opacity"></div>
			</transition>

			<nav id="menu" class="
				fixed top-0 left-0 h-full bg-light shadow-lg w-96 border-l transition-position duration-500 z-50
				md:static md:flex md:items-center md:shadow-none md:w-full md:justify-center md:py-3 md:border-t md:border-b md:border-darkbox
			" :class="{ '-left-96': !showMenu }" v-click-away="showMenu ? onClickAway : false"
			>

				<div class="flex md:hidden p-6 justify-between items-center">
					<div class="font-serif text-4xl tracking-wider">Menu</div>
					<a href="#" @click.prevent="showMenu = false" class="text-xl">
						<svg class="w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14">
							<path id="Path_3" data-name="Path 3" d="M525.493,313.25l6.1-6.1a.525.525,0,0,0-.742-.742l-6.1,6.1-6.1-6.1a.525.525,0,0,0-.742.742l6.1,6.1-6.1,6.1a.525.525,0,1,0,.742.742l6.1-6.1,6.1,6.1a.525.525,0,0,0,.742-.742Z" transform="translate(-517.75 -306.25)" fill="#888"/>
						</svg>
					</a>
				</div>

				<ul class="menu-border md:flex items-center justify-between uppercase text-xs tracking-widest">
					<li class="mega-menu-hover border-t border-lightbox md:border-none px-6 md:px-4">
						<inertia-link href="/shop" class="py-4 md:py-0 block">Shop</inertia-link>
						<div class="mega-menu bg-light">
							<section class="container mx-auto flex justify-center flex-wrap text-left">
								<div class="w-full md:w-1/4 xl:w-1/8 md:p-6 md:text-center" v-for="category in menuCategories" :key="category.id">
									<inertia-link v-if="category.image_object" :href="'/category/'+category.url" class="hidden md:block"><img class="mb-2" :src="category.image_object.url"></inertia-link>
									<inertia-link :href="'/category/'+category.url"><h3 class="font-serif text-lg md:text-xl normal-case tracking-wider">{{ category.title }}</h3></inertia-link>
								</div>
							</section>
						</div>
					</li>

					<li class="border-t border-lightbox md:border-none px-6 md:px-4"><inertia-link href="/all-about-juice-cleansing" class="py-4 md:py-0 block">Why Cleanse</inertia-link></li>
					<li class="border-t border-lightbox md:border-none px-6 md:px-4"><inertia-link href="/clinical-nutritionist-consults" class="py-4 md:py-0 block">Nutritionist Consults</inertia-link></li>
					<li class="border-t border-lightbox md:border-none px-6 md:px-4"><inertia-link href="/pages/i-equals-change" class="py-4 md:py-0 block">I=Change</inertia-link></li>

					<li class="border-t border-lightbox md:border-none mega-menu-hover px-6 md:px-4">
						<inertia-link href="/journal" class="py-4 md:py-0 block">Journal</inertia-link>
						<div class="mega-menu bg-light">
							<section class="container mx-auto flex justify-center flex-wrap text-left">
								<div class="w-full md:w-1/4 xl:w-1/8 py-2 md:p-6 md:text-center" v-for="article in menuArticles" :key="article.id">
									<inertia-link v-if="article.image_object" :href="'/journal/'+article.url" class="hidden md:block"><div class="article-image mb-5" :style="'background-image: url('+article.image_object.url+')'"></div></inertia-link>
									<inertia-link :href="'/journal/'+article.url"><h3 class="font-serif text-lg md:text-xl normal-case tracking-wider">{{ article.title }}</h3></inertia-link>
								</div>
							</section>
						</div>
					</li>

					<li class="border-t border-lightbox md:border-none sub-menu-hover px-6 md:px-4">
						<inertia-link href="/about" class="py-4 md:py-0 block">About</inertia-link>

						<ul class="sub-menu bg-light absolute bottom-0">
							<li><inertia-link href="/about" class="py-4 md:py-0 block">About us</inertia-link></li>
                            <li><inertia-link href="/pages/impact" class="py-4 md:py-0 block">Impact</inertia-link></li>
                            <li><inertia-link href="/about-our-juice" class="py-4 md:py-0 block">About our Juice</inertia-link></li>
							<li><inertia-link href="/juice-cleanse-home-delivery" class="py-4 md:py-0 block">Delivery Details</inertia-link></li>
						</ul>
					</li>

					<!-- <li class="px-6 md:px-4"><inertia-link href="/faq" class="py-4 md:py-0 block">FAQ's</inertia-link></li>
					<li class="px-6 md:px-4"><inertia-link href="/recipes" class="py-4 md:py-0 block">Recipes</inertia-link></li> -->
				</ul>

			</nav>
		</header>

		<main id="main">
			<slot />
		</main>

		<footer v-if="$page.props.route !== 'checkout.index'" id="footer" class="">
			<section class="border-t py-12 border-darkbox">
				<div class="container mx-auto flex flex-wrap">

					<div class="mb-10 w-full md:w-1/2 xl:w-1/5 px-6 flex flex-col">
						<p>At Sol Cleanse, we create the highest quality organic cleanses to take people on a journey towards holistic health.</p>
						<p class="uppercase tracking-wide text-sm">&copy; sol cleanse 2021</p>

						<div class="flex items-center">
							<a href="https://www.facebook.com/solcleanse/" target="_blank" class="mr-3"><img src="/assets/icons/icon-facebook.svg" /></a>
							<a href="https://www.pinterest.com.au/solcleanse/" target="_blank" class="mr-3"><img src="/assets/icons/icon-pinterest.svg" /></a>
							<a href="https://www.instagram.com/solcleanse/" target="_blank"><img src="/assets/icons/icon-instagram.svg" /></a>
						</div>
					</div>

					<div class="mb-10 w-full md:w-1/2 xl:w-1/5 px-6 flex flex-col">
						<div class="uppercase tracking-widest text-xs mb-1">Shop</div>
						<nav v-html="settings.shop_menu"></nav>
					</div>

					<div class="mb-5 w-full md:w-1/2 xl:w-1/5 px-6 flex flex-col">
						<div class="uppercase tracking-widest text-xs mb-1">Customer care</div>
						<nav v-html="settings.customer_care_menu"></nav>
					</div>

					<div class="mb-5 w-full md:w-1/2 xl:w-1/5 px-6 flex flex-col">
						<div class="uppercase tracking-widest text-xs mb-1">About</div>
						<nav v-html="settings.about_menu"></nav>
					</div>

					<div class="mb-5 w-full md:w-1/2 xl:w-1/5 px-6 flex flex-col">
						<div class="uppercase tracking-widest text-xs mb-2">Sign up to our newsletter</div>
						<newsletter-form :grid="false"></newsletter-form>
					</div>

				</div>
                <hr>
                <div class="text-center mt-10 font-serif italic">
                    We respectfully acknowledge the Traditional Custodians of the land on which we work and live.<br>
                    We recognise their continuing connection to land, water and community and we pay our respects to Elders past, present and emerging.
                </div>
			</section>
		</footer>

		<location-sidebar></location-sidebar>
		<cart-sidebar></cart-sidebar>
		<search-sidebar></search-sidebar>

		<div class="bg-lightbox fixed bottom-0 w-full shadow-lg p-6 transition-transform duration-500 transform z-50 translate-y-0" :class="{ 'translate-y-full': !showNutritionistPopup }">
			<div class="container mx-auto flex flex-wrap items-center justify-center ">
				<!-- <div class="rounded-full w-20 h-20 bg-center bg-cover bg-white mr-5" style="background-image: url()"></div> -->
				<img src="/assets/nutritionist-circle.png" class="w-20 mr-5">
				<div class="mr-10">
					<h3 class="font-serif text-xl tracking-wider">Ask our nutritionist</h3>
					<div class="text-sm">Talk to our nutritionist about cleansing to your unique needs.</div>
				</div>
				<inertia-link href="/clinical-nutritionist-consults" class="block text-center uppercase border border-accent text-xs tracking-widest font-semibold py-3 px-8 outline-none transition duration-500 focus:outline-none hover:bg-accent">Find out more</inertia-link>
			</div>
			<a href="#" @click.prevent="dismissNutritionistPopup" class="absolute left-5 top-1/2 transform -translate-y-1/2">
				<svg class="w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14"><path id="Path_3" data-name="Path 3" d="M525.493,313.25l6.1-6.1a.525.525,0,0,0-.742-.742l-6.1,6.1-6.1-6.1a.525.525,0,0,0-.742.742l6.1,6.1-6.1,6.1a.525.525,0,1,0,.742.742l6.1-6.1,6.1,6.1a.525.525,0,0,0,.742-.742Z" transform="translate(-517.75 -306.25)" fill="#888"/></svg>
			</a>
		</div>

    </div>
</template>

<script>
import emitter from "@/emitter";
import { mixin as VueClickAway } from "vue3-click-away";

import LocationSidebar from '@/Sections/LocationSidebar'
import CartSidebar from '@/Sections/CartSidebar'
import SearchSidebar from '@/Sections/SearchSidebar'
import NewsletterForm from '@/Sections/NewsletterForm'

import debounce from 'lodash/debounce';

export default {
	props: ['settings'],

	components: {
		LocationSidebar,
		CartSidebar,
		SearchSidebar,
		NewsletterForm
	},

	mixins: [VueClickAway],

	computed: {
		settings() {
			return this.$page.props.settings
		},
		menuCategories() {
			return this.$page.props.menu_categories
		},
		cart() {
			return this.$page.props.cart
		},
		totals() {
			return this.$page.props.totals
		},
		menuArticles() {
			return this.$page.props.menu_articles
		}
    },

	data() {
		return {
			showMenu: false,
			showNutritionistPopup: false
		}
	},

	mounted() {
		if (!sessionStorage.getItem("dismissNutritionistPopup")) {
			this.handleDebouncedScroll = debounce(this.handleScroll, 100);
			window.addEventListener('scroll', this.handleDebouncedScroll);
		}
	},

	created() {

	},

	methods: {
		showLocationSidebar() {
			emitter.emit('showLocationSidebar');
		},

		showCartSidebar() {
			emitter.emit('showCartSidebar');
		},

		showSearchSidebar() {
			emitter.emit('showSearchSidebar');
		},

		onClickAway(event) {
			this.showMenu = false;
		},

		handleScroll(event) {
			if (window.scrollY > window.innerHeight) {
				this.showNutritionistPopup = true;
			}
		},

		dismissNutritionistPopup() {
			sessionStorage.setItem("dismissNutritionistPopup", true);
			this.showNutritionistPopup = false;
		}
	}
}
</script>
