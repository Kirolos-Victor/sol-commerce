<template>
	<inertia-link :href="href" class="uppercase tracking-widest border-b font-semibold border-secondary pb-1 text-xs hover:text-secondary">
		<slot />
	</inertia-link>
</template>

<script>
    export default {
        props: ['href']
    }
</script>