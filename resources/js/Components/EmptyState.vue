<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    title: {
        type: String,
        default: 'No masterpieces found yet'
    },
    message: {
        type: String,
        default: 'It looks a bit empty here. Adjust your filters or check back later for new arrivals.'
    },
    actionLabel: String,
    actionUrl: String,
});
</script>

<template>
    <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
        <div class="relative w-32 h-32 mb-6 opacity-90 hover:scale-105 transition-transform duration-500">
             <!-- Cute Empty State Illustration (SVG) -->
            <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full text-gray-200">
                <circle cx="100" cy="100" r="80" fill="#F3F4F6"/>
                <path d="M65 85C65 85 70 85 75 80C80 75 85 85 85 85" stroke="#9CA3AF" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M115 85C115 85 120 85 125 80C130 75 135 85 135 85" stroke="#9CA3AF" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M80 120C80 120 90 130 120 120" stroke="#9CA3AF" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="150" cy="50" r="10" fill="#FFD700" class="animate-bounce" style="animation-duration: 3s;"/>
            </svg>
        </div>
        
        <h3 class="text-2xl font-serif font-bold text-ink mb-3 tracking-tight">{{ title }}</h3>
        <p class="text-gray-500 max-w-md mb-8 leading-relaxed">{{ message }}</p>
        
        <Link 
            v-if="actionLabel && actionUrl" 
            :href="actionUrl" 
            class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-full shadow-lg text-white bg-black hover:bg-gray-800 transition-all duration-300 hover:-translate-y-1"
        >
            {{ actionLabel }}
        </Link>
    </div>
</template>
