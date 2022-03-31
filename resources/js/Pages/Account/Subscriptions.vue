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

				<div class="overflow-x-auto">
					<div class="align-middle inline-block min-w-full">
						<div class="overflow-hidden">
							<table class="border border-primary min-w-full align-middle divide-y divide-primary">
								<thead>
									<tr>
										<th class="bg-lightbox text-primary p-3 text-left">Subscription</th>
										<th class="bg-lightbox text-primary p-3 text-left">Product</th>
										<th class="bg-lightbox text-primary p-3 text-left">Total</th>
										<th class="bg-lightbox text-primary p-3 text-left">Next Renewal</th>
									</tr>
								</thead>
								<tbody class="bg-white divide-y divide-lightbox">
									<tr v-for="subscription in subscriptions" :key="subscription.id">
										<td class="p-3 whitespace-no-wrap">
											<inertia-link :href="'/account/subscriptions/'+subscription.id">
												<div class="font-bold text-primary">Subscription #{{ subscription.id }}</div>
												<div class="text-xs">Renews every {{ subscription.frequency }} week{{ subscription.frequency == 1 ? '' : 's' }}</div>
											</inertia-link>
										</td>
										<td class="p-3 whitespace-no-wrap">
											<div class="flex items-center">
												{{ subscription.qty }}x {{ subscription.variant.title }}
												<!-- <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-primary rounded-full">{{ subscription.qty }}</span> -->
											</div>
										</td>
										<td class="p-3 whitespace-no-wrap">
											${{ subscription.price }}
										</td>
										<td class="p-3 whitespace-no-wrap">
											{{ moment(subscription.next_order).format('Do MMM YYYY') }}
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

import moment from 'moment'

export default {
	props: ['subscriptions'],

	computed: {
		user() {
			return this.$page.props.auth.user
		},
	},

	components: {
		Layout,
		AccountMenu
	},

	data() {
		return {

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

</style>