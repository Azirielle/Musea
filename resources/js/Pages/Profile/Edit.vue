<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import VerificationForm from './Partials/VerificationForm.vue';
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
                 <div v-if="$page.props.flash.error" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Notice: </strong>
                    <span class="block sm:inline">{{ $page.props.flash.error }}</span>
                </div>

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
                    <VerificationForm class="max-w-xl" />
                </div>

                <div class="bg-white p-4 shadow-xl rounded-2xl sm:p-8 border border-gray-100">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </MainLayout>
</template>
