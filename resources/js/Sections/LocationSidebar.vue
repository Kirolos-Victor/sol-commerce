<template>
	<transition name="fade">
		<div v-if="show" class="fixed top-0 right-0 h-full w-full bg-black bg-opacity-50 z-40 transition-opacity"></div>
	</transition>

    <div class="fixed top-0 left-0 h-full bg-light shadow-lg p-5 w-96 border-l transition-position duration-500 z-50" :class="{ '-left-96': !show }" v-click-away="show ? onClickAway : false">
		
		<div class="flex justify-between items-center mb-5">
			<div class="font-serif text-2xl tracking-wider mt-2 mb-4">Select your location</div>
			<a href="#" @click.prevent="show = false" class="text-xl">
				<svg class="w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14">
					<path id="Path_3" data-name="Path 3" d="M525.493,313.25l6.1-6.1a.525.525,0,0,0-.742-.742l-6.1,6.1-6.1-6.1a.525.525,0,0,0-.742.742l6.1,6.1-6.1,6.1a.525.525,0,1,0,.742.742l6.1-6.1,6.1,6.1a.525.525,0,0,0,.742-.742Z" transform="translate(-517.75 -306.25)" fill="#888"/>
				</svg>
			</a>
		</div>
		
		<location-form></location-form>

    </div>
</template>

<script>
import emitter from "@/emitter";
import { mixin as VueClickAway } from "vue3-click-away";

import LocationForm from '@/Sections/LocationForm'
import ButtonPrimary from '@/Components/ButtonPrimary'

export default {
	props: [],

	components: {
		LocationForm,
		ButtonPrimary,
	},

	mixins: [VueClickAway],

	computed: {

    },

	data() {
		return {
			show: false,
		}
	},

	created() {
		emitter.on('showLocationSidebar', event => {
			this.show = true;
		});

		emitter.on('hideLocationSidebar', event => {
			this.show = false;
		});
	},

	methods: {
		onClickAway(event) {
			this.show = false;
		},
	}
}
</script>
