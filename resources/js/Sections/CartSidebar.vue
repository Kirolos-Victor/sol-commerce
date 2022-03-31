<template>
	<transition name="fade">
		<div v-if="show" class="fixed top-0 right-0 h-full w-full bg-black bg-opacity-50 z-40 transition-opacity"></div>
	</transition>

    <div class="fixed top-0 right-0 h-full bg-light shadow-lg w-96 border-l transition-position duration-500 z-50" :class="{ '-right-96': !show }" v-click-away="show ? onClickAway : false">
		
		<div class="p-6 flex justify-between items-center">
			<div class="font-serif text-4xl tracking-wider">Cart</div>
			<a href="#" @click.prevent="show = false" class="text-xl">
				<svg class="w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14">
					<path id="Path_3" data-name="Path 3" d="M525.493,313.25l6.1-6.1a.525.525,0,0,0-.742-.742l-6.1,6.1-6.1-6.1a.525.525,0,0,0-.742.742l6.1,6.1-6.1,6.1a.525.525,0,1,0,.742.742l6.1-6.1,6.1,6.1a.525.525,0,0,0,.742-.742Z" transform="translate(-517.75 -306.25)" fill="#888"/>
				</svg>
			</a>
		</div>
		
		<div v-if="cart" class="overflow-y-auto cart-items">
			<div v-for="item in cart.cart_items" :key="item.id">
				<div class="flex justify-between px-6 my-3 py-3">
					<inertia-link :href="'product/'+item.variant.id"><img :src="item.variant.product.image_object.url" width="80"></inertia-link>

					<div class="flex-1 pl-3">
						<div class="flex justify-between items-center mb-1">
							<inertia-link :href="'product/'+item.variant.id" class="">{{ item.variant.title }}</inertia-link>
							<a href="#" @click.prevent="removeItem(item)" class="text-sm">
								<svg class="w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14">
									<path id="Path_3" data-name="Path 3" d="M525.493,313.25l6.1-6.1a.525.525,0,0,0-.742-.742l-6.1,6.1-6.1-6.1a.525.525,0,0,0-.742.742l6.1,6.1-6.1,6.1a.525.525,0,1,0,.742.742l6.1-6.1,6.1,6.1a.525.525,0,0,0,.742-.742Z" transform="translate(-517.75 -306.25)" fill="#888"/>
								</svg>
							</a>
						</div>

						<div v-if="item.subscription_option_id">
							<div class="text-primary italic">Renews every {{ item.subscription_option.frequency }} week{{ item.subscription_option.frequency == 1 ? '' : 's' }}</div>
						</div>

						<div class="flex justify-between items-center mb-1">
							<div>${{ item.price }}</div>

							<div class="border border-primary">
								<span @click="subQty(item)" class="cursor-pointer px-2 inline-block">-</span>
								<span class="px-1 inline-block">{{ item.qty }}</span>
								<!-- <input type="number" name="qty" v-model="item.qty" min="1" class="w-14 h-8 text-sm text-center border-primary focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-25 px-2 mx-2"> -->
								<span @click="addQty(item)" class="cursor-pointer px-2 inline-block">+</span> 
							</div>
						</div>

						<div v-if="!item.subscription_option_id && item.variant.subscription_options.length">
							<div @click="item.expanded = !item.expanded" class="my-2 cursor-pointer uppercase tracking-widest border-b font-semibold border-secondary pb-1 text-xs inline-flex items-center">Subscribe & save up to 15% <img src="/assets/icons/icon-arrow-right.svg" class="ml-1 transform" :class="{ 'rotate-90': !item.expanded, '-rotate-90': item.expanded }"></div>

							<div v-if="item.expanded">
								<button
									v-for="subscriptionOption in item.variant.subscription_options" :key="subscriptionOption.id"
									@click.prevent="upgradeItem(item, subscriptionOption)"
									class="w-full mb-2 text-primary bg-transparent text-center border border-primary text-xs font-semibold py-1 px-2 outline-none focus:outline-none transition duration-300 ease-in-out"
								>
									Renews every {{ subscriptionOption.frequency }} week{{ subscriptionOption.frequency == 1 ? '' : 's' }} - ${{ formatPrice(subscriptionOption.price) }} per delivery
								</button>
							</div>
						</div>
					</div>
				</div>

				<!-- addons -->
				<div v-if="item.variant.product_addons.length > 0" class="my-6 px-6">
					<h4 class="font-serif italic text-lg">Enhance your cleansing experience</h4>
					<div class="flex justify-between mt-4 py-3 px-6 -mx-6 bg-lightbox" v-for="product_addon in item.variant.product_addons" :key="product_addon.id">
						<inertia-link :href="'product/'+product_addon.product.url"><img :src="product_addon.product.image_object.url" width="80"></inertia-link>
						<div class="flex-1 pl-3">
							<div class="flex items-center justify-between mb-2">
								<div>
									<inertia-link :href="'product/'+product_addon.product.url" class="">{{ product_addon.product.title }}</inertia-link>
								</div>
								<div>
									<div>${{ product_addon.price }}</div>
								</div>
							</div>
							<button-primary @click.prevent="addToCart(product_addon)" style="padding: 0.5rem 1rem;">Add</button-primary>
						</div>
					</div>
				</div>
			</div>

			<div class="p-6 pt-0 absolute w-full bottom-0 left-0" v-if="totals">
				<!-- <div class="font-medium flex justify-between items-center border-t mt-2 pt-2">
					<span class="text-xs font-medium uppercase tracking-widest">Subtotal</span>
					<span class="font-medium text-right">${{ formatPrice(totals.subtotal) }}</span>
				</div> -->
				<!-- <div class="font-medium flex justify-between items-center border-t mt-2 pt-2">
					<span class="text-xs font-medium uppercase tracking-widest">Tax</span>
					<span class="font-medium text-right">${{ formatPrice(totals.tax) }}</span>
				</div> -->
				<!-- <div class="font-medium flex justify-between items-center border-t mt-2 pt-2">
					<span class="text-xs font-medium uppercase tracking-widest">Shipping</span>
					<span class="font-medium text-right">Calculated at checkout</span>
				</div> -->
				<!-- <div class="font-medium flex justify-between items-center border-t mt-2 pt-2 mb-5">
					<span class="text-xs font-medium uppercase tracking-widest">Total</span>
					<span class="font-medium text-right">${{ formatPrice(totals.total) }}</span>
				</div> -->

				<div class="border-t text-center mb-3 pt-3">
					<span class="">Shipping calculated at checkout</span>
				</div>

				<button-primary :href="'/checkout'" class="w-full">Proceed to Checkout | ${{ formatPrice(totals.total) }}</button-primary>
			</div>
		</div>

		<div v-else>
			<p class="font-serif text-lg px-6">No items in cart</p>
		</div>

    </div>
</template>

<script>
import emitter from "@/emitter";
import { mixin as VueClickAway } from "vue3-click-away";

import ButtonPrimary from '@/Components/ButtonPrimary'

export default {
	props: [],

	components: {
		ButtonPrimary,
	},

	mixins: [VueClickAway],

	computed: {
		cart() {
			return this.$page.props.cart
		},
		totals() {
			return this.$page.props.totals
		}
    },

	data() {
		return {
			show: false,

			form: this.$inertia.form({
				variant_id: '',
				subscription_option_id: '',
				qty: '',
				mode: 'set'
			})
		}
	},

	created() {
		emitter.on('showCartSidebar', event => {
			this.show = true;
		});
	},

	methods: {
		onClickAway(event) {
			this.show = false;
		},

		formatPrice(value) {
			return value.toFixed(2)
		},

		subQty(item) {
			this.form.variant_id = item.variant_id;
			this.form.subscription_option_id = item.subscription_option_id;
			this.form.qty = item.qty - 1;
			this.form.mode = 'set';

			this.form.post('/cart', {
				preserveScroll: true,
				onSuccess: () => {
					
				}
			});
		},

		removeItem(item) {
			this.form.variant_id = item.variant_id;
			this.form.subscription_option_id = item.subscription_option_id;
			this.form.qty = 0;
			this.form.mode = 'set';

			this.form.post('/cart', {
				preserveScroll: true,
				onSuccess: () => {

				}
			});
		},
		
		addQty(item) {
			this.form.variant_id = item.variant_id;
			this.form.subscription_option_id = item.subscription_option_id;
			this.form.qty = item.qty + 1;
			this.form.mode = 'set';

			this.form.post('/cart', {
				preserveScroll: true,
				onSuccess: () => {

				}
			});
		},

		upgradeItem(item, subscriptionOption) {
			this.form.variant_id = item.variant_id;
			this.form.subscription_option_id = subscriptionOption.id;
			this.form.qty = item.qty;
			this.form.mode = 'add';

			this.form.post('/cart', {
				onSuccess: () => {
					this.form.variant_id = item.variant_id;
					this.form.subscription_option_id = item.subscription_option_id;
					this.form.qty = 0;
					this.form.mode = 'set';
					
					this.form.post('/cart', {
						preserveScroll: true,
						onSuccess: () => {
							
						}
					});
				}
			});
		},

		addToCart(item) {
			this.form.variant_id = item.id;
			this.form.subscription_option_id = '';
			this.form.qty = 1;
			this.form.mode = 'add';

			this.form.post('/cart', {
				preserveScroll: true,
				onSuccess: () => {

				}
			});
		}
	}
}
</script>

<style scoped>
.cart-items {
	max-height: calc(100% - 201px);
}
</style>>