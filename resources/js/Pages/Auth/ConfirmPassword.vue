<template>
    <layout>
	
		<section class="mt-20 mb-10 container max-w-screen-md mx-auto px-6 text-center">
			<h1 class="font-serif text-4xl tracking-wider mt-2 mb-4">Confirm password</h1>
            <div class="mb-4 text-sm text-gray-600">
                This is a secure area of the application. Please confirm your password before continuing.
            </div>
		</section>
    
        <section class="mb-10 container mx-auto flex flex-wrap justify-center" style="max-width: 500px;">
            <validation-errors class="mb-4" />

            <form @submit.prevent="submit" class="w-full">
                <div>
                    <form-label for="password" value="Password" />
                    <form-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="current-password" autofocus />
                </div>

                <div class="flex justify-end mt-4">
                    <button-primary class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Confirm
                    </button-primary>
                </div>
            </form>
        </section>

    </layout>
</template>

<script>
    import ButtonPrimary from '@/Components/ButtonPrimary'
    import Layout from "@/Layouts/App"
    import FormInput from '@/Components/FormInput'
    import FormLabel from '@/Components/FormLabel'
    import ValidationErrors from '@/Components/ValidationErrors'

    export default {
        components: {
			Layout,
            ButtonPrimary,
            FormInput,
            FormLabel,
            ValidationErrors,
        },

        data() {
            return {
                form: this.$inertia.form({
                    password: '',
                })
            }
        },

        methods: {
            submit() {
                this.form.post(this.route('password.confirm'), {
                    onFinish: () => this.form.reset(),
                })
            }
        }
    }
</script>
