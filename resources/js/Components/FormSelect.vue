<template>
    <select class="w-full border-primary focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-25" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)"><slot/></select>
</template>

<script>
    export default {
        props: ['modelValue'],

        emits: ['update:modelValue'],

        methods: {
            focus() {
                this.$refs.input.focus()
            }
        }
    }
</script>

