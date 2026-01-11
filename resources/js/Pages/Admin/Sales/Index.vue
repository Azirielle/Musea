<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    orders: Object,
});

const showModal = ref(false);
const selectedOrder = ref(null);
const form = useForm({
    courier: '',
    tracking_number: '',
});

const openShipModal = (order) => {
    selectedOrder.value = order;
    form.courier = '';
    // Auto-generate tracking number: TRK-{timestamp}-{random}
    const timestamp = Date.now().toString().slice(-6);
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    form.tracking_number = `TRK-${timestamp}-${random}`;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedOrder.value = null;
    form.reset();
};

const submitShip = () => {
    if (!selectedOrder.value) return;
    
    form.post(route('admin.sales.ship', selectedOrder.value.id), {
        onSuccess: () => closeModal(),
        onError: () => {
            // Keep modal open on error
        }
    });
};
</script>

<template>
    <Head title="Transaction History" />

    <AdminLayout>
        <template #header>Transaction History</template>

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
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Order #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Buyer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Shipping Info</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="order in orders.data" :key="order.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ order.order_number }}
                                    <div class="text-xs text-gray-500">{{ order.created_at }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ order.buyer }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300 max-w-xs truncate">
                                    <div class="font-medium">Address:</div>
                                    <div class="truncate" :title="order.shipping_address">{{ order.shipping_address || 'N/A' }}</div>
                                    <div v-if="order.tracking_number" class="mt-1 text-xs text-indigo-500">
                                        {{ order.courier }}: {{ order.tracking_number }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    <div>P{{ order.total_amount }}</div>
                                    <div class="text-xs text-green-600 dark:text-green-400">Comm: +P{{ order.commission }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm capitalize">
                                    <span :class="{
                                        'px-2 py-1 rounded-full text-xs font-bold': true,
                                        'bg-yellow-100 text-yellow-800': order.status === 'pending',
                                        'bg-blue-100 text-blue-800': order.status === 'shipped',
                                        'bg-green-100 text-green-800': order.status === 'completed' || order.status === 'paid',
                                        'bg-red-100 text-red-800': order.status === 'cancelled'
                                    }">
                                        {{ order.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    <button 
                                        v-if="order.status === 'pending' || order.status === 'paid'"
                                        @click="openShipModal(order)"
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-bold"
                                    >
                                        Mark Shipped
                                    </button>
                                    <span v-else class="text-gray-400">--</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    <Pagination :links="orders.links" />
                </div>
            </div>
        </div>

        <!-- Ship Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Ship Order #{{ selectedOrder?.order_number }}</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Courier Service</label>
                    <input v-model="form.courier" type="text" placeholder="e.g. LBC, J&T, Grab" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <div v-if="form.errors.courier" class="text-red-500 text-xs mt-1">{{ form.errors.courier }}</div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tracking Number</label>
                    <input v-model="form.tracking_number" type="text" placeholder="e.g. 1234567890" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <div v-if="form.errors.tracking_number" class="text-red-500 text-xs mt-1">{{ form.errors.tracking_number }}</div>
                </div>

                <div class="flex justify-end gap-3">
                    <button @click="closeModal" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                    <button @click="submitShip" :disabled="form.processing" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50">
                        Confirm Shipment
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
