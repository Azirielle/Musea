<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />

    <MainLayout>
        <div class="py-12 bg-[#FDFBF7] min-h-screen pt-24">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                 <div class="mb-8 text-center">
                    <h2 class="text-3xl font-bold font-serif text-[#1A1A1A]">Account Settings</h2>
                    <p class="text-gray-500 mt-2">Manage your profile, password, and preferences.</p>
                </div>

                <div class="bg-white p-4 shadow-xl rounded-2xl sm:p-8 border border-gray-100">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div class="bg-white p-4 shadow-xl rounded-2xl sm:p-8 border border-gray-100">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="bg-white p-4 shadow-xl rounded-2xl sm:p-8 border border-gray-100">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </MainLayout>
</template>
