<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue'; 
import Swal from 'sweetalert2';

defineProps({
    applications: Object,
});

const form = useForm({});

const approve = (user) => {
    Swal.fire({
        title: 'Approve Application?',
        text: `Are you sure you want to approve ${user.first_name} as a ${user.requested_role}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#10B981', // Green
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, approve!',
        background: '#1F2937', // Dark bg
        color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
             form.post(route('admin.verifications.approve', user.id), {
                 onSuccess: () => Swal.fire({
                     title: 'Approved!',
                     text: 'User has been verified.',
                     icon: 'success',
                     background: '#1F2937',
                     color: '#fff',
                     confirmButtonColor: '#10B981'
                 })
             });
        }
    });
};

const reject = (user) => {
    Swal.fire({
        title: 'Reject Application?',
        text: `Are you sure you want to reject ${user.first_name}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444', // Red
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, reject',
        background: '#1F2937',
        color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            form.post(route('admin.verifications.reject', user.id), {
                onSuccess: () => Swal.fire({
                    title: 'Rejected',
                    text: 'Application has been rejected.',
                    icon: 'success',
                    background: '#1F2937',
                    color: '#fff',
                    confirmButtonColor: '#10B981'
                })
            });
        }
    });
};
</script>

<template>
    <Head title="Verification Applications" />

    <AdminLayout>
        <template #header>User Verification Applications</template>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role Requested</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Bio / Details</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Portfolio</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-if="applications.data.length === 0">
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No pending applications.
                                </td>
                            </tr>
                            <tr v-for="app in applications.data" :key="app.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full object-cover" :src="app.avatar" alt="" />
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ app.first_name }} {{ app.last_name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ app.email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 uppercase">
                                        {{ app.requested_role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                        {{ app.bio || 'No bio provided' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <a v-if="app.portfolio_url" :href="app.portfolio_url" target="_blank" class="text-indigo-600 hover:text-indigo-900 underline">
                                        View Portfolio
                                    </a>
                                    <span v-else class="italic text-gray-400">N/A</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <button 
                                        @click="approve(app)" 
                                        class="text-green-600 hover:text-green-900 font-bold disabled:opacity-50"
                                        :disabled="form.processing"
                                    >
                                        Approve
                                    </button>
                                    <button 
                                        @click="reject(app)" 
                                        class="text-red-600 hover:text-red-900 font-bold disabled:opacity-50"
                                        :disabled="form.processing"
                                    >
                                        Reject
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div v-if="applications.links && applications.links.length > 3" class="mt-6">
                    <!-- Pagination Component Placeholder if not imported or simple links -->
                     <Pagination :links="applications.links" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
