<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, router } from '@inertiajs/vue3'; // Import router
import LoginForm from './LoginForm.vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const switchToRegister = () => {
    router.visit(route('register'));
}
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />
        <LoginForm 
            :canResetPassword="canResetPassword" 
            :status="status" 
            @switchToRegister="switchToRegister"
        />
    </GuestLayout>
</template>
