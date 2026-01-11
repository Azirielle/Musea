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
            class="flex items-center gap-5 p-5 border rounded-2xl transition-all duration-200 relative overflow-hidden group hover:shadow-lg active:scale-[0.98]"
            :class="selectedMethod === method.id 
                ? 'border-accent bg-[#FAFAFA] ring-2 ring-accent shadow-sm' 
                : 'border-gray-200 bg-white hover:border-black/20'"
        >
            <div class="w-14 h-14 flex flex-shrink-0 items-center justify-center bg-white rounded-xl p-2.5 border border-gray-100 shadow-sm group-hover:scale-105 transition-transform overflow-hidden">
                <!-- GCash Image -->
                <img v-if="method.id === 'gcash'" :src="method.icon" alt="GCash" class="w-full h-full object-contain" />
                <img v-else-if="method.id === 'paypal'" :src="method.icon" alt="PayPal" class="w-full h-full object-contain" />
                
                <svg v-else-if="method.id === 'bank'" class="w-7 h-7 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                </svg>
                <svg v-else class="w-7 h-7 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="text-left flex-1 min-w-0">
                <p class="font-bold text-gray-900 text-base mb-0.5 truncate">{{ method.name }}</p>
                <p class="text-xs text-gray-500 font-medium">
                    <span v-if="method.id === 'gcash' || method.id === 'paypal'">Log in to pay</span>
                    <span v-else-if="method.id === 'bank'">Select your bank</span>
                    <span v-else>Pay upon receipt</span>
                </p>
            </div>
            
            <div v-if="selectedMethod === method.id" class="absolute top-4 right-4 text-accent animate-in fade-in zoom-in duration-200">
                <div class="w-5 h-5 bg-accent text-white rounded-full flex items-center justify-center">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
        </button>
    </div>
</template>
