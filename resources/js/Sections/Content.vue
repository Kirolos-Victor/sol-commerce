<template>
    <section class="container max-w-screen-lg mx-auto">
        <div v-if="content.content" v-html="content.content" class="wysiwyg"></div>
    </section>
</template>

<script>
export default {
	props: ['content'],

	components: {

    },
}
</script>
