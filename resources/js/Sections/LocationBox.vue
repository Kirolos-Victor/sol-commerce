<template>
	<section class="mt-20 container max-w-screen-md mx-auto px-6">
		<div class="p-8 border border-primary relative">
			<div class="font-serif text-2xl tracking-wider transform -translate-y-1/2 -translate-x-1/2 px-4 bg-light inline-block absolute top-0 left-1/2">Delivery Information</div>

			<a href="#" @click.prevent="" class="absolute top-4 right-4 w-3">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14">
					<path id="Path_3" data-name="Path 3" d="M525.493,313.25l6.1-6.1a.525.525,0,0,0-.742-.742l-6.1,6.1-6.1-6.1a.525.525,0,0,0-.742.742l6.1,6.1-6.1,6.1a.525.525,0,1,0,.742.742l6.1-6.1,6.1,6.1a.525.525,0,0,0,.742-.742Z" transform="translate(-517.75 -306.25)" fill="#888"/>
				</svg>
			</a>

			<location-form></location-form>
		</div>
	</section>
</template>

<script>
import LocationForm from '@/Sections/LocationForm'

export default {
	components: {
		LocationForm
	},

	computed: {
		// cart() {
		// 	return this.$page.props.cart
		// }
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
