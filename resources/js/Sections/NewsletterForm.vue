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

		<button type="submit" class="uppercase tracking-widest border-b font-semibold border-secondary pb-1 text-xs">Subscribe</button>
    </form>

	<div v-if="success" class="mt-2 font-medium text-sm italic text-green-600">
		{{ success }}
	</div>

</template>

<script>
import FormLabel from '@/Components/FormLabel'
import FormInputError from '@/Components/FormInputError'

export default {
	props: {
		grid: {
			type: Boolean,
			default: true,
		},
	},

	components: {
		FormInputError,
		FormLabel,
	},

	computed: {

    },

	data() {
		return {
			success: '',

			form: this.$inertia.form({
				first_name: '',
				email: '',
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
					dataLayer.push({'klav_identify': {
						'$email': this.form.email,
						'$first_name': this.form.first_name,
					}});

					dataLayer.push({'event': 'klaviyo_identify'});

					this.success = 'Subscribed to the newsletter';
					
					this.form.reset()
				}
			});
		}
	}
}
</script>
