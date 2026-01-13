<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

import Swal from 'sweetalert2';

defineProps({
    approvals: Array,
});

const approve = (item) => {
    const routeName = item.model === 'artwork' ? 'admin.approvals.approve' : 'admin.verifications.approve';
    
    Swal.fire({
        title: `Approve this ${item.model === 'artwork' ? 'artwork' : 'application'}?`,
        text: item.model === 'artwork' ? "It will be visible in the shop." : "User will become a Verified Artist.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#166534',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, approve it!'
    }).then((result) => {
        if (result.isConfirmed) {
            router.post(route(routeName, item.id), {}, {
                onSuccess: () => {
                    Swal.fire('Approved!', 'The item has been approved.', 'success')
                }
            });
        }
    });
};

const reject = (item) => {
    const routeName = item.model === 'artwork' ? 'admin.approvals.reject' : 'admin.verifications.reject';

    Swal.fire({
        title: `Reject this ${item.model === 'artwork' ? 'artwork' : 'application'}?`,
        text: "It will be marked as rejected.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, reject it!'
    }).then((result) => {
        if (result.isConfirmed) {
            router.post(route(routeName, item.id), {}, {
                onSuccess: () => {
                    Swal.fire('Rejected!', 'The item has been rejected.', 'success')
                }
            });
        }
    });
};
</script>

<template>
    <Head title="Artwork Approvals" />

    <AdminLayout>
        <template #header>Pending Submissions</template>

        <div class="mb-6">
            <Link :href="route('admin.dashboard')" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Dashboard
            </Link>
        </div>

        <div v-if="approvals.length === 0" class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 text-center text-gray-500">
            No pending submissions.
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="item in approvals" :key="item.type + item.id" class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg relative">
                <!-- Type Badge -->
                <div class="absolute top-2 right-2 px-2 py-1 rounded text-xs font-bold uppercase tracking-wider"
                    :class="item.model === 'artwork' ? 'bg-indigo-100 text-indigo-800' : 'bg-purple-100 text-purple-800'">
                    {{ item.type }}
                </div>

                <img :src="item.image" alt="Thumbnail" class="w-full h-48 object-cover bg-gray-100">
                
                <div class="p-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate" :title="item.title">{{ item.title }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-300 truncate">{{ item.subtitle }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ new Date(item.created_at).toLocaleDateString() }}</p>

                    <div class="mt-4 flex space-x-2">
                        <button @click="approve(item)" class="flex-1 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors">
                            Approve
                        </button>
                        <button @click="reject(item)" class="flex-1 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition-colors">
                            Reject
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
