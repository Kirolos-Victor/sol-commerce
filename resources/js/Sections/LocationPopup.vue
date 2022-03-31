<template>
	<dialog-modal :show="(!product || product.perishable) && !dismissedLocationPopup && (!cart || !cart.location_id)" @close="dismissLocationPopup()" max-width="2xl">
		<template #title>
			<div class="flex justify-between items-center">
				<div class="font-serif text-2xl tracking-wider">Delivery Information</div>

				<a href="#" @click.prevent="dismissLocationPopup" class="w-3">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14">
						<path id="Path_3" data-name="Path 3" d="M525.493,313.25l6.1-6.1a.525.525,0,0,0-.742-.742l-6.1,6.1-6.1-6.1a.525.525,0,0,0-.742.742l6.1,6.1-6.1,6.1a.525.525,0,1,0,.742.742l6.1-6.1,6.1,6.1a.525.525,0,0,0,.742-.742Z" transform="translate(-517.75 -306.25)" fill="#888"/>
					</svg>
				</a>
			</div>
		</template>

		<template #content>
			<location-form></location-form>
		</template>

		<template #footer>
			
		</template>
	</dialog-modal>
</template>

<script>
import LocationForm from '@/Sections/LocationForm'
import DialogModal from '@/Components/DialogModal'

export default {
	props: ['product'],

	components: {
		LocationForm,
		DialogModal,
	},

	computed: {
		
    },

	data() {
		return {
			dismissedLocationPopup: sessionStorage.getItem("dismissLocationPopup") ? true : false,
		}
	},

	created() {

	},

	methods: {
		dismissLocationPopup() {
        	sessionStorage.setItem("dismissLocationPopup", true);
			this.dismissedLocationPopup = true;
		},
	}
}
</script>
