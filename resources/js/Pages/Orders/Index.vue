<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    orders: Object,
});

const confirmReceipt = (order) => {
    if (confirm('Are you sure you have received this order? This will release funds to the artist.')) {
        router.post(route('orders.received', order.id));
    }
};
</script>

<template>
    <Head title="My Orders" />

    <MainLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">My Orders</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div v-if="orders.data.length === 0" class="text-center py-12 text-gray-500">
                            You haven't placed any orders yet. <Link :href="route('shop.index')" class="text-indigo-600 hover:underline">Go Shopping</Link>
                        </div>
                        
                        <div v-else class="space-y-6">
                            <div v-for="order in orders.data" :key="order.id" class="border dark:border-gray-700 rounded-lg overflow-hidden">
                                <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 flex justify-between items-center">
                                    <div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Order Placed</div>
                                        <div class="font-medium">{{ order.created_at }}</div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Total</div>
                                        <div class="font-medium">P{{ order.total_amount }}</div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Order #</div>
                                        <div class="font-medium">{{ order.order_number }}</div>
                                    </div>
                                </div>
                                
                                <div class="p-6">
                                    <div class="flex flex-col md:flex-row justify-between gap-6">
                                        <div class="flex-1">
                                            <div class="mb-4">
                                                <h4 class="font-bold text-lg mb-2">Items</h4>
                                                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                                    <li v-for="item in order.items" :key="item.id" class="py-2 flex justify-between">
                                                        <span>{{ item.title }} <span class="text-gray-500">x{{ item.quantity }}</span></span>
                                                        <span>P{{ item.price }}</span>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="mt-4 border-t pt-4 dark:border-gray-700">
                                                 <h4 class="font-bold mb-2">Status: 
                                                    <span :class="{
                                                        'text-yellow-600': order.status === 'pending',
                                                        'text-blue-600': order.status === 'shipped',
                                                        'text-green-600': order.status === 'completed',
                                                        'text-red-600': order.status === 'cancelled',
                                                    }" class="capitalize">{{ order.status }}</span>
                                                 </h4>
                                                 
                                                 <div v-if="order.tracking_number" class="mt-2 p-3 bg-indigo-50 dark:bg-indigo-900 rounded-md">
                                                    <div class="text-sm font-semibold text-indigo-900 dark:text-indigo-200">Tracking Info</div>
                                                    <div class="text-sm text-indigo-800 dark:text-indigo-300">Courier: {{ order.courier }}</div>
                                                    <div class="text-sm text-indigo-800 dark:text-indigo-300">Tracking #: {{ order.tracking_number }}</div>
                                                    <div class="text-xs text-indigo-600 dark:text-indigo-400 mt-1">Shipped on: {{ order.shipped_at }}</div>
                                                 </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-center md:border-l md:pl-6 dark:border-gray-700">
                                            <button 
                                                v-if="order.status === 'shipped'"
                                                @click="confirmReceipt(order)" 
                                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-bold shadow-md transition-colors"
                                            >
                                                Confirm Receipt
                                            </button>
                                            <div v-else-if="order.status === 'completed'" class="text-green-600 font-bold flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                                Received
                                            </div>
                                            <div v-else-if="order.status === 'pending'" class="text-gray-500 italic">
                                                Awaiting Shipment
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                         <!-- Pagination would go here if needed, passing links prop -->
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
