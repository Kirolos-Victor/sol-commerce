<template>
    <modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div class="p-6">
            <slot name="title"> </slot>
            <div class="mt-4">
                <slot name="content">
                </slot>
            </div>
        </div>

        <div >
            <slot name="footer" class="px-6 text-right">
            </slot>
        </div>
    </modal>
</template>

<script>
    import Modal from './Modal.vue'

    export default {
        emits: ['close'],

        components: {
            Modal,
        },

        props: {
            show: {
                default: false
            },
            maxWidth: {
                default: '2xl'
            },
            closeable: {
                default: true
            },
        },

        methods: {
            close() {
                this.$emit('close')
            },
        }
    }
</script>
