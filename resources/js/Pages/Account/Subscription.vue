<template>
    <layout>
	
		<section class="mt-20 mb-10 container max-w-screen-md mx-auto px-6 text-center">
			<h1 class="font-serif text-4xl tracking-wider mt-2 mb-4">Account</h1>
		</section>

		<section class="mb-10 max-w-screen-lg container mx-auto flex flex-wrap">
			<div class="w-full md:w-1/3 p-6">
				<account-menu></account-menu>
			</div>
			<div class="w-full md:w-2/3 p-6">

				<div class="mb-5">
					<h2 class="font-serif text-3xl tracking-wider mb-2">Subscription #{{ subscription.id }}</h2>
				</div>
				<div class="mb-5">
					<div class="mb-1">Created: {{ moment(subscription.created_at).format('Do MMM YYYY') }}</div>
					<div class="mb-1">Next Order Renewal: {{ moment(subscription.next_order.renewal_date).format('Do MMM YYYY') }}</div>
				</div>

				<h3 class="font-serif text-2xl tracking-wider mb-2">Items</h3>
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
									<tr>
										<td class="p-3 whitespace-no-wrap">
											<div class="flex-shrink-0 w-10" v-if="subscription.variant.image_object">
												<img class="h-10 w-10" :src="subscription.variant.image_object.url" alt="">
											</div>
										</td>
										<td class="p-3 whitespace-no-wrap">
											{{ subscription.variant.title }}
										</td>
										<td class="p-3 whitespace-no-wrap">
											${{ subscription.price }}
										</td>
										<td class="p-3 whitespace-no-wrap">
											{{ subscription.qty }}
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div v-if="subscription.next_order" class="mb-8">
					<h3 class="font-serif text-2xl tracking-wider mb-2">Next Order</h3>
					<div class="mb-5 overflow-x-auto">
						<div class="align-middle inline-block min-w-full">
							<div class="overflow-hidden">
								<table class="border border-primary min-w-full align-middle divide-y divide-primary">
									<thead>
										<tr>
											<th class="bg-lightbox text-primary p-3 text-left">Order</th>
											<th class="bg-lightbox text-primary p-3 text-left">Renewal date</th>
											<th class="bg-lightbox text-primary p-3 text-left">Delivery date</th>
											<th class="bg-lightbox text-primary p-3 text-left">Total</th>
										</tr>
									</thead>
									<tbody class="bg-white divide-y divide-lightbox">
										<tr>
											<td class="p-3 whitespace-no-wrap">
												<inertia-link :href="'/account/orders/'+subscription.next_order.id">
													<div class="font-bold text-primary">Order #{{ subscription.next_order.id }}</div>
													<!-- <div class="text-xs">Created {{ moment(subscription.next_order.created_at).format('Do MMM YYYY') }}</div> -->
												</inertia-link>
											</td>
											<td class="p-3 whitespace-no-wrap">
												{{ moment(subscription.next_order.renewal_date).format('Do MMM YYYY') }}
											</td>
											<td class="p-3 whitespace-no-wrap">
												{{ moment(subscription.next_order.delivery_date).format('Do MMM YYYY') }}
											</td>
											<td class="p-3 whitespace-no-wrap">
												${{ subscription.next_order.total }}
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					
					<h3 class="font-serif text-xl tracking-wider mb-2">Change your next delivery date</h3>
					<div class="grid md:grid-cols-2 md:gap-4">
						<div>
							<form>
								<form-label for="delivery_date" value="Adjust next delivery date" />
								<datepicker v-model="form1.delivery_date" :lowerLimit="new Date()" :upperLimit="new Date(subscription.buffer_date)" placeholder="Select a date" class="w-full border-primary focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-25" :inputFormat="'dd/MM/yyyy'" :disabledDates="{ dates: blocked_dates }" />
								<form-input-error :message="form1.errors.delivery_date" />
							</form>
							<button-primary class="mt-2" @click.prevent="changeDeliveryDate">Confirm Delivery Date</button-primary>
							<p class="mt-2 text-primary italic">You can only adjust your next delivery up to {{ subscription.buffer_days }} days</p>
							<div v-if="form1Success" class="mt-2 font-medium text-sm italic text-green-600">
								{{ form1Success }}
							</div>
						</div>
						<div v-if="subscription.paused_count < subscription.pause_count">
							<form>
								<form-label for="delivery_date" value="Or pause subscription until" />
								<datepicker v-model="form2.delivery_date" :lowerLimit="new Date()" :upperLimit="new Date(subscription.paused_date)" placeholder="Select a date" class="w-full border-primary focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-25" :inputFormat="'dd/MM/yyyy'" :disabledDates="{ dates: blocked_dates }" />
								<form-input-error :message="form2.errors.delivery_date" />
							</form>
							<button-primary class="mt-2" @click.prevent="pauseSubscription">Pause Subscription</button-primary>
							<p class="mt-2 text-primary italic">You can only pause this subscription {{ subscription.pause_count }} time/s, and for a maximum of {{ subscription.pause_days }} day/s. To cancel your subscription please contact us.</p>
							<div v-if="form2Success" class="mt-2 font-medium text-sm italic text-green-600">
								{{ form2Success }}
							</div>
						</div>
					</div>

					<div class="italic text-sm text-primary mt-4">To cancel your subscription, please <inertia-link href="/contact" class="underline">contact us</inertia-link></div>
				</div>

				<h3 class="font-serif text-2xl tracking-wider mb-2">Past Orders</h3>
				<div class="overflow-x-auto">
					<div class="align-middle inline-block min-w-full">
						<div class="overflow-hidden">
							<table class="border border-primary min-w-full align-middle divide-y divide-primary">
								<thead>
									<tr>
										<th class="bg-lightbox text-primary p-3 text-left">Order</th>
										<th class="bg-lightbox text-primary p-3 text-left">Status</th>
										<th class="bg-lightbox text-primary p-3 text-left">Total</th>
									</tr>
								</thead>
								<tbody class="bg-white divide-y divide-lightbox">
									<tr v-for="order in subscription.orders" :key="order.id">
										<td class="p-3 whitespace-no-wrap">
											<inertia-link :href="'/account/orders/'+order.id">
												<div class="font-bold text-primary">Order #{{ order.id }}</div>
												<div class="text-xs">Created {{ moment(order.created_at).format('Do MMM YYYY') }}</div>
											</inertia-link>
										</td>
										<td class="p-3 whitespace-no-wrap">
											<span :class="'text-xs py-1 px-2 inline-flex text-white rounded-full status-pill status-'+order.status.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'-')">{{ order.status }}</span>
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
				
			</div>
		</section>

    </layout>
</template>

<script>
import Layout from '@/Layouts/App'
import AccountMenu from '@/Pages/Account/Menu'
import ButtonPrimary from '@/Components/ButtonPrimary.vue'
import FormInputError from '@/Components/FormInputError'
import FormLabel from '@/Components/FormLabel'

import Datepicker from 'vue3-datepicker'

import moment from 'moment'

export default {
	props: ['subscription', 'blocked_dates'],

	computed: {
		user() {
			return this.$page.props.auth.user
		},
	},

	components: {
		Layout,
		AccountMenu,
		ButtonPrimary,
		FormInputError,
		FormLabel,
		Datepicker
	},

	data() {
		return {
			form1Success: '',
			form1: this.$inertia.form({
				delivery_date: '',
			}),

			form2Success: '',
			form2: this.$inertia.form({
				pause: true,
				delivery_date: '',
			})
		}
	},

	created() {
		this.moment = moment;

		var today = new Date();
		var upperLimit = new Date().setMonth(today.getMonth() + 6);

		if (this.subscription.location_id == 1) {
			var blockedDays = [0, 2, 4, 6];
		} else if (this.subscription.location_id == 2) {
			var blockedDays = [0, 1, 3, 5, 6];
		}

		for (var date = today; date <= upperLimit; date.setDate(date.getDate() + 1)) {
			if (blockedDays.includes(date.getDay())) {
				this.blocked_dates.push(date.getTime());
			}
		}
	},

	methods: {
		changeDeliveryDate() {
			this.form1.put('/account/subscriptions/'+this.subscription.id, {
				onSuccess: () => {
					this.form1Success = 'Delivery date adjusted.';
					this.form1.reset();
				},
				preserveScroll: true,
			});
		},

		pauseSubscription() {
			this.form2.put('/account/subscriptions/'+this.subscription.id, {
				onSuccess: () => {
					this.form2Success = 'Subscription paused.';
					this.form2.reset();
				},
				preserveScroll: true,
			});
		}
	}
}
</script>

<style>
	:root {
		--vdp-hover-bg-color: #AA947C;
		--vdp-selected-bg-color: #AA947C;
	}
</style>