<template>
	<p v-if="message" class="mb-3">Due to the perishable nature of our organic cleanses and food packs, we’re only able to deliver those items to specific regions that are close to our kitchens. Non-perishable goods are available for delivery Australia-wide.</p>

	<div v-if="!location">
		<p class="mb-4 font-semibold">Enter your post code below to see what’s available for delivery in your area.</p>
		<form @submit.prevent="findLocation">
			<input type="text" name="postcode" v-model="form.postcode" placeholder="Postcode" class="text-sm bg-transparent border-black p-0 py-1 border-0 border-b w-64 focus:border-black focus:ring-0">
			<button type="submit" class="-ml-3">
				<svg xmlns="http://www.w3.org/2000/svg" class="inline-block" width="12" viewBox="0 0 11.387 11.269">
					<g id="Icon_feather-arrow-right" data-name="Icon feather-arrow-right" transform="translate(0.5 0.707)">
						<path id="Path_3" data-name="Path 3" d="M7.5,18H17.887" transform="translate(-7.5 -13.073)" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"/>
						<path id="Path_4" data-name="Path 4" d="M18,7.5l4.927,4.927L18,17.354" transform="translate(-12.54 -7.5)" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="1"/>
					</g>
				</svg>
			</button>
		</form>
		<div v-if="locationError" class="mt-1 text-sm text-red-500">Please enter your postcode.</div>
	</div>

	<div v-if="location">
		<div v-if="location.non_perishable">
			<p class="mb-3 font-semibold">We don't deliver our juices and cleanses to this postcode but you can still purchase our other products.</p>
			<div class="flex mb-4">
				<span class="text-2xl mr-2" style="line-height: 30px; color: #C77C65;">&bull;</span> 
				<div>
					<div class="font-serif text-lg">Australia wide: non-perishable products only</div>
					<!-- <div>{{ location.address }}</div> -->
				</div>
			</div>
			<button-primary @click.prevent="selectLocation" class="w-64">Select Location</button-primary>
		</div>
		<div v-else>
			<p class="mb-3 font-semibold">Great news! Our {{ location.name }} Kitchen can deliver to your area.</p>
			<div class="flex mb-4">
				<span class="text-2xl mr-2" style="line-height: 30px; color: #C77C65;">&bull;</span> 
				<div>
					<div class="font-serif text-lg">{{ location.name }}</div>
					<!-- <div>{{ location.address }}</div> -->
				</div>
			</div>
			<button-primary @click.prevent="selectLocation" class="w-64">Select Location</button-primary>
		</div>
	</div>
</template>

<script>
import emitter from "@/emitter";
import ButtonPrimary from '@/Components/ButtonPrimary'

export default {
	props: {
		message: {
			type: Boolean,
			default: true,
		},
		locationError: {
			type: String,
			default: '',
		},
	},

	components: {
		ButtonPrimary,
	},

	// computed: {
	// 	cart() {
	// 		return this.$page.props.cart
	// 	}
    // },

	data() {
		return {
			form: this.$inertia.form({
				postcode: '',
				location_id: '',
				non_perishable: false
			}),

			location: '',
			postcodeNotFound: false
		}
	},

	created() {

	},

	methods: {
		findLocation() {
			if (!this.form.postcode) { return false; }

			this.postcodeNotFound = false;
			axios.get('/locations?postcode='+this.form.postcode)
				.then(response => {
					this.location = response.data.location;

					if (this.location) {
						this.form.location_id = this.location.id
						this.form.non_perishable = this.location.non_perishable
					} else {
						this.postcodeNotFound = true;
					}
				});
		},

		selectLocation() {
			axios.post('/cart/location', this.form)
				.then(response => {
					sessionStorage.setItem("dismissLocationPopup", true);
					location.reload();
				});

			// this.form.post('/cart/location', {
			// 	onSuccess: (response) => {
			// 		emitter.emit('hideLocationSidebar');
			// 		this.$inertia.reload({ only: ['cart'] })
			// 	}
			// });
		}
	}
}
</script>
