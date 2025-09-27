<script setup>
    import { Head, Link, useForm } from '@inertiajs/vue3';
    import AuthenticationCardLogo from '@/Components/Utils/AuthenticationCardLogo.vue';
    import Checkbox from '@/Components/Inputs/Checkbox.vue';
    import InputError from '@/Components/Inputs/InputError.vue';
    import CardCustomLogin from '@/Components/Cards/CardCustomLogin.vue';
    import NativeButton from '@/Components/Buttons/NativeButton.vue';
    import InputRevealablePassword from '@/Components/Inputs/InputRevealablePassword.vue';
    import InputLabelClasicc from '@/Components/Inputs/InputLabelClasicc.vue';

    defineProps({
        canResetPassword: Boolean,
        status: String,
    });

    const form = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = () => {
        form.transform(data => ({
            ...data,
            remember: form.remember ? 'on' : '',
        })).post(route('login'), {
            onFinish: () => form.reset('password'),
        });
    };
</script>

<template>
    <Head title="Log in" />

    <div
        class="h-screen bg-cover bg-center bg-no-repeat overflow-hidden flex items-center justify-center relative"
        style="background-image: url('/System/samples/fondo-login.webp')"
    >
        <CardCustomLogin>
            <template #logo>
                <AuthenticationCardLogo />
            </template>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <InputLabelClasicc
                        v-model="form.email"
                        label="Email"
                        type="email"
                        autofocus
                        theme="gray"
                        size="base"
                        required="true"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4">
                    <InputRevealablePassword
                        v-model="form.password"
                        label="Contraseña"
                        theme="gray"
                        size="base"
                        required="true"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="block mt-4">
                    <label class="flex items-center">
                        <Checkbox
                            v-model:checked="form.remember"
                            name="remember"
                        />
                        <span class="ms-2 text-sm text-gray-600"
                            >Remember me</span
                        >
                    </label>
                </div>

                <div class="flex flex-col items-center justify-center mt-4">
                    <NativeButton
                        :theme="'gray'"
                        :loading="form.processing"
                        :disabled="form.processing"
                        class="h-11 w-full md:w-full"
                    >
                        Iniciar sesión
                    </NativeButton>
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="underline mt-4 text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Forgot your password?
                    </Link>
                </div>
            </form>
        </CardCustomLogin>
    </div>
</template>
