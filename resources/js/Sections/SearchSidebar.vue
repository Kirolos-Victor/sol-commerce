<template>
	<transition name="fade">
		<div v-if="show" class="fixed top-0 right-0 h-full w-full bg-black bg-opacity-50 z-40 transition-opacity"></div>
	</transition>

    <div class="fixed top-0 right-0 h-full bg-light shadow-lg w-96 border-l transition-position duration-500 z-50" :class="{ '-right-96': !show }" v-click-away="show ? onClickAway : false">
		
		<div class="p-6 flex justify-between items-center">
			<div class="font-serif text-4xl tracking-wider">Search</div>
			<a href="#" @click.prevent="show = false" class="text-xl">
				<svg class="w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14">
					<path id="Path_3" data-name="Path 3" d="M525.493,313.25l6.1-6.1a.525.525,0,0,0-.742-.742l-6.1,6.1-6.1-6.1a.525.525,0,0,0-.742.742l6.1,6.1-6.1,6.1a.525.525,0,1,0,.742.742l6.1-6.1,6.1,6.1a.525.525,0,0,0,.742-.742Z" transform="translate(-517.75 -306.25)" fill="#888"/>
				</svg>
			</a>
		</div>

		<form class="p-6">
			<div class="mb-2">
				<input id="search" type="text" placeholder="Search products" v-model="form.search" class="text-sm mb-3 bg-transparent border-black p-0 py-1 border-0 border-b w-full focus:border-black focus:ring-0" required />
			</div>
			<button-primary @click.prevent="processSearch">Search</button-primary>
		</form>

    </div>
</template>

<script>
import emitter from "@/emitter";
import { mixin as VueClickAway } from "vue3-click-away";

import ButtonPrimary from '@/Components/ButtonPrimary'

export default {
	props: [],

	components: {
		ButtonPrimary,
	},

	mixins: [VueClickAway],

	computed: {
		search() {
			return this.$page.props.search
		},
	},

	data() {
		return {
			show: false,

			form: this.$inertia.form({
				search: ''
			})
		}
	},

	created() {
		// if (this.search) {
		// 	this.form.search = this.search;
		// }

		emitter.on('showSearchSidebar', event => {
			this.show = true;
		});
	},

	methods: {
		onClickAway(event) {
			this.show = false;
		},

		processSearch() {
			this.$inertia.visit('/shop?search='+this.form.search);
		}
	}
}
</script>

<style scoped>

</style>>