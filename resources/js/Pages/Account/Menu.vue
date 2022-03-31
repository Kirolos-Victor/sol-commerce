<template>
    <div class="">
        <ul class="">
            <li class="my-3"><span class="">MENU</span></li>
            <li class="my-3"><inertia-link :href="'/account/profile'" class="" :class="{ 'text-secondary border-b border-secondary': $page.props.route === 'profile.index' }">Profile</inertia-link></li>
            <li class="my-3"><inertia-link :href="'/account/orders'" class="" :class="{ 'text-secondary border-b border-secondary': $page.props.route === 'orders.index' }">Orders</inertia-link></li>
            <li class="my-3"><inertia-link :href="'/account/subscriptions'" class="" :class="{ 'text-secondary border-b border-secondary': $page.props.route === 'subscriptions.index' }">Subscriptions</inertia-link></li>
            <li class="my-3" v-if="user && user.location_id && user.pm_last_four"><inertia-link :href="'/account/payment-method'" class="" :class="{ 'text-secondary border-b border-secondary': $page.props.route === 'payment-method' }">Payment Method</inertia-link></li>
            <li class="my-3"><inertia-link :href="'/account/password'" class="" :class="{ 'text-secondary border-b border-secondary': $page.props.route === 'passwords.index' }">Password</inertia-link></li>
        </ul>
    </div>
</template>

<script>
export default {
	props: [],

	computed: {
		user() {
			return this.$page.props.auth.user
		},
	},

	components: {
		
	},

	data() {
		return {

		}
	},

	created() {

	},

	methods: {
		
	}
}
</script>