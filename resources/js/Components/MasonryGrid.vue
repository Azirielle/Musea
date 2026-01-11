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
    <div class="columns-1 sm:columns-2 lg:columns-3 gap-12 space-y-12">
        <div v-for="artwork in artworks" :key="artwork.id" class="break-inside-avoid group relative">
             <Link :href="route('shop.show', artwork.id)" class="block transition-all duration-700">
                <!-- Image Container -->
                <div class="relative overflow-hidden rounded-[2rem] bg-paper shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-700 group-hover:shadow-[0_20px_60px_rgb(0,0,0,0.1)] group-hover:-translate-y-2">
                    <img 
                        :src="artwork.image || 'https://placehold.co/800x600/f3f4f6/1a1a1a?text=Musea+Artwork'" 
                        :alt="artwork.title" 
                        loading="lazy"
                        decoding="async"
                        class="w-full h-auto object-cover transition-transform duration-1000 ease-out group-hover:scale-110"
                        :class="{'grayscale opacity-40': artwork.stock <= 0}"
                        @error="$event.target.src = 'https://placehold.co/800x600/f3f4f6/1a1a1a?text=Musea+Artwork'"
                    >
                    
                    <!-- Hover Overlay -->
                    <div class="absolute inset-0 bg-ink/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                    <!-- Sold Out Badge -->
                    <div v-if="artwork.stock <= 0" class="absolute inset-0 flex items-center justify-center">
                        <span class="bg-white/95 backdrop-blur-md px-8 py-3 rounded-full text-[10px] font-black tracking-[0.3em] text-ink shadow-2xl border border-divider uppercase">
                            Sold Out
                        </span>
                    </div>

                    <!-- Quick View Indicator (Subtle) -->
                    <div class="absolute bottom-6 right-6 opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-500 delay-100">
                        <div class="w-10 h-10 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-ink shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14m-7-7 7 7-7 7"/></svg>
                        </div>
                    </div>
                </div>
                
                <!-- Information -->
                <div class="mt-8 px-2 space-y-2">
                    <div class="flex justify-between items-start">
                        <div class="space-y-1">
                            <h3 class="font-serif text-2xl font-bold text-ink leading-tight group-hover:text-accent transition-colors duration-300">{{ artwork.title }}</h3>
                            <p class="text-xs font-bold uppercase tracking-widest text-ink-light/60 group-hover:text-ink transition-colors duration-300">{{ artwork.artist }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-serif text-xl font-bold text-ink">₱{{ artwork.price }}</p>
                            <p class="text-[10px] uppercase font-bold text-ink-light/40">{{ artwork.category }}</p>
                        </div>
                    </div>
                </div>
            </Link>
        </div>
    </div>
</template>
