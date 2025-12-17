<script setup>
import { onMounted } from 'vue';

import { watch, onUnmounted } from 'vue';

const props = defineProps({
    show: Boolean,
    message: String,
});

const emit = defineEmits(['close']);

// Prevent scrolling when modal is open
watch(() => props.show, (val) => {
    if (val) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
});

onUnmounted(() => {
    document.body.style.overflow = '';
});

</script>

<template>
    <div v-if="show" class="fixed inset-0 z-[2000] flex items-center justify-center overflow-y-auto overflow-x-hidden p-4 sm:p-6" role="dialog" aria-modal="true">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 transform transition-all" @click="$emit('close')">
            <div class="absolute inset-0 bg-gray-900 opacity-60"></div>
        </div>

        <!-- Modal Panel -->
        <div class="relative w-full max-w-sm transform overflow-hidden rounded-2xl bg-white p-6 shadow-2xl transition-all sm:w-full sm:max-w-md">
            
            <!-- Icon/Header -->
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100 mb-6">
                <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>

            <div class="text-center">
                <h3 class="text-2xl font-bold leading-6 text-gray-900 mb-2">Success!</h3>
                <div class="mt-2">
                    <p class="text-lg text-gray-500">
                        {{ message }}
                    </p>
                </div>
            </div>

            <div class="mt-8 flex justify-center">
                <button
                    type="button"
                    class="inline-flex w-full justify-center rounded-xl bg-gradient-to-br from-[#CBA35C] to-[#B89350] px-6 py-3 text-base font-bold text-white shadow-lg transition-all hover:shadow-xl hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-[#CBA35C] focus:ring-offset-2 sm:w-auto sm:text-sm md:text-base md:px-10"
                    @click="$emit('close')"
                >
                    OK
                </button>
            </div>
        </div>
    </div>
</template>
