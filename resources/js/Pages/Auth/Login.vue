<template>
    <layout>
	
		<section class="mt-20 mb-10 container max-w-screen-md mx-auto px-6 text-center">
			<h1 class="font-serif text-4xl tracking-wider mt-2 mb-4">Login</h1>
            <inertia-link :href="route('register')" class="text-sm">
                New to Solcleanse? <span class="text-primary">Register here.</span>
            </inertia-link>
		</section>

		<section class="mb-10 container mx-auto" style="max-width: 500px;">
            <login-form></login-form>
		</section>

    </layout>
</template>

<script>
    import ButtonPrimary from '@/Components/ButtonPrimary'
    import Layout from "@/Layouts/App"
    import FormInput from '@/Components/FormInput'
    import FormCheckbox from '@/Components/FormCheckbox'
    import FormLabel from '@/Components/FormLabel'
    import ValidationErrors from '@/Components/ValidationErrors'
    import LoginForm from '@/Components/LoginForm'

    export default {
        components: {
			Layout,
            ButtonPrimary,
            FormInput,
            FormCheckbox,
            FormLabel,
            ValidationErrors,
            LoginForm
        },

        props: {
            auth: Object,
            canResetPassword: Boolean,
            errors: Object,
            status: String,
        },

        data() {
            return {
                form: this.$inertia.form({
                    email: '',
                    password: '',
                    remember: false
                })
            }
        },

        methods: {
            submit() {
                this.form
                    .transform(data => ({
                        ... data,
                        remember: this.form.remember ? 'on' : ''
                    }))
                    .post(this.route('login'), {
                        onFinish: () => this.form.reset('password'),
                    })
            }
        }
    }
</script>
