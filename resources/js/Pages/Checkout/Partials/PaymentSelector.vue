<script setup>
import { ref } from 'vue';

const props = defineProps({
    selectedMethod: String,
});

const emit = defineEmits(['select']);

const methods = [
    { id: 'gcash', name: 'GCash', icon: '/images/icons/gcash.png' },
    { id: 'paypal', name: 'PayPal', icon: 'https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg' },
    { id: 'bank', name: 'Bank Transfer', icon: null },
    { id: 'cod', name: 'Cash on Delivery', icon: null },
];

const select = (id) => {
    emit('select', id);
};
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <button
            v-for="method in methods"
            :key="method.id"
            @click="select(method.id)"
            class="flex items-center gap-4 p-4 border rounded-xl transition-all duration-300 relative overflow-hidden group hover:shadow-md"
            :class="selectedMethod === method.id ? 'border-accent bg-accent/5 ring-1 ring-accent' : 'border-gray-200 bg-white hover:border-gray-300'"
        >
            <div class="w-12 h-12 flex items-center justify-center bg-white rounded-lg p-2 border border-gray-100 shadow-sm group-hover:scale-105 transition-transform overflow-hidden">
                <!-- GCash Image -->
                <img v-if="method.id === 'gcash'" :src="method.icon" alt="GCash" class="w-full h-full object-contain" />
                <img v-else-if="method.id === 'paypal'" :src="method.icon" alt="PayPal" class="w-full h-full object-contain" />
                
                <svg v-else-if="method.id === 'bank'" class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                </svg>
                <svg v-else class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="text-left">
                <p class="font-bold text-gray-900">{{ method.name }}</p>
                <p class="text-xs text-gray-500">
                    <span v-if="method.id === 'gcash' || method.id === 'paypal'">Log in to pay</span>
                    <span v-else-if="method.id === 'bank'">Select your bank</span>
                    <span v-else>Pay upon receipt</span>
                </p>
            </div>
            
            <div v-if="selectedMethod === method.id" class="absolute top-4 right-4 text-accent">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            </div>
        </button>
    </div>
</template>
