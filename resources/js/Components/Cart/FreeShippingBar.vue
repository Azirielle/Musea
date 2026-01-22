<script setup>
import { computed } from 'vue';

const props = defineProps({
    subtotal: {
        type: Number,
        required: true,
    },
    threshold: {
        type: Number,
        required: true,
        default: 5000 // Fallback default
    }
});

const percentage = computed(() => {
    if (props.threshold <= 0) return 100;
    const pct = (props.subtotal / props.threshold) * 100;
    return Math.min(100, Math.max(0, pct));
});

const amountNeeded = computed(() => {
    return Math.max(0, props.threshold - props.subtotal);
});

const isFreeShipping = computed(() => {
    return props.subtotal >= props.threshold;
});

// Helper to format currency
const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(value);
};
</script>

<template>
    <div class="w-full max-w-2xl mx-auto my-4 p-4 bg-white rounded-lg shadow-sm border border-gray-100">
        <!-- Text Status -->
        <div class="mb-2 text-center">
            <template v-if="!isFreeShipping">
                <p class="text-sm font-medium text-gray-700">
                    Add <span class="font-bold text-indigo-600">{{ formatCurrency(amountNeeded) }}</span> more for <span class="uppercase tracking-wider font-bold text-gray-900">Free Shipping!</span>
                </p>
            </template>
            <template v-else>
                <p class="text-sm font-bold text-green-600 flex items-center justify-center gap-2">
                    <span>🎉</span>
                    You've unlocked Free Shipping!
                </p>
            </template>
        </div>

        <!-- Progress Bar Container -->
        <div class="relative h-3 w-full bg-gray-200 rounded-full overflow-hidden">
            <!-- Animated Progress Fill -->
            <div 
                class="absolute top-0 left-0 h-full rounded-full transition-all duration-500 ease-out"
                :class="isFreeShipping ? 'bg-green-500' : 'bg-indigo-500'"
                :style="{ width: `${percentage}%` }"
            >
                <!-- Optional: Shimmer effect for motivation -->
                <div v-if="!isFreeShipping" class="absolute top-0 left-0 w-full h-full bg-white opacity-20 animate-pulse"></div>
            </div>
        </div>
    </div>
</template>
