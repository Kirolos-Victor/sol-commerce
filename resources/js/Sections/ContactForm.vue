<template>

	<form @submit.prevent="submit">
		<div :class="{ 'grid md:grid-cols-2 md:gap-12 mb-2': grid }">
			<div class="mb-2">
				<input id="first_name" type="text" placeholder="First name" v-model="form.first_name" class="text-sm mb-3 bg-transparent border-black p-0 py-1 border-0 border-b w-full focus:border-black focus:ring-0" required />
				<form-input-error :message="form.errors.name" />
			</div>
			<div class="mb-2">
				<input id="email" type="email" placeholder="Email address" v-model="form.email" class="text-sm mb-3 bg-transparent border-black p-0 py-1 border-0 border-b w-full focus:border-black focus:ring-0" required />
				<form-input-error :message="form.errors.email" />
			</div>
		</div>
		<div class="mb-2">
			<select id="location" name="location" v-model="form.location" class="text-sm mb-3 bg-transparent border-black p-0 py-1 border-0 border-b w-full focus:border-black focus:ring-0" required>
				<option value="">Select location</option>
				<option value="Gold Coast kitchen">Gold Coast kitchen</option>
				<option value="Sydney kitchen">Sydney kitchen</option>
				<option value="Rest of Australia">Rest of Australia</option>
			</select>
			<form-input-error :message="form.errors.location" />
		</div>
		<div class="mb-2">
			<textarea id="message" placeholder="Message" v-model="form.message" class="text-sm mb-3 bg-transparent border-black p-0 py-1 border-0 border-b w-full focus:border-black focus:ring-0" required rows="5" />
			<form-input-error :message="form.errors.message" />
		</div>

		<button-primary :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Submit</button-primary>
    </form>

	<div v-if="success" class="mt-2 font-medium text-sm italic text-green-600">
		{{ success }}
	</div>

</template>

<script>
import FormLabel from '@/Components/FormLabel'
import FormInputError from '@/Components/FormInputError'
import ButtonPrimary from '@/Components/ButtonPrimary'

export default {
	props: {
		grid: {
			type: Boolean,
			default: true,
		},
		subject: {
			type: String,
			default: 'Contact Enquiry',
		},
		alert: {
			type: String,
			default: 'Thanks for enquiring. We\'ll be in touch soon.',
		}
	},

	components: {
		FormInputError,
		FormLabel,
		ButtonPrimary
	},

	computed: {

    },

	data() {
		return {
			success: '',

			form: this.$inertia.form({
				first_name: '',
				email: '',
				location: '',
				message: '',

				subject: this.subject
			}),
		}
	},

	created() {

	},

	methods: {
		submit() {
			this.form.post('/users', {
				preserveScroll: true,
				onSuccess: () => {			
					this.success = this.alert;
					
					dataLayer.push({'klav_identify': {
						'$email': this.form.email,
						'$first_name': this.form.first_name,
					}});

					dataLayer.push({'event': 'klaviyo_identify'});
					
					this.form.reset()
				}
			});
		}
	}
}
</script>
