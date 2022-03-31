<template>
    <validation-errors class="mb-4" />

    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
    </div>

    <form @submit.prevent="submit" class="w-full">
        <div>
            <form-label for="email" value="Email" />
            <form-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus autocomplete="username" />
        </div>

        <div class="mt-4">
            <form-label for="password" value="Password" />
            <form-input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="current-password" />
        </div>

        <!-- <div class="block mt-4">
            <label class="flex items-center">
                <form-checkbox name="remember" v-model:checked="form.remember" />
                <span class="ml-2 text-sm">Remember me</span>
            </label>
        </div> -->

        <div class="flex items-center justify-end mt-4">
            <inertia-link v-if="canResetPassword" :href="route('password.request')" class="text-primary text-sm">
                Forgot your password?
            </inertia-link>

            <button-primary class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Log in
            </button-primary>
        </div>
    </form>
</template>

<script>
    import ButtonPrimary from '@/Components/ButtonPrimary'
    import FormInput from '@/Components/FormInput'
    import FormCheckbox from '@/Components/FormCheckbox'
    import FormLabel from '@/Components/FormLabel'
    import ValidationErrors from '@/Components/ValidationErrors'

    export default {
        components: {
            ButtonPrimary,
            FormInput,
            FormCheckbox,
            FormLabel,
            ValidationErrors
        },

        props: {
            auth: Object,
            canResetPassword: {
                type: Boolean,
                default: true
            },
            errors: Object,
            status: String,
            user: Object
        },

        data() {
            return {
                form: this.$inertia.form({
                    email: '',
                    password: '',
                    remember: true
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
                        onFinish: () => {
                            this.form.reset('password');
                        },
                        onSuccess: () => {
                            //this.$inertia.reload({ only: ['user'] });
                            location.reload();
                        },
                    })
            }
        }
    }
</script>
