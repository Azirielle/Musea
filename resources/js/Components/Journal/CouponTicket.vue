<script setup>
import { ref } from 'vue';
import { defineProps } from 'vue';

const props = defineProps({
    code: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        required: true,
    }
});

const copied = ref(false);

const copyToClipboard = async () => {
    try {
        await navigator.clipboard.writeText(props.code);
        copied.value = true;
        setTimeout(() => {
            copied.value = false;
        }, 2000);
    } catch (err) {
        console.error('Failed to copy: ', err);
    }
};
</script>

<template>
    <div class="relative w-full max-w-3xl mx-auto my-8 font-sans filter drop-shadow-sm">
        <!-- Main Ticket Body -->
        <div class="relative flex flex-col md:flex-row items-center justify-between p-6 bg-indigo-50 border-2 border-dashed border-indigo-300 rounded-lg overflow-hidden">
            
            <!-- Cutout Circles (Pseudo-elements simulation) -->
            <!-- Left Cutout -->
            <div class="absolute top-1/2 -left-3 w-6 h-6 bg-white rounded-full transform -translate-y-1/2 border-r-2 border-dashed border-indigo-300 box-content z-10" style="border-right-color: transparent;"></div>
            <!-- Right Cutout -->
            <div class="absolute top-1/2 -right-3 w-6 h-6 bg-white rounded-full transform -translate-y-1/2 border-l-2 border-dashed border-indigo-300 box-content z-10" style="border-left-color: transparent;"></div>

            <!-- Left: Label -->
            <div class="relative z-10 flex-shrink-0 text-center md:text-left mb-4 md:mb-0 md:pr-6 md:border-r border-indigo-200 border-dashed">
                <span class="inline-block px-2 py-1 text-xs font-bold tracking-wider text-indigo-600 uppercase bg-indigo-100 rounded-full">
                    Exclusive Reader Offer
                </span>
            </div>

            <!-- Center: Description -->
            <div class="relative z-10 flex-grow text-center md:text-left px-4 mb-4 md:mb-0">
                <p class="text-lg font-medium text-gray-800 leading-snug">
                    {{ description }}
                </p>
            </div>

            <!-- Right: Code & Action -->
            <div class="relative z-10 flex-shrink-0 flex flex-col items-center justify-center space-y-2">
                <div class="text-2xl font-mono font-bold text-indigo-700 tracking-widest select-all">
                    {{ code }}
                </div>
                <button 
                    @click="copyToClipboard" 
                    class="inline-flex items-center px-4 py-1.5 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500 rounded-md"
                    :class="copied ? 'text-green-700 bg-green-100 hover:bg-green-200' : 'text-indigo-700 bg-indigo-100 hover:bg-indigo-200'"
                >
                    <span v-if="!copied" class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Copy Code
                    </span>
                    <span v-else class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Copied!
                    </span>
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Optional: Adding a subtle perforated line effect if desired generally, but manual borders usually work better for control */
</style>
