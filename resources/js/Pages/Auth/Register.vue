<template>
    <layout>
	
		<section class="mt-20 mb-10 container max-w-screen-md mx-auto px-6 text-center">
			<h1 class="font-serif text-4xl tracking-wider mt-2 mb-4">Register</h1>
            <inertia-link :href="route('login')" class="text-sm">
                Already have an account? <span class="text-primary">Login here.</span>
            </inertia-link>
		</section>

		<section class="mb-20 container mx-auto" style="max-width: 500px;">
            <validation-errors class="mb-4" />

            <div v-if="form.errors.email && form.errors.email === 'The email has already been taken.'" class="border border-primary p-3 text-center bg-lightbox mt-4">
                Please <inertia-link href="/forgot-password" class="text-primary">reset your password</inertia-link> or <inertia-link :href="route('login')" class="text-primary">login</inertia-link> instead.
            </div> 

            <form @submit.prevent="submit" class="w-full">
                <div>
                    <form-label for="first_name" value="First Name" />
                    <form-input id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name" required autofocus autocomplete="given-name" />
                </div>

                <div class="mt-4">
                    <form-label for="last_name" value="Last Name" />
                    <form-input id="last_name" type="text" class="mt-1 block w-full" v-model="form.last_name" required autofocus autocomplete="family-name" />
                </div>

                <div class="mt-4">
                    <form-label for="email" value="Email" />
                    <form-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="username" />
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
                    <button-primary class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Register
                    </button-primary>
                </div>
            </form>
		</section>

    </layout>
</template>

<script>
    import ButtonPrimary from '@/Components/ButtonPrimary'
    import Layout from '@/Layouts/App'
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
                    first_name: '',
                    last_name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    terms: false,
                })
            }
        },

        methods: {
            submit() {
                this.form.post(this.route('register'), {
                    onFinish: () => this.form.reset('password', 'password_confirmation'),
                })
            }
        }
    }
</script>
