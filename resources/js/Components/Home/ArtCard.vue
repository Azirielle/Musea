<script setup>
import { computed } from 'vue';

const props = defineProps({
    artwork: {
        type: Object,
        required: true,
        // Expected shape: { title: String, price: Number, artist: String, image_url: String }
    }
});

const formattedPrice = computed(() => {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
        minimumFractionDigits: 0
    }).format(props.artwork.price);
});
</script>

<template>
    <div class="group relative flex flex-col gap-4 cursor-pointer transition-all duration-500 hover:-translate-y-2">
        <!-- Image Container with Rounded Corners & Overflow Hidden -->
        <div class="relative w-full aspect-[4/5] rounded-2xl overflow-hidden shadow-sm transition-shadow duration-500 group-hover:shadow-2xl bg-gray-100">
            <!-- Image with Zoom Effect -->
            <img 
                :src="artwork.image_url" 
                :alt="artwork.title" 
                class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-105"
            />
            
            <!-- Quick View Button Overlay -->
            <!-- Hidden by default, fades in and slides up on hover -->
            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <button 
                    class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500 ease-out
                           px-6 py-3 rounded-full 
                           backdrop-blur-md bg-white/30 text-white 
                           border border-white/40 
                           font-medium tracking-wide 
                           hover:bg-white/40 hover:scale-105
                           shadow-lg"
                >
                    Quick View
                </button>
            </div>
            
            <!-- Optional subtle gradient overlay for better text contrast if we had text on image, 
                 but here it just adds depth during hover -->
            <div class="absolute inset-0 pointer-events-none bg-black/0 group-hover:bg-black/10 transition-colors duration-500"></div>
        </div>

        <!-- Meta Data (Clean Typography) -->
        <div class="flex flex-col px-1">
            <h3 class="text-lg font-bold text-gray-900 leading-tight group-hover:text-indigo-600 transition-colors duration-300">
                {{ artwork.title }}
            </h3>
            <p class="text-sm font-medium text-gray-500 mt-1">{{ artwork.artist }}</p>
            <p class="text-base font-semibold text-gray-900 mt-1">{{ formattedPrice }}</p>
        </div>
    </div>
</template>
