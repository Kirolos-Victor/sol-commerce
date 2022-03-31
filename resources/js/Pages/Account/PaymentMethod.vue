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
						<div class="text-lg mb-4">Update payment method ending in {{ user.pm_last_four }}</div>
						<div class="mb-2">
							<div id="card-element" class="w-full bg-white border p-3 border-primary focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-25"></div>
							<form-input-error :message="form.errors.card" />
						</div>
					</div>

					<button-primary @click.prevent="createSetupIntent" :busy="busy">Update Payment Method</button-primary>
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

export default {
	props: [],

	computed: {
		user() {
			return this.$page.props.auth.user
		},
	},

	components: {
		Layout,
		AccountMenu,

		FormInput,
		FormInputError,
		FormCheckbox,
		FormLabel,
		ButtonPrimary,
	},

	data() {
		return {
			busy: false,
			stripe: null,
			cardElement: null,
			
			success: '',

			form: this.$inertia.form({
				payment_method: this.user && this.user.payment_method ? this.user.payment_method : '',
			}),
		}
	},

	created() {
		this.$nextTick(()=> {
			this.stripe = Stripe(this.user.stripe_key);
			this.cardElement = this.createCardElement('#card-element');
		});
	},

	methods: {
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
		
		createSetupIntent() {
			this.busy = true;
            axios.post('/stripe/setup-intent', this.form).then(response => {
				this.stripe.confirmCardSetup(response.data.clientSecret, {
					payment_method: {
      					card: this.cardElement,
					}
				}).then(response => {
					this.busy = false;
					if (response.error) {
						alert(response.error.message);
					} else {
						this.success = 'Your payment method has been updated.';
					}
				});
			});
        },
	}
}
</script>

<style scoped>

</style>