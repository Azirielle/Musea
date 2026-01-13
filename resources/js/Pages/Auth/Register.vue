<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { VueTelInput } from 'vue-tel-input';
import 'vue-tel-input/vue-tel-input.css';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    address: '',
    contact_number: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-[#1A1A1A]">Join Musea</h2>
            <p class="text-gray-500 text-sm">Create an account to start collecting.</p>
        </div>

        <form @submit.prevent="submit">
            <div class="flex gap-4">
                <div class="w-1/2">
                    <InputLabel for="first_name" value="First Name" />
                    <TextInput
                        id="first_name"
                        type="text"
                        class="mt-1 block w-full border-divider focus:border-accent focus:ring-accent rounded-lg"
                        v-model="form.first_name"
                        required
                        autofocus
                        autocomplete="given-name"
                    />
                    <InputError class="mt-2" :message="form.errors.first_name" />
                </div>
                <div class="w-1/2">
                    <InputLabel for="last_name" value="Last Name" />
                    <TextInput
                        id="last_name"
                        type="text"
                        class="mt-1 block w-full border-divider focus:border-accent focus:ring-accent rounded-lg"
                        v-model="form.last_name"
                        required
                        autocomplete="family-name"
                    />
                    <InputError class="mt-2" :message="form.errors.last_name" />
                </div>
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full border-divider focus:border-accent focus:ring-accent rounded-lg"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="address" value="Address" />
                <TextInput
                    id="address"
                    type="text"
                    class="mt-1 block w-full border-divider focus:border-accent focus:ring-accent rounded-lg"
                    v-model="form.address"
                    required
                    autocomplete="street-address"
                />
                <InputError class="mt-2" :message="form.errors.address" />
            </div>

            <div class="mt-4">
                <InputLabel for="contact_number" value="Phone Number" />
                <VueTelInput
                    id="contact_number"
                    v-model="form.contact_number"
                    class="mt-1 block w-full border-divider focus:border-accent focus:ring-accent rounded-lg"
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

            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full border-divider focus:border-accent focus:ring-accent rounded-lg"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                />
                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full border-gray-300 focus:border-[#CBA35C] focus:ring-[#CBA35C] rounded-lg"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />
                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="mt-6 flex flex-col items-center justify-end gap-4">
                <PrimaryButton
                    class="w-full justify-center py-3 bg-[#CBA35C] hover:bg-[#B89350] focus:ring-[#CBA35C]"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Create Account
                </PrimaryButton>
                
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-[#CBA35C] focus:outline-none focus:ring-2 focus:ring-[#CBA35C] focus:ring-offset-2"
                >
                    Already registered?
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
