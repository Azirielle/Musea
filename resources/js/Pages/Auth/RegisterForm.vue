<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { VueTelInput } from 'vue-tel-input';
import 'vue-tel-input/vue-tel-input.css';
import { Link, useForm } from '@inertiajs/vue3';

const emit = defineEmits(['switchToLogin', 'success']);

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    address: '',
    contact_number: '',
    password: '',
    password_confirmation: '',
});

const validateName = (field) => {
    const value = form[field];
    if (!value) return;

    // Check for leading spaces
    if (value.startsWith(' ')) {
        form.setError(field, 'Name cannot start with a space.');
        return;
    }

    // Regex: Only letters, spaces, and periods. No numbers, emojis, or other symbols.
    const regex = /^[a-zA-Z. ]*$/;
    if (!regex.test(value)) {
        form.setError(field, 'Name can only contain letters, spaces, and periods.');
    } else {
        form.clearErrors(field);
    }
};

const submit = () => {
    validateName('first_name');
    validateName('last_name');

    if (form.errors.first_name || form.errors.last_name) {
        return;
    }

    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
        onSuccess: () => emit('success'),
    });
};
</script>

<template>
    <div>
        <div class="text-center mb-10">
            <h2 class="text-3xl font-serif text-ink dark:text-white mb-2">Join Musea</h2>
            <p class="text-ink-light dark:text-gray-400 text-sm font-light tracking-wide">Create an account to start your collection.</p>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <InputLabel for="first_name" value="First Name" class="text-xs uppercase tracking-wider text-ink-light dark:text-gray-400 mb-2" />
                    <TextInput
                        id="first_name"
                        type="text"
                        class="mt-1 block w-full bg-white/50 dark:bg-black/20 border-divider dark:border-white/10 focus:border-[#CBA35C] focus:ring-[#CBA35C] rounded-lg px-4 py-3 transition-colors duration-300"
                        v-model="form.first_name"
                        required
                        autofocus
                        autocomplete="given-name"
                        placeholder="First"
                        @input="validateName('first_name')"
                    />
                    <InputError class="mt-2" :message="form.errors.first_name" />
                </div>
                <div>
                    <InputLabel for="last_name" value="Last Name" class="text-xs uppercase tracking-wider text-ink-light dark:text-gray-400 mb-2" />
                    <TextInput
                        id="last_name"
                        type="text"
                        class="mt-1 block w-full bg-white/50 dark:bg-black/20 border-divider dark:border-white/10 focus:border-[#CBA35C] focus:ring-[#CBA35C] rounded-lg px-4 py-3 transition-colors duration-300"
                        v-model="form.last_name"
                        required
                        autocomplete="family-name"
                        placeholder="Last"
                        @input="validateName('last_name')"
                    />
                    <InputError class="mt-2" :message="form.errors.last_name" />
                </div>
            </div>

            <div>
                <InputLabel for="email" value="Email" class="text-xs uppercase tracking-wider text-ink-light dark:text-gray-400 mb-2" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full bg-white/50 dark:bg-black/20 border-divider dark:border-white/10 focus:border-[#CBA35C] focus:ring-[#CBA35C] rounded-lg px-4 py-3 transition-colors duration-300"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    placeholder="name@example.com"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="address" value="Address" class="text-xs uppercase tracking-wider text-ink-light dark:text-gray-400 mb-2" />
                <TextInput
                    id="address"
                    type="text"
                    class="mt-1 block w-full bg-white/50 dark:bg-black/20 border-divider dark:border-white/10 focus:border-[#CBA35C] focus:ring-[#CBA35C] rounded-lg px-4 py-3 transition-colors duration-300"
                    v-model="form.address"
                    required
                    autocomplete="street-address"
                    placeholder="Full Address"
                />
                <InputError class="mt-2" :message="form.errors.address" />
            </div>

            <div>
                <InputLabel for="contact_number" value="Phone Number" class="text-xs uppercase tracking-wider text-ink-light dark:text-gray-400 mb-2" />
                <VueTelInput
                    id="contact_number"
                    v-model="form.contact_number"
                    class="mt-1 block w-full bg-white/50 dark:bg-black/20 border-divider dark:border-white/10 focus-within:border-[#CBA35C] focus-within:ring-[#CBA35C] rounded-lg overflow-hidden transition-colors duration-300 [&_.vti__dropdown]:bg-transparent [&_.vti__dropdown]:hover:bg-black/5 [&_.vti__input]:bg-transparent [&_.vti__input]:placeholder-gray-400"
                    mode="international"
                    :preferredCountries="['PH', 'US']"
                    :validCharactersOnly="true"
                    :inputOptions="{
                        showDialCode: true,
                        placeholder: 'Enter phone number'
                    }"
                ></VueTelInput>
                <InputError class="mt-2" :message="form.errors.contact_number" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <InputLabel for="password" value="Password" class="text-xs uppercase tracking-wider text-ink-light dark:text-gray-400 mb-2" />
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full bg-white/50 dark:bg-black/20 border-divider dark:border-white/10 focus:border-[#CBA35C] focus:ring-[#CBA35C] rounded-lg px-4 py-3 transition-colors duration-300"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div>
                    <InputLabel
                        for="password_confirmation"
                        value="Confirm"
                        class="text-xs uppercase tracking-wider text-ink-light dark:text-gray-400 mb-2"
                    />
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full bg-white/50 dark:bg-black/20 border-divider dark:border-white/10 focus:border-[#CBA35C] focus:ring-[#CBA35C] rounded-lg px-4 py-3 transition-colors duration-300"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                    />
                    <InputError
                        class="mt-2"
                        :message="form.errors.password_confirmation"
                    />
                </div>
            </div>

            <div class="pt-4 flex flex-col items-center justify-end gap-5">
                <PrimaryButton
                    class="w-full justify-center py-4 text-base font-serif bg-[#CBA35C] hover:bg-[#B89350] active:bg-[#A68340] focus:ring-[#CBA35C] shadow-lg shadow-[#CBA35C]/20 transition-all duration-300 transform hover:-translate-y-0.5"
                    :class="{ 'opacity-75 cursor-not-allowed': form.processing }"
                    :disabled="form.processing"
                >
                    Create Account
                </PrimaryButton>
                
                <div class="relative w-full text-center">
                     <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-divider dark:border-white/10"></div>
                    </div>
                    <span class="relative bg-transparent px-3 text-xs text-ink-light/70 uppercase tracking-widest bg-white/50 dark:bg-gray-900/50 backdrop-blur-sm">or</span>
                </div>
                
                <button
                    type="button"
                    @click="$emit('switchToLogin')"
                    class="text-[#CBA35C] font-serif italic text-lg hover:text-[#B89350] transition-colors duration-200 hover:tracking-wide"
                >
                    Already registered?
                </button>
            </div>
        </form>
    </div>
</template>
