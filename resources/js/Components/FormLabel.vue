<template>
    <label class="">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>

<script>
    export default {
        props: ['value']
    }
</script>
