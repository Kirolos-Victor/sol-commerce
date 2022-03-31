<template>
	<div>
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
							<div class="text-lg mb-4">Update password</div>
							<div class="mb-2">
								<form-label for="password" value="New Password" />
								<form-input id="password" type="password" v-model="form.password" required autocomplete="username" />
								<form-input-error :message="form.errors.password" />
							</div>
						</div>

						<button-primary @click.prevent="updatePassword">Update Password</button-primary>
					</form>

					<div v-if="success" class="mt-2 font-medium text-sm italic text-green-600">
						{{ success }}
					</div>

				</div>
			</section>

		</layout>
	</div>
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
		ButtonPrimary
	},

	data() {
		return {
			success: '',

			form: this.$inertia.form({
				password: ''
			}),
		}
	},

	mounted() {
		
	},

	methods: {
		updatePassword() {
			this.form.post('/account/password', {
				onSuccess: () => {
					this.form.reset()
					this.success = 'Password updated'
				},
				onError: (response) => {
					console.log(response)
				}
			});
		}
	}
}
</script>

<style scoped>

</style>