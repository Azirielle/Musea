<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

import Swal from 'sweetalert2';

defineProps({
    pendingArtworks: Array,
});

const approve = (id) => {
    Swal.fire({
        title: 'Approve this artwork?',
        text: "It will be visible in the shop immediately.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#166534', // green-700
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, approve it!'
    }).then((result) => {
        if (result.isConfirmed) {
            router.post(route('admin.approvals.approve', id), {}, {
                onSuccess: () => {
                    Swal.fire(
                        'Approved!',
                        'The artwork has been approved.',
                        'success'
                    )
                }
            });
        }
    });
};

const reject = (id) => {
    Swal.fire({
        title: 'Reject this artwork?',
        text: "It will be marked as rejected.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626', // red-600
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, reject it!'
    }).then((result) => {
        if (result.isConfirmed) {
            router.post(route('admin.approvals.reject', id), {}, {
                 onSuccess: () => {
                    Swal.fire(
                        'Rejected!',
                        'The artwork has been rejected.',
                        'success'
                    )
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

        <div v-if="pendingArtworks.length === 0" class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 text-center text-gray-500">
            No pending submissions.
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="artwork in pendingArtworks" :key="artwork.id" class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <img :src="artwork.image_url" alt="Artwork" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ artwork.title }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-300">by {{ artwork.artist.first_name }} {{ artwork.artist.last_name }}</p>
                    <p class="mt-2 text-gray-700 dark:text-gray-300">{{ artwork.description }}</p>
                    <p class="mt-2 font-bold text-indigo-600 dark:text-indigo-400">₱{{ artwork.price }}</p>

                    <div class="mt-4 flex space-x-2">
                        <button @click="approve(artwork.id)" class="flex-1 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            Approve
                        </button>
                        <button @click="reject(artwork.id)" class="flex-1 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                            Reject
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
