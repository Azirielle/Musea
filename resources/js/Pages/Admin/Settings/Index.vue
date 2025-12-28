<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    settings: Object,
});

const form = useForm({
    commission_rate: props.settings.commission_rate || 10,
    site_maintenance: props.settings.site_maintenance === '1' || props.settings.site_maintenance === true,
});

const updateSettings = () => {
    form.post(route('admin.settings.update'), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Settings updated!',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        },
    });
};
</script>

<template>
    <Head title="Platform Settings" />

    <AdminLayout>
        <template #header>Platform Settings</template>

        <div class="mb-6">
            <Link :href="route('admin.dashboard')" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Dashboard
            </Link>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <form @submit.prevent="updateSettings" class="space-y-6 max-w-xl">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Commission Rate (%)</label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">The percentage required from every sale.</p>
                        <input v-model="form.commission_rate" type="number" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
                        <div v-if="form.errors.commission_rate" class="text-red-600 text-sm mt-1">{{ form.errors.commission_rate }}</div>
                    </div>

                    <div class="flex items-center">
                         <input v-model="form.site_maintenance" id="maintenance" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="maintenance" class="ml-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Maintenance Mode
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 ml-6">If enabled, the main site will show a maintenance page (logic to be implemented).</p>

                    <div class="pt-4">
                        <button type="submit" :disabled="form.processing" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
