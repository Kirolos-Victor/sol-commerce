<template>
    <button v-if="!href" :type="type" :disabled="busy" class="text-center uppercase text-white bg-accent text-xs tracking-widest font-semibold py-3 px-8 outline-none transition duration-500 focus:outline-none hover:bg-accentdark inline-flex justify-center items-center" >
		
		<svg v-if="busy" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
			<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
			<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
		</svg> 

		<slot />
    </button>
	<inertia-link v-else :href="href" class="block text-center uppercase text-white bg-accent text-xs tracking-widest font-semibold py-3 px-8 outline-none transition duration-500 focus:outline-none hover:bg-accentdark">
        <slot />
    </inertia-link>
</template>

<script>
export default {
	props: {
		type: {
			type: String,
			default: 'submit',
		},
		href: {
			type: String,
			default: '',
		},
		busy: {
			type: Boolean,
			default: false
		}
	}
}
</script>
