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
        <div class="text-center mb-10">
            <h2 class="text-3xl font-serif text-ink mb-2">Welcome Back</h2>
            <p class="text-ink-light text-sm font-light tracking-wide">Please sign in to continue your collection.</p>
        </div>

        <div v-if="status" class="mb-6 p-3 bg-green-50 text-green-700 text-sm font-medium rounded-lg text-center border border-green-200">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <InputLabel for="email" value="Email" class="text-xs uppercase tracking-wider text-ink-light mb-2" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full bg-white border-divider focus:border-accent focus:ring-accent rounded-lg px-4 py-3 transition-colors duration-300 placeholder:text-ink-light/30"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Enter your email"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="password" value="Password" class="text-xs uppercase tracking-wider text-ink-light mb-2" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full bg-white border-divider focus:border-accent focus:ring-accent rounded-lg px-4 py-3 transition-colors duration-300 placeholder:text-ink-light/30"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="pt-2 flex flex-col items-center justify-end gap-5">
                 <PrimaryButton
                    class="w-full justify-center py-4 text-base font-serif bg-accent hover:bg-accent-hover focus:ring-accent shadow-lg shadow-accent/20 transition-all duration-300 transform hover:-translate-y-0.5"
                    :class="{ 'opacity-75 cursor-not-allowed': form.processing }"
                    :disabled="form.processing"
                >
                    Log In
                </PrimaryButton>

                <div class="flex flex-col items-center gap-4 text-sm w-full">
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-ink-light hover:text-accent transition-colors duration-200"
                    >
                        Forgot your password?
                    </Link>
                    
                    <div class="relative w-full text-center">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-divider"></div>
                        </div>
                        <span class="relative px-3 text-xs text-ink-light/70 uppercase tracking-widest bg-canvas">or</span>
                    </div>
                    
                     <button
                        type="button"
                        @click="$emit('switchToRegister')"
                        class="text-accent font-serif italic text-lg hover:text-accent-hover transition-colors duration-200 hover:tracking-wide"
                    >
                        Create new account
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>
