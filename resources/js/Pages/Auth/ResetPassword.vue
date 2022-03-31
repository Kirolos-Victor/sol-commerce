<template>
    <layout>
	
		<section class="mt-20 mb-10 container max-w-screen-md mx-auto px-6 text-center">
			<h1 class="font-serif text-4xl tracking-wider mt-2 mb-4">Reset password</h1>
		</section>

		<section class="mb-10 container mx-auto" style="max-width: 500px;">
            <validation-errors class="mb-4" />

            <form @submit.prevent="submit" class="w-full">
                <div>
                    <form-label for="email" value="Email" />
                    <form-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus autocomplete="username" />
                </div>

                <div class="mt-4">
                    <form-label for="password" value="Password" />
                    <form-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password" />
                </div>

                <div class="mt-4">
                    <form-label for="password_confirmation" value="Confirm Password" />
                    <form-input id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required autocomplete="new-password" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button-primary :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Reset Password
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
            email: String,
            token: String,
        },

        data() {
            return {
                form: this.$inertia.form({
                    token: this.token,
                    email: this.email,
                    password: '',
                    password_confirmation: '',
                })
            }
        },

        methods: {
            submit() {
                this.form.post(this.route('password.update'), {
                    onFinish: () => this.form.reset('password', 'password_confirmation'),
                })
            }
        }
    }
</script>
