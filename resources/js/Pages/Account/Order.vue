<template>
    <layout>
	
		<section class="mt-20 mb-10 container max-w-screen-md mx-auto px-6 text-center">
			<h1 class="font-serif text-4xl tracking-wider mt-2 mb-4" v-if="source == 'checkout'">Thank you for your order</h1>
			<h1 class="font-serif text-4xl tracking-wider mt-2 mb-4" v-else>Account</h1>
		</section>

		<section class="mb-10 max-w-screen-lg container mx-auto flex flex-wrap">
			<div class="w-full md:w-1/3 p-6" v-if="user">
				<account-menu></account-menu>
			</div>
			<div class="w-full md:w-2/3 p-6">
				<div class="mb-5">
					<h2 class="font-serif text-3xl tracking-wider mb-2">Order #{{ order.id }}</h2>
					<span :class="'px-2 py-1 inline-flex text-xs text-white rounded-full status-pill status-'+order.status.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'-')">{{ order.status }}</span>
				</div>

				<div class="mb-5">
					<div class="mb-1">Created: {{ moment(order.created_at).format('Do MMM YYYY') }}</div>
					<div class="mb-1">Shipping Address: {{ order.shipping_address_formatted }}</div>
					<div v-if="order.billing_address_formatted" class="mb-1">Billing Address: {{ order.billing_address_formatted }}</div>
					<div v-if="order.delivery_date" class="mb-1">Delivery Date: {{ moment(order.delivery_date).format('Do MMM YYYY') }}</div>
					<div v-if="order.notes" class="mb-1">Order Notes: {{ order.notes }}</div>
				</div>

				<div v-if="!user && source == 'checkout'" class="mb-5">We've created an account for you and saved your card for future use. To login again, first <inertia-link href="/forgot-password'" class="text-primary">set your password here</inertia-link></div>

				<h3 class="font-serif text-2xl tracking-wider mb-2">Order Items</h3>
				<div class="mb-5 overflow-x-auto">
					<div class="align-middle inline-block min-w-full">
						<div class="overflow-hidden">
							<table class="border border-primary min-w-full align-middle divide-y divide-primary">
								<thead>
									<tr>
										<th class="bg-lightbox text-primary p-3 text-left" colspan="2">Product</th>
										<th class="bg-lightbox text-primary p-3 text-left">Price</th>
										<th class="bg-lightbox text-primary p-3 text-left">Quantity</th>
									</tr>
								</thead>
								<tbody class="bg-white divide-y divide-lightbox">
									<tr v-for="orderItem in order.order_items" :key="orderItem.id">
										<td class="p-3 whitespace-no-wrap">
											<div class="flex-shrink-0 w-10" v-if="orderItem.variant.image_object">
												<img class="h-10 w-10" :src="orderItem.variant.image_object.url" alt="">
											</div>
										</td>
										<td class="p-3 whitespace-no-wrap">
											{{ orderItem.variant.title }}
											<div v-if="orderItem.custom_options" class="italic text-xs">{{ orderItem.custom_options.join(', ') }}</div>
										</td>
										<td class="p-3 whitespace-no-wrap">
											${{ orderItem.price }}
										</td>
										<td class="p-3 whitespace-no-wrap">
											{{ orderItem.qty }}
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<!-- <div class="mb-5">
					<h3 class="font-serif text-xl tracking-wider mb-2">Edit Order</h3>
					<button-primary class="mt-2" @click.prevent="editOrder">Edit</button-primary>
				</div> -->

				<h3 class="font-serif text-2xl tracking-wider mb-2">Order Totals</h3>
				<div class="mb-5 overflow-x-auto">
					<div class="align-middle inline-block min-w-full">
						<div class="overflow-hidden">
							<table class="border border-primary min-w-full align-middle divide-y divide-primary">
								<tbody class="bg-white divide-y divide-lightbox">
									<tr>
										<td class="p-3 whitespace-no-wrap">
											Subtotal excl. tax	
										</td>
										<td class="p-3 whitespace-no-wrap">
											${{ order.subtotal }}
										</td>
									</tr>
									<!-- <tr>
										<td class="p-3 whitespace-no-wrap">
											Total GST
										</td>
										<td class="p-3 whitespace-no-wrap">
											${{ order.tax }}
										</td>
									</tr> -->
									<tr v-if="order.discount_amount">
										<td class="p-3 whitespace-no-wrap">
											Discount
										</td>
										<td class="p-3 whitespace-no-wrap">
											-${{ order.discount_amount }}
										</td>
									</tr>
									<tr>
										<td class="p-3 whitespace-no-wrap">
											Shipping
										</td>
										<td class="p-3 whitespace-no-wrap">
											${{ order.shipping_price }}
										</td>
									</tr>
									<tr>
										<td class="p-3 whitespace-no-wrap">
											Total
										</td>
										<td class="p-3 whitespace-no-wrap">
											${{ order.total }}
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div v-if="moment().diff(order.delivery_date, 'days') >= 4">
					<h3 class="font-serif text-2xl tracking-wider mb-2">Review Order</h3>

					<form @submit.prevent="reviewOrder" v-if="!order.review">
						<div class="mb-3">
							<form-label for="rating" value="Rating" />
							<!-- <form-input id="rating" type="number" v-model="reviewForm.rating" /> -->
							<star-rating v-model:rating="reviewForm.rating" :show-rating="false" :star-size="30" :padding="5"></star-rating>
							<form-input-error :message="reviewForm.errors.rating" />
						</div>

						<div class="mb-2">
							<form-label for="review" value="Review" />
							<textarea id="review" v-model="reviewForm.review" class="w-full border-primary focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-25" rows="5" />
							<form-input-error :message="reviewForm.errors.review" />
						</div>

						<button-primary :class="{ 'opacity-25': reviewForm.processing }" :disabled="reviewForm.processing">Review Order</button-primary>
					</form>
					
					<div v-else>
						<p class="flex items-center"><span class="mt-1 mr-2">You reviewed this order</span> <star-rating v-model:rating="order.review.rating" :read-only="true" :active-color="'#AA947C'" :show-rating="false" :star-size="20" :inline="true"></star-rating> <span class="mt-1 ml-1">stars.</span></p>
					
						<div class="uppercase tracking-widest text-sm mb-3">Your Review</div>
						<div v-html="order.review.review" class="italic"></div>
					</div>
				</div>
				
			</div>
		</section>

    </layout>
</template>

<script>
import Layout from '@/Layouts/App'
import AccountMenu from '@/Pages/Account/Menu'
import ButtonPrimary from '@/Components/ButtonPrimary.vue'

import FormLabel from '@/Components/FormLabel'
import FormInput from '@/Components/FormInput'
import FormInputError from '@/Components/FormInputError'

import StarRating from 'vue-star-rating'
import moment from 'moment'

export default {
	props: ['order', 'source'],

	computed: {
		user() {
			return this.$page.props.auth.user
		},
	},

	components: {
		Layout,
		AccountMenu,
		ButtonPrimary,

		FormLabel,
		FormInput,
		FormInputError,

		StarRating
	},

	data() {
		return {
 			reviewForm: this.$inertia.form({
				review: '',
				rating: '',
			})
		}
	},

	created() {
		this.moment = moment;

		if (this.source && this.source === 'checkout') {
			// dataLayer.push({'ichange': this.order});
			// dataLayer.push({'event': 'ichange'});

			localStorage.clear();
			var iec_script = document.createElement('script');
			iec_script.setAttribute('type', 'text/javascript');
			iec_script.setAttribute('src', 'https://staging.share.iequalchange.com/static/js/js_donate');
			iec_script.setAttribute('data-site-id', '486');
			iec_script.setAttribute('data-iec-site-password', 'zY`n6TSUS52~7ABj');
			iec_script.setAttribute('data-iec-orderid', this.order.id);
			iec_script.setAttribute('data-iec-first-name', this.order.first_name);
			iec_script.setAttribute('data-iec-surname', this.order.last_name);
			iec_script.setAttribute('data-iec-customer-email', this.order.email);
			iec_script.setAttribute('data-iec-postcode', this.order.shipping_postcode);
			iec_script.setAttribute('data-iec-state', this.order.shipping_state);
			iec_script.setAttribute('data-iec-country', this.order.shipping_country);
			document.head.appendChild(iec_script);
		}
	},

	methods: {
		editOrder() {
			// add order to cart for updating
		},

		reviewOrder() {
			this.reviewForm.post('/orders/'+this.order.id+'/review', {
				preserveScroll: true,
				onSuccess: () => {

				},
				// onFinish: () => this.reviewForm.reset(),
			})
		}
	}
}
</script>

<style scoped>

</style>