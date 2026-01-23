<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Swal from 'sweetalert2';

const props = defineProps({
    requests: Array,
});

const selectedRequest = ref(null);
const showModal = ref(false);
const modalType = ref(''); // 'approve' or 'reject'

const form = useForm({
    admin_notes: '',
});

const openModal = (request, type) => {
    selectedRequest.value = request;
    modalType.value = type;
    form.admin_notes = '';
    showModal.value = true;
};

const processRequest = () => {
    const url = modalType.value === 'approve' 
        ? route('admin.withdrawals.approve', selectedRequest.value.id)
        : route('admin.withdrawals.reject', selectedRequest.value.id);

    form.post(url, {
        onSuccess: () => {
            showModal.value = false;
            Swal.fire({
                title: 'Success',
                text: `Request ${modalType.value}d successfully.`,
                icon: 'success',
                confirmButtonColor: '#4F46E5',
            });
        },
    });
};

const getStatusBadge = (status) => {
    switch (status) {
        case 'approved': return 'bg-green-100 text-green-800';
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        case 'rejected': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};
</script>

<template>
    <Head title="Payout Requests" />

    <AdminLayout>
        <template #header>
            Payout Requests
        </template>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Artist</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-if="requests.length === 0">
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 italic">No payout requests found.</td>
                        </tr>
                        <tr v-for="request in requests" :key="request.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full object-cover" :src="request.user.avatar" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ request.user.first_name }} {{ request.user.last_name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ request.user.email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900 dark:text-white">₱{{ request.amount }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-bold rounded-full uppercase tracking-wider mb-1" :class="{'bg-blue-100 text-blue-800': request.payout_method === 'gcash', 'bg-indigo-100 text-indigo-800': request.payout_method === 'bank_transfer'}">
                                    {{ request.payout_method }}
                                </span>
                                <div class="mt-1 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded p-2 text-xs font-mono select-all text-gray-700 dark:text-gray-300">
                                    {{ request.payout_details }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="getStatusBadge(request.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full uppercase tracking-wider">
                                    {{ request.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ new Date(request.created_at).toLocaleDateString() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div v-if="request.status === 'pending'" class="flex justify-end gap-2">
                                    <button @click="openModal(request, 'approve')" class="text-green-600 hover:text-green-900 dark:hover:text-green-400 font-bold px-3 py-1 border border-green-600 rounded-md hover:bg-green-50 transition">Approve</button>
                                    <button @click="openModal(request, 'reject')" class="text-red-600 hover:text-red-900 dark:hover:text-red-400 font-bold px-3 py-1 border border-red-600 rounded-md hover:bg-red-50 transition">Reject</button>
                                </div>
                                <div v-else class="text-xs text-gray-400 italic">Processed</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div @click="showModal = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border dark:border-gray-700">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div :class="modalType === 'approve' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'" class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                                <svg v-if="modalType === 'approve'" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                    {{ modalType === 'approve' ? 'Approve Withdrawal' : 'Reject Withdrawal' }}
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Are you sure you want to {{ modalType }} this payout of <span class="font-bold text-gray-900 dark:text-white">₱{{ selectedRequest?.amount }}</span> for {{ selectedRequest?.user?.first_name }}?
                                    </p>
                                </div>
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Admin Notes (Optional)</label>
                                    <textarea v-model="form.admin_notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white text-sm" placeholder="e.g. Transaction completed via GCash."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button @click="processRequest" type="button" :class="modalType === 'approve' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700'" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:w-auto sm:text-sm transition">
                            Confirm {{ modalType === 'approve' ? 'Approval' : 'Rejection' }}
                        </button>
                        <button @click="showModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm transition">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
