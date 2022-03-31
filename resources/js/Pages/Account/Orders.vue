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
										<th class="bg-lightbox text-primary p-3 text-left">Order</th>
										<th class="bg-lightbox text-primary p-3 text-left">Status</th>
										<th class="bg-lightbox text-primary p-3 text-left">Total</th>
									</tr>
								</thead>
								<tbody class="bg-white divide-y divide-lightbox">
									<tr v-for="order in orders" :key="order.id">
										<td class="p-3 whitespace-no-wrap">
											<inertia-link :href="'/account/orders/'+order.id">
												<div class="font-bold text-primary">Order #{{ order.id }}</div>
												<div class="text-xs">Created {{ moment(order.created_at).calendar() }}</div>
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

import moment from 'moment'

export default {
	props: ['orders'],

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