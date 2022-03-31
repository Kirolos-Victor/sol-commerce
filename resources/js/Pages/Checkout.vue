<template>
    <div>
    <layout>
	
		<section class="pt-20 container max-w-screen-lg mx-auto px-6">
			<h1 class="font-serif text-4xl tracking-wider mt-2 mb-4">Checkout</h1>
		</section>

		<section class="mb-20 container mx-auto max-w-screen-lg flex flex-wrap">

			<form class="w-full md:w-2/3 p-6">
				<!-- <div class="flex flex-row text-sm pb-5">
					<span class="font-semibold">Information</span> 
					<small class="ml-1">></small> 
					<span class="ml-1">Shipping</span> 
					<small class="ml-1">></small> 
					<span class="ml-1">Payment</span> 
				</div> -->

				<div class="step-1" :class="{'hidden': form.step != 1}">
					<div class="mb-8">
						<div class="text-lg mb-4">Customer information</div>
						<div>
							<form-label for="email" value="Email address" />
							<form-input id="email" type="email" v-model="form.email" required autocomplete="email" />
							<form-input-error :message="form.errors.email" />
						</div>
					</div>

					<div class="flex justify-between items-center pt-2">
						<inertia-link href="/shop" class="uppercase tracking-widest border-b font-semibold border-secondary pb-1 text-xs">Return to shop</inertia-link>
						<button-primary @click.prevent="nextStep" :busy="busy">Continue to details</button-primary>
					</div>
				</div>
				
				<div class="step-2" :class="{'hidden': form.step != 2}">

					<div v-if="totals.perishable" class="mb-8">
						<div class="text-lg mb-4">Delivery date</div>

						<div v-if="renewal_orders.length" class="mb-4">
							<form-label for="deliver_with_renewal_order" value="Combine order with an upcoming renewal to save on delivery" class="mb-2" />
							<div class="mt-2 flex items-center">
								<div class="mr-4">
									<label class="flex items-center">
										<input type="radio" name="deliver_with_renewal_order" value="Yes" v-model="form.deliver_with_renewal_order" class="mr-2" @input="form.shipping_method = 'Free'"> 
										<span>Yes</span>
									</label>
								</div>
								<div>
									<label class="flex items-center">
										<input type="radio" name="deliver_with_renewal_order" value="No" v-model="form.deliver_with_renewal_order" class="mr-2" @input="form.shipping_method = 'Free'"> 
										<span>No</span>
									</label>
								</div>
							</div>
						</div>

						<div v-if="form.deliver_with_renewal_order == 'Yes'">
							<div class="text-lg mb-4">Select delivery date</div>
							<ul class="">
								<li class="mb-2 bg-white relative border border-gray-300 px-6 py-4 transition-all duration-100 border-primary bg-lightbox" v-for="renewal_order in renewal_orders" :key="renewal_order.id">
									<label class="cursor-pointer flex items-center">
										<input type="radio" name="delivery_date" :value="moment(renewal_order.delivery_date).format('YYYY-MM-DD')" v-model="form.delivery_date" class="mr-2"> 
										<span>Order #{{ renewal_order.id }} - {{ moment(renewal_order.delivery_date).format('Do MMM YYYY') }}</span>
									</label>
								</li>
							</ul>
						</div>

						<div v-if="renewal_orders.length == 0 || form.deliver_with_renewal_order == 'No'">
							<div class="text-lg mb-4" v-if="form.deliver_with_renewal_order == 'No'">Select delivery date</div>
							<div class="grid md:grid-cols-2 md:gap-4">
								<div>
									<datepicker v-model="form.delivery_date" :lowerLimit="new Date()" :upperLimit="new Date().setMonth(new Date().getMonth() + 6)" class="w-full border-primary focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-25" :inputFormat="'dd/MM/yyyy'" :disabledDates="{ dates: totals.blocked_dates }" />
								</div>
							</div>
							<div v-if="totals && totals.sold_out_dates.length" class="mt-2">Please note, we are currently sold out on the following dates: {{ totals.sold_out_dates.join(', ') }}.</div>
						</div>

						<form-input-error :message="form.errors.delivery_date" />
					</div>

					<div class="mb-8">
						<div class="text-lg mb-4">Shipping address</div>
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
						<div class="mb-2">
							<form-label for="address" value="Address" />
							<vue-google-autocomplete
								ref="shipping_address"
								id="shipping_address_map"
								classname="w-full border-primary focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-25"
								placeholder=""
								v-on:placechanged="getShippingAddressData"
								country="au"
								v-model="form.shipping_address"
							>
							</vue-google-autocomplete>

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
						<!-- <div class="mb-2">
							<form-label for="shipping_country" value="Country" />
							<form-input id="shipping_country" type="text" v-model="form.shipping_country" required />
							<form-input-error :message="form.errors.shipping_country" />
						</div> -->
					</div>


					<div class="mb-8">
						<div class="text-lg mb-4">Billing address</div>
						<div class="mb-4">
							<form-label for="billing_address_same" value="Use Shipping address for Billing Address?" class="mb-2" />
							<div class="mt-2 flex items-center">
								<div class="mr-4">
									<label class="flex items-center">
										<input type="radio" name="billing_address_same" value="1" v-model="form.billing_address_same" class="mr-2"> 
										<span>Yes</span>
									</label>
								</div>
								<div>
									<label class="flex items-center">
										<input type="radio" name="billing_address_same" value="0" v-model="form.billing_address_same" class="mr-2"> 
										<span>No</span>
									</label>
								</div>
							</div>
						</div>

						<div v-if="form.billing_address_same == 0">
							<div class="mb-2">
								<form-label for="address" value="Address" />
								<vue-google-autocomplete
									ref="billing_address"
									id="billing_address_map"
									classname="w-full border-primary focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-25"
									placeholder=""
									v-on:placechanged="getBillingAddressData"
									country="au"
									v-model="form.billing_address"
								>
								</vue-google-autocomplete>

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
							<!-- <div class="mb-2">
								<form-label for="billing_country" value="Country" />
								<form-input id="billing_country" type="text" v-model="form.billing_country" required />
								<form-input-error :message="form.errors.billing_country" />
							</div> -->
						</div>
					</div>

					

					<div class="flex justify-between items-center pt-2">
						<a href="#" @click.prevent="form.step = form.step - 1" class="uppercase tracking-widest border-b font-semibold border-secondary pb-1 text-xs">Previous</a>
						<button-primary @click.prevent="nextStep" :busy="busy">Continue to shipping</button-primary>
					</div>
				</div>

				<div class="step-3" :class="{'hidden': form.step != 3}">
					<div class="mb-8">
						<div class="text-lg mb-4">Shipping method</div>
						<ul class="">
							<li v-for="shipping_method in form.shipping_methods" :key="shipping_method.shipping_method" class="mb-2 bg-white relative border border-gray-300 px-6 py-4 transition-all duration-100 border-primary bg-lightbox">
								<label class="cursor-pointer flex items-center">
									<input type="radio" name="shipping_method" :value="shipping_method.shipping_method" v-model="form.shipping_method" class="mr-2">
									<span>{{ shipping_method.description }}</span>
								</label>
							</li>
						</ul>
					</div>
					
					<div class="mb-8">
						<div class="text-lg mb-4">Discount Code</div>
						<div>
							<!-- <form-label for="discount_code" value="Discount Code" /> -->
							<form-input id="discount_code" type="text" v-model="form.discount_code" required />
							<form-input-error :message="form.errors.discount_code" />
						</div>
					</div>
					
					<div class="flex justify-between items-center pt-2">
						<a href="#" @click.prevent="form.step = form.step - 1" class="uppercase tracking-widest border-b font-semibold border-secondary pb-1 text-xs">Previous</a>
						<button-primary @click.prevent="nextStep" :busy="busy">Continue to payment</button-primary>
					</div>
				</div>

				<div class="step-4" :class="{'hidden': form.step != 4}">

					<div class="mb-8">
						<div class="text-lg mb-4">Payment method</div>
						
						<ul class="">
							<li v-if="user && user.pm_last_four && this.user.payment_method" class="mb-2 bg-white relative border border-gray-300 px-6 py-4 transition-all duration-100" :class="{ 'border-primary bg-lightbox': form.payment_option == 'Existing Card' }">
								<div class="cursor-pointer flex items-center" @click="form.payment_option = 'Existing Card'">
									<input type="radio" name="payment_option" value="Existing Card" v-model="form.payment_option" class="mr-2"> 
									<span>Existing card ending in {{ user.pm_last_four }}</span>
								</div>
								<div class="relative overflow-hidden" :class="{ 'max-h-0': form.payment_option !== 'Existing Card' }">
									<img src="/assets/stripe-badge-transparent.png" class="w-80 mt-6">
								</div>
							</li>

							<li class="mb-2 bg-white relative border border-gray-300 px-6 py-4 transition-all duration-100" :class="{ 'border-primary bg-lightbox': form.payment_option == 'Card' }">
								<div class="cursor-pointer flex items-center" @click="form.payment_option = 'Card'">
									<input type="radio" name="payment_option" value="Card" v-model="form.payment_option" class="mr-2"> 
									<span v-if="user && user.pm_last_four && user.location_id == cart.location_id">New Credit Card</span>
									<span v-else>Credit Card</span>
								</div>
								<div class="relative overflow-hidden" :class="{ 'max-h-0': form.payment_option !== 'Card' }">
									<div class="mt-2">
										<div id="card-element" class="w-full bg-white border p-3 border-primary focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-25"></div>
										<form-input-error :message="form.errors.card" />
									</div>
									<div v-if="!cart.requires_account && /*cart.user_status != 'No Password' &&*/ (!user || (!user.location_id || user.location_id == cart.location_id))" class="mt-6 ml-1">
										<form-label for="save_card" value="Save card for faster checkout for future orders?" class="mb-2" />
										<div class="flex items-center">
											<label class="flex items-center mr-4">
												<input type="radio" name="save_card" value="Yes" v-model="form.save_card" class="mr-2"> 
												<span>Yes</span>
											</label>
											<label class="flex items-center">
												<input type="radio" name="save_card" value="No" v-model="form.save_card" class="mr-2"> 
												<span>No</span>
											</label>
										</div>
									</div>

									<img src="/assets/stripe-badge-transparent2.png" class="mt-6" style="width: 320px;">
								</div>
							</li>

							<li class="mb-2 bg-white relative border border-gray-300 px-6 py-4 transition-all duration-100" :class="{ 'border-primary bg-lightbox': form.payment_option == 'Afterpay' }">
								<div class="cursor-pointer flex items-center" @click="form.payment_option = 'Afterpay'">
									<input type="radio" name="payment_option" value="Afterpay" v-model="form.payment_option" class="mr-2"> 
									<span>Afterpay</span>
								</div>
								<div class="relative overflow-hidden" :class="{ 'max-h-0': form.payment_option !== 'Afterpay' }">
									<div class="mt-2">
										After clicking "Place order", you will be redirected to Afterpay to complete your purchase securely.
									</div>
								</div>
							</li>
						</ul>
					</div>

					<div class="flex justify-between items-center pt-2">
						<a href="#" @click.prevent="form.step = form.step - 1" class="uppercase tracking-widest border-b font-semibold border-secondary pb-1 text-xs">Previous</a>
						<button-primary @click.prevent="nextStep" :busy="busy">Place Order</button-primary>
					</div>
				</div>
			</form>		
			
			<div class="w-full md:w-1/3 p-6" v-if="cart">
				<div class="border border-primary p-4 mb-4">
					<div class="flex items-center mb-2" v-for="item in cart.cart_items" :key="item.id">
						<img src="https://solcleanse.com/wp-content/uploads/2018/03/Custom-organic-juice-pack-delivered.jpg" width="80">
						<div class="ml-3">
							<inertia-link :href="'product/'+item.variant.id" class="">{{ item.variant.title }}</inertia-link>
							<div class="flex items-center">
								<span class="mr-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-primary rounded-full">{{ item.qty }}</span>
								<div>
									<!-- <span v-if="item.subscription_option_id">Total today:</span>  -->
									${{ item.price }}
								</div>
							</div>
							<div class="text-primary italic" v-if="item.subscription_option">
								Renews every {{ item.subscription_option.frequency }} week{{ item.subscription_option.frequency == 1 ? '' : 's' }}: ${{ item.price }} + shipping
							</div>
						</div>
					</div>

					<div class="mt-6">
						<div class="font-medium flex justify-between items-center border-t mt-2 pt-2">
							<span class="text-xs font-medium uppercase tracking-widest">Subtotal</span>
							<span class="font-medium text-right">${{ formatPrice(totals.subtotal) }}</span>
						</div>
						<div class="font-medium flex justify-between items-center border-t mt-2 pt-2" v-if="totals.discount_amount">
							<span class="text-xs font-medium uppercase tracking-widest">Discount</span>
							<span class="font-medium text-right">-${{ formatPrice(totals.discount_amount) }}</span>
						</div>
						<div class="font-medium flex justify-between items-center border-t mt-2 pt-2">
							<span class="text-xs font-medium uppercase tracking-widest">Shipping</span>
							<span class="font-medium text-right">${{ formatPrice(totals.shipping) }}</span>
						</div>
						<div class="font-medium flex justify-between items-center border-t mt-2 pt-2">
							<span class="text-xs font-medium uppercase tracking-widest">Total</span>
							<span class="font-medium text-right">${{ formatPrice(totals.total) }}</span>
						</div>
					</div>
				</div>

				<div class="">
                    <h4 class="text-primary uppercase text-sm tracking-wider mb-2">Shipping Information</h4>
					<p class="text-xs">Shipping is calculated when your subscription order renews. Delivery cost for perishable orders is $19. Non pershiable orders are charged $5 or free is the amount is over $100.</p>
				</div>

			</div>

		</section>

		<dialog-modal :show="cart.user_status == 'Has Account' && !user" max-width="lg">
            <template #title>
                <h2 class="font-serif text-3xl tracking-wider mb-4">Login</h2>
                <!-- <div class="">asd</div> -->
            </template>

            <template #content>
                <login-form :can-reset-password="true"></login-form>
            </template>

            <template #footer>
				
            </template>
        </dialog-modal>

    </layout>
	</div>
</template>

<script>
import Layout from '@/Layouts/App'

import FormLabel from '@/Components/FormLabel'
import FormInput from '@/Components/FormInput'
import FormInputError from '@/Components/FormInputError'
import FormCheckbox from '@/Components/FormCheckbox'
import ButtonPrimary from '@/Components/ButtonPrimary'
import LoginForm from '@/Components/LoginForm'
import DialogModal from '@/Components/DialogModal'

import Datepicker from 'vue3-datepicker'
import VueGoogleAutocomplete from 'vue-google-autocomplete'

import moment from 'moment'

export default {
	props: ['cart', 'totals', 'renewal_orders'],

	computed: {
		user() {
			return this.$page.props.auth.user
		},
	},

	components: {
		Layout,

		FormInput,
		FormInputError,
		FormCheckbox,
		FormLabel,
		ButtonPrimary,
		LoginForm,
		DialogModal,

		Datepicker,
		VueGoogleAutocomplete
	},

	computed: {
		// cart() {
		// 	return this.$page.props.cart
		// },
		// totals() {
		// 	return this.$page.props.totals
		// }
    },

	data() {
		return {
			busy: false,

			stripe: null,
			cardElement: null,

			form: this.$inertia.form({
				step: 1,
				
				// step 1
				email: '',

				// step 2
				first_name: '',
				last_name: '',
				phone: '',

				deliver_with_renewal_order: 'No',
				delivery_date: '',

				shipping_address: '',
				shipping_apartment: '',
				shipping_city: '',
				shipping_postcode: '',
				shipping_state: '',
				shipping_country: 'AU',

				billing_address_same: '',
				billing_address: '',
				billing_apartment: '',
				billing_city: '',
				billing_postcode: '',
				billing_state: '',
				billing_country: '',

				// step 3
				shipping_methods: '',
				shipping_method: '',
				//shipping_price: '',

				discount_code_id: '',
				discount_code: '',
				//discount_amount: '',

				// step 4
				payment_option: 'Card',
				payment_method: this.user && this.user.payment_method ? this.user.payment_method : '',
				payment_intent: '',
				save_card: 'Yes'
			}),
		}
	},

	created() {
		this.moment = moment;

		this.track();

		// hydrate form with cart fields
		for (let property in this.form) {
			if (this.cart[property]) {
				this.form[property] = this.cart[property];
			}
		}

		if (this.form.delivery_date) {
			this.form.delivery_date = moment(this.form.delivery_date).format('YYYY-MM-DD');
		}
		this.form.shipping_method = this.form.shipping_methods[0].shipping_method;

		this.$nextTick(()=> {
			this.stripe = Stripe(this.cart.stripe_key);
			this.cardElement = this.createCardElement('#card-element');
		});

		var today = new Date();
		var upperLimit = new Date().setMonth(today.getMonth() + 6);

		if (this.cart.location_id == 1) {
			var blockedDays = [0, 2, 4, 6];
		} else if (this.cart.location_id == 2) {
			var blockedDays = [0, 1, 3, 5, 6];
		}

		for (var date = today; date <= upperLimit; date.setDate(date.getDate() + 1)) {
			if (this.cart.location_id == 1 && blockedDays.includes(date.getDay())) {
				this.totals.blocked_dates.push(date.getTime());
			}
		}
	},

	mounted() {
		this.$refs.shipping_address.update(this.form.shipping_address);
		// this.$refs.billing_address.update(this.form.billing_address);
	},

	methods: {
		track() {
			var klav_checkout = {
				"$event_id": this.cart + '_' + new Date().getTime(),
				"$value": this.totals.total,
				"ItemNames": this.cart.cart_items.map(item => item.variant.title),
				"CheckoutURL": window.location.protocol+'//'+window.location.hostname+'/checkout',
				//"Categories": ["Fiction", "Children", "Classics"],
				"Items": this.cart.cart_items.map(item => item.variant.product.title)
			};

			var cats = [];
			this.cart.cart_items.forEach(item => {
				var item_cats = [];
				for (var category of item.variant.product.categories) {
					cats.push(category.title); 
					item_cats.push(category.title); 
				}

				cats.concat(item_cats);
				klav_checkout['Items'].push({ 
					"ProductID": item.variant.product.id,
					"SKU": item.variant.sku,
					"ProductName": item.variant.product.title,
					"Quantity": item.qty,
					"ItemPrice": item.price,
					"RowTotal": item.price * item.qty,
					"ProductURL": window.location.protocol+'//'+window.location.hostname+'/'+item.variant.product.url,
					"ImageURL": item.variant.product.image_object.url,
					"ProductCategories": item_cats
				});
			});
			klav_checkout.Categories = cats;

			dataLayer.push({'klav_checkout': klav_checkout});
			dataLayer.push({'event': 'klaviyo_checkout'});
		},

		formatPrice(value) {
			return value.toFixed(2)
		},

		nextStep() {
			this.busy = true;
			this.form.post('/checkout', {
				onSuccess: () => {
					this.busy = false;
					if (this.form.step === 4) {
						this.placeOrder();
					} else {
						this.form.step = this.form.step + 1;
					}
				},
				onError: (response) => {
					this.busy = false;
				},
				// onFinish: () => {
				// 	this.$inertia.reload({
				// 		onFinish: () => {}
				// 	});
				// }
			});
		},

		getShippingAddressData: function (addressData, placeResultData, id) {
			this.form.shipping_address = addressData.street_number + ' ' +addressData.route;
			this.form.shipping_city = addressData.locality;
			this.form.shipping_postcode = addressData.postal_code;
			this.form.shipping_state = addressData.administrative_area_level_1;
			this.form.shipping_country = addressData.country;

			for (var i = 0; i < placeResultData.address_components.length; i++) {
				var addressType = placeResultData.address_components[i].types[0];
				if (addressType == 'country') {
					this.form.shipping_country = placeResultData.address_components[i].short_name;
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
					this.form.billing_country = placeResultData.address_components[i].short_name;
				}
			}
			
			this.$refs.billing_address.update(this.form.billing_address);
		},

        createCardElement(container){
            if (!this.stripe) {
                throw "Invalid Stripe Key/Secret";
            }

            var card = this.stripe.elements().create('card', {
                hideIcon: true,
                hidePostalCode: true,
                style: {
                    base: {
                        fontFamily: 'Open Sans, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"',
                        color: '#000',
                        fontSize: '16px'
                    }
                }
            });

            card.mount(container);

            return card;
        },

		placeOrder() {
			this.busy = true;

			if (this.form.payment_option === 'Existing Card') {
				if (this.form.payment_method) {
					this.chargeUser();
				} else {
					// this should never happen?
				}
			}

			if (this.form.payment_option === 'Card') {
				if (
					this.form.save_card === 'Yes' && 
					//this.cart.user_status !== 'No Password' && // don't allow saving card if checking out as user who's not logged in and doesn't have a password
					(!this.user || (!this.user.location_id || this.user.location_id == this.cart.location_id)) // confusing?
				) { 
					this.createSetupIntent(); // save card
				} else {
					this.createPaymentIntent(); // dont save card
				}
			}

			// afterpay creates an unpaid order first, then completes the payment
			if (this.form.payment_option === 'Afterpay') {
				this.createOrder();
			}
		},

		createPaymentIntent() {
            axios.post('/stripe/payment-intent', this.form).then(response => {
				this.busy = true;

				this.stripe.confirmCardPayment(response.data.clientSecret, {
					payment_method: {
      					card: this.cardElement,
					}
				}).then(response => {
					if (response.error) {
						this.busy = false;
						alert(response.error.message);
					} else {
						this.form.payment_intent = response.paymentIntent.id
						this.createOrder();
					}
				});
			});
        },

		createPaymentIntentAfterpay() {
            axios.post('/stripe/payment-intent', this.form).then(response => {
				this.busy = true;

				this.stripe.confirmAfterpayClearpayPayment(response.data.clientSecret, {
					payment_method: {
						billing_details: {
							email: this.form.email,
							name: this.form.first_name+' '+this.form.last_name,
							address: {
								line1: this.form.shipping_address,
								city: this.form.shipping_city,
								state: this.form.shipping_state,
								country: this.form.shipping_country,
								postal_code: this.form.shipping_postcode,
							},
						},
					},
					return_url: window.location.protocol+'//'+window.location.hostname+'/stripe/afterpay',
				})
			});
        },

        createSetupIntent() {
            axios.post('/stripe/setup-intent', this.form).then(response => {
				this.stripe.confirmCardSetup(response.data.clientSecret, {
					payment_method: {
      					card: this.cardElement,
					}
				}).then(response => {
					if (response.error) {
						this.busy = false;
						alert(response.error.message);
					} else {
						this.form.payment_method = response.setupIntent.payment_method;
						axios.post('/stripe/add-payment-method', this.form).then(response => {
							this.chargeUser();
						});
					}
				});
			});
        },

		chargeUser() {
			axios.post('/stripe/charge-user', this.form).then(response => {
				this.form.payment_intent = response.data.payment.id
				this.createOrder();
			}).catch(error => {
				alert(error);
				this.busy = false;
			});
		},

		createOrder() {
			this.form.post('/orders', {
				onSuccess: (response) => {
					if (this.form.payment_option === 'Afterpay') {
						this.createPaymentIntentAfterpay();
					} else {
						this.busy = false;
					}
				},
				onError: (response) => {
					console.log(response);
					this.busy = false;
				}
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