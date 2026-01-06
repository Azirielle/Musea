<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    artworks: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <div class="columns-1 sm:columns-2 lg:columns-3 gap-8 space-y-8">
        <div v-for="artwork in artworks" :key="artwork.id" class="break-inside-avoid group relative">
             <Link :href="route('shop.show', artwork.id)" class="block bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition duration-300">
                <div class="relative">
                    <img 
                        :src="artwork.image || 'https://placehold.co/800x600/f3f4f6/1a1a1a?text=Musea+Artwork'" 
                        :alt="artwork.title" 
                        loading="lazy"
                        decoding="async"
                        class="w-full h-auto object-cover group-hover:scale-[1.02] transition-transform duration-500 will-change-transform"
                        :class="{'grayscale opacity-60': artwork.stock <= 0}"
                        @error="$event.target.src = 'https://placehold.co/800x600/f3f4f6/1a1a1a?text=Musea+Artwork'"
                    >
                    <div v-if="artwork.stock <= 0" class="absolute inset-0 flex items-center justify-center bg-black/5">
                        <span class="bg-white/90 backdrop-blur px-6 py-2 rounded-full text-sm font-black tracking-widest text-[#1A1A1A] shadow-xl border border-zinc-200 uppercase">
                            Sold Out
                        </span>
                    </div>
                    <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
                
                <div class="p-4">
                    <h3 class="font-bold text-lg text-[#1A1A1A] truncate">{{ artwork.title }}</h3>
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-sm text-gray-500">{{ artwork.artist }}</p>
                        <p class="font-bold text-[#1A1A1A] text-sm">₱{{ artwork.price }}</p>
                    </div>
                </div>
            </Link>
        </div>
    </div>
</template>
