<script setup>
import { ref } from 'vue';

const emit = defineEmits(['close', 'success']);

const selectedBank = ref('');
const accountNumber = ref('');
const isLoading = ref(false);

const banks = [
    'BDO Unibank',
    'Bank of the Philippine Islands (BPI)',
    'Metropolitan Bank & Trust Company (Metrobank)',
    'Land Bank of the Philippines',
    'Union Bank of the Philippines',
];

const confirm = () => {
    isLoading.value = true;
    setTimeout(() => {
        isLoading.value = false;
        emit('success');
    }, 2000);
};
</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden relative animate-fade-in-up">
            <div class="p-6">
                <h3 class="text-xl font-serif font-bold mb-6 text-gray-900">Bank Transfer</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Bank</label>
                        <select v-model="selectedBank" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-accent focus:ring focus:ring-accent/20 transition">
                            <option value="" disabled>Choose a bank...</option>
                            <option v-for="bank in banks" :key="bank" :value="bank">{{ bank }}</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Account Number</label>
                        <input 
                            v-model="accountNumber"
                            type="text" 
                            placeholder="Type your account number" 
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-accent focus:ring focus:ring-accent/20 transition"
                        />
                    </div>
                    
                    <div class="bg-blue-50 p-4 rounded-lg flex gap-3 items-start mt-4">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm text-blue-800">
                            Please ensure your account has sufficient balance. The transfer will be processed immediately.
                        </p>
                    </div>

                    <div class="flex gap-3 mt-8">
                        <button @click="$emit('close')" class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button 
                            @click="confirm" 
                            class="flex-1 px-4 py-3 bg-accent text-white font-bold rounded-lg hover:bg-black transition disabled:opacity-50"
                            :disabled="!selectedBank || !accountNumber || isLoading"
                        >
                             <span v-if="isLoading">Processing...</span>
                             <span v-else>Confirm Payment</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-up {
    animation: fade-in-up 0.3s ease-out forwards;
}
</style>
