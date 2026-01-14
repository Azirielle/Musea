<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    canResetPassword: {
        type: Boolean,
        default: true,
    },
    status: {
        type: String,
    },
});

const emit = defineEmits(['switchToRegister', 'success']);

const form = useForm({
    email: '',
    password: '',
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
        onSuccess: () => emit('success'),
    });
};
</script>

<template>
    <div>
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-[#1A1A1A]">Welcome Back</h2>
            <p class="text-gray-500 text-sm">Please sign in to continue.</p>
        </div>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full border-gray-300 focus:border-[#CBA35C] focus:ring-[#CBA35C] rounded-lg"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full border-gray-300 focus:border-[#CBA35C] focus:ring-[#CBA35C] rounded-lg"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-6 flex flex-col items-center justify-end gap-3">
                 <PrimaryButton
                    class="w-full justify-center py-3 bg-[#CBA35C] hover:bg-[#B89350] focus:ring-[#CBA35C]"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>

                <div class="flex flex-col items-center mt-2 gap-2 text-sm">
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-gray-500 underline hover:text-[#CBA35C]"
                    >
                        Forgot your password?
                    </Link>
                    
                    <span class="text-gray-400">or</span>
                    
                     <button
                        type="button"
                        @click="$emit('switchToRegister')"
                        class="text-[#CBA35C] font-semibold hover:underline"
                    >
                        Create new account
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>
