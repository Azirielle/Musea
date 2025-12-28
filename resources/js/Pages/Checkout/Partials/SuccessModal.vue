<script setup>
import { Link } from '@inertiajs/vue3';
const props = defineProps({
    orderId: String,
});
</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-sm text-center animate-bounce-in">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            
            <h2 class="text-2xl font-serif font-bold text-gray-900 mb-2">Order Placed!</h2>
            <p class="text-gray-500 mb-6">Thank you for your purchase. Your order has been securely processed.</p>
            
            <div class="bg-gray-50 rounded-lg p-3 mb-8">
                <p class="text-xs text-gray-500 uppercase tracking-widest font-bold mb-1">Order ID</p>
                <p class="font-mono text-lg font-bold text-gray-900">{{ orderId }}</p>
            </div>
            
            <Link href="/" class="block w-full bg-black text-white py-4 rounded-xl font-bold hover:bg-gray-800 transition">
                Return to Home
            </Link>
        </div>
    </div>
</template>

<style scoped>
@keyframes bounce-in {
    0% { transform: scale(0.9); opacity: 0; }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); opacity: 1; }
}
.animate-bounce-in {
    animation: bounce-in 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
}
</style>
