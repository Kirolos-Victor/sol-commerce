<template>
    <layout>
	
		<section class="mt-20 mb-10 container max-w-screen-md mx-auto px-6 text-center">
			<h1 class="font-serif text-4xl tracking-wider mt-2 mb-4">Reset password</h1>
            <div class="mb-4 text-sm">
                Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
            </div>
		</section>
    
        <section class="mb-10 container mx-auto" style="max-width: 500px;">
        
            <validation-errors class="mb-4" />

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="w-full">
                <div>
                    <form-label for="email" value="Email" />
                    <form-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus autocomplete="username" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button-primary :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Email Password Reset Link
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

        props: {
            status: String
        },

        data() {
            return {
                form: this.$inertia.form({
                    email: ''
                })
            }
        },

        methods: {
            submit() {
                this.form.post(this.route('password.email'))
            }
        }
    }
</script>
