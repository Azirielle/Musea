<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import RegisterForm from './RegisterForm.vue';

const switchToLogin = () => {
    router.visit(route('login'));
}
</script>

<template>
    <GuestLayout>
        <Head title="Register" />
        <RegisterForm @switchToLogin="switchToLogin" />
    </GuestLayout>
</template>
