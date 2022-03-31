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
				
				<form>
					<div class="mb-8">
						<div class="text-lg mb-4">Customer information</div>
						<div class="mb-2">
							<form-label for="email" value="Email address" />
							<form-input id="email" type="email" v-model="form.email" required autocomplete="username" />
							<form-input-error :message="form.errors.email" />
						</div>
						<div class="grid md:grid-cols-2 md:gap-4">
							<div class="mb-2">
								<form-label for="first_name" value="First Name" />
								<form-input id="first_name" type="text" v-model="form.first_name" required />
								<form-input-error :message="form.errors.first_name" />
							</div>
							<div class="mb-2">
								<form-label for="last_name" value="Last Name" />
								<form-input id="last_name" type="text" v-model="form.last_name" required />
								<form-input-error :message="form.errors.last_name" />
							</div>
						</div>
						<div class="mb-2">
							<form-label for="phone" value="Phone number" />
							<form-input id="phone" type="text" v-model="form.phone" required />
							<form-input-error :message="form.errors.phone" />
						</div>
					</div>

					<div class="mb-8">
						<div class="text-lg mb-4">Shipping address</div>
						<div class="mb-2">
							<form-label for="address" value="Address" />
							<vue-google-autocomplete
								ref="shipping_address"
								id="shipping_address_map"
								classname="w-full border-primary focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-25"
								placeholder=""
								v-on:placechanged="getShippingAddressData"
								country="au"
								autocomplete="off"
								v-model="form.shipping_address"
							>
							</vue-google-autocomplete>

							<!-- <form-input id="shipping_address" type="hidden" v-model="form.shipping_address" required /> -->
							<form-input-error :message="form.errors.shipping_address" />
						</div>
						<div class="mb-2">
							<form-label for="shipping_apartment" value="Apartment, suite, etc. (optional)" />
							<form-input id="shipping_apartment" type="text" v-model="form.shipping_apartment" />
							<form-input-error :message="form.errors.shipping_apartment" />
						</div>
						<div class="grid md:grid-cols-3 md:gap-4">
							<div class="mb-2">
								<form-label for="shipping_postcode" value="Postcode" />
								<form-input id="shipping_postcode" type="text" v-model="form.shipping_postcode" required />
								<form-input-error :message="form.errors.shipping_postcode" />
							</div>
							<div class="mb-2">
								<form-label for="shipping_city" value="City" />
								<form-input id="shipping_city" type="text" v-model="form.shipping_city" required />
								<form-input-error :message="form.errors.shipping_city" />
							</div>
							<div class="mb-2">
								<form-label for="shipping_state" value="State" />
								<form-input id="shipping_state" type="text" v-model="form.shipping_state" required />
								<form-input-error :message="form.errors.shipping_state" />
							</div>
						</div>
						<div class="mb-2">
							<form-label for="shipping_country" value="Country" />
							<form-input id="shipping_country" type="text" v-model="form.shipping_country" required />
							<form-input-error :message="form.errors.shipping_country" />
						</div>
					</div>

					<div class="mb-8">
						<div class="text-lg mb-4">Billing address</div>
						<div class="mb-2">
							<form-label for="address" value="Address" />
							<vue-google-autocomplete
								ref="billing_address"
								id="billing_address_map"
								classname="w-full border-primary focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-25"
								placeholder=""
								v-on:placechanged="getBillingAddressData"
								country="au"
								autocomplete="off"
								v-model="form.billing_address"
							>
							</vue-google-autocomplete>

							<!-- <form-input id="billing_address" type="hidden" v-model="form.billing_address" required /> -->
							<form-input-error :message="form.errors.billing_address" />
						</div>
						<div class="mb-2">
							<form-label for="billing_apartment" value="Apartment, suite, etc. (optional)" />
							<form-input id="billing_apartment" type="text" v-model="form.billing_apartment" />
							<form-input-error :message="form.errors.billing_apartment" />
						</div>
						<div class="grid md:grid-cols-3 md:gap-4">
							<div class="mb-2">
								<form-label for="billing_postcode" value="Postcode" />
								<form-input id="billing_postcode" type="text" v-model="form.billing_postcode" required />
								<form-input-error :message="form.errors.billing_postcode" />
							</div>
							<div class="mb-2">
								<form-label for="billing_city" value="City" />
								<form-input id="billing_city" type="text" v-model="form.billing_city" required />
								<form-input-error :message="form.errors.billing_city" />
							</div>
							<div class="mb-2">
								<form-label for="billing_state" value="State" />
								<form-input id="billing_state" type="text" v-model="form.billing_state" required />
								<form-input-error :message="form.errors.billing_state" />
							</div>
						</div>
						<div class="mb-2">
							<form-label for="billing_country" value="Country" />
							<form-input id="billing_country" type="text" v-model="form.billing_country" required />
							<form-input-error :message="form.errors.billing_country" />
						</div>
					</div>

					<button-primary @click.prevent="updateProfile">Update Profile</button-primary>
				</form>

				<div v-if="success" class="mt-2 font-medium text-sm italic text-green-600">
					{{ success }}
				</div>

			</div>
		</section>

    </layout>
</template>

<script>
import Layout from '@/Layouts/App'
import AccountMenu from '@/Pages/Account/Menu'


import FormLabel from '@/Components/FormLabel'
import FormInput from '@/Components/FormInput'
import FormInputError from '@/Components/FormInputError'
import FormCheckbox from '@/Components/FormCheckbox'
import ButtonPrimary from '@/Components/ButtonPrimary'

import VueGoogleAutocomplete from 'vue-google-autocomplete'

export default {
	props: ['user'],

	components: {
		Layout,
		AccountMenu,

		FormInput,
		FormInputError,
		FormCheckbox,
		FormLabel,
		ButtonPrimary,

		VueGoogleAutocomplete
	},

	data() {
		return {
			form: this.$inertia.form({
				email: this.user.email,

				first_name: this.user.first_name,
				last_name: this.user.last_name,
				phone: this.user.phone,

				shipping_address: this.user.shipping_address,
				shipping_apartment: this.user.shipping_apartment,
				shipping_city: this.user.shipping_city,
				shipping_postcode: this.user.shipping_postcode,
				shipping_state: this.user.shipping_state,
				shipping_country: this.user.shipping_country,

				billing_address: this.user.billing_address,
				billing_apartment: this.user.billing_apartment,
				billing_city: this.user.billing_city,
				billing_postcode: this.user.billing_postcode,
				billing_state: this.user.billing_state,
				billing_country: this.user.billing_country,
			}),
		}
	},

	mounted() {
		this.$refs.shipping_address.update(this.form.shipping_address);
		this.$refs.billing_address.update(this.form.billing_address);
	},

	methods: {
		getShippingAddressData: function (addressData, placeResultData, id) {
			this.form.shipping_address = addressData.street_number + ' ' +addressData.route;
			this.form.shipping_city = addressData.locality;
			this.form.shipping_postcode = addressData.postal_code;
			this.form.shipping_state = addressData.administrative_area_level_1;
			this.form.shipping_country = addressData.country;

			for (var i = 0; i < placeResultData.address_components.length; i++) {
				var addressType = placeResultData.address_components[i].types[0];
				if (addressType == 'country') {
					this.form.country = placeResultData.address_components[i].short_name;
				}
			}

			this.$refs.shipping_address.update(this.form.shipping_address);
		},

		getBillingAddressData: function (addressData, placeResultData, id) {
			this.form.billing_address = addressData.street_number + ' ' +addressData.route;
			this.form.billing_city = addressData.locality;
			this.form.billing_postcode = addressData.postal_code;
			this.form.billing_state = addressData.administrative_area_level_1;
			this.form.billing_country = addressData.country;

			for (var i = 0; i < placeResultData.address_components.length; i++) {
				var addressType = placeResultData.address_components[i].types[0];
				if (addressType == 'country') {
					this.form.country = placeResultData.address_components[i].short_name;
				}
			}

			this.$refs.billing_address.update(this.form.billing_address);
		},

		updateProfile() {
			this.form.post('/account/profile', {
				onSuccess: () => {
					this.success = 'Profile updated.';
				},
				onError: (response) => {
					console.log(response);
				}
			});
		}
	}
}
</script>

<style scoped>

</style>