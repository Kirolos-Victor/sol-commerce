<template>
    <section class="container max-w-screen-lg mx-auto px-6">
        <div v-for="(tab, index) in content.tabs" :key="tab.key" class="border-b border-primary py-2" :class="{ 'border-t': index == 0 }" :id="tab.attributes.heading.replace(/[^A-Z0-9]/ig, '')">

            <a href="#" @click.prevent="tab.show ? tab.show = false : tab.show = true" class="flex justify-between items-center text-primary uppercase text-xs py-2">
                <div class="uppercase tracking-widest">{{ tab.attributes.heading }}</div>
                <img v-if="!tab.show" src="/assets/icons/plus.svg">
                <img v-else src="/assets/icons/minus-thick.svg">
            </a>
            <div v-if="tab.show" v-html="tab.attributes.content"></div>

        </div>
    </section>
</template>

<script>
export default {
	props: ['content'],

	components: {

	},
}
</script>
