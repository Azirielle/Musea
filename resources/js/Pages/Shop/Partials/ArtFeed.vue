<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    artworks: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <div class="px-2 md:px-0">
        <!-- Masonry Grid Wrapper -->
        <!-- 'columns-2' creates the Pinterest style layout on mobile -->
        <!-- 'md:columns-3 lg:columns-4' scales it up for desktop -->
        <!-- 'gap-4' space between columns -->
        <div class="columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4">
            
            <div 
                v-for="artwork in artworks" 
                :key="artwork.id" 
                class="break-inside-avoid relative group rounded-xl overflow-hidden bg-white shadow-sm border border-gray-100 transition-transform duration-300 hover:-translate-y-1"
            >
                <Link :href="route('shop.show', artwork.id)" class="block">
                    <!-- Image -->
                    <div class="relative overflow-hidden w-full">
                         <img 
                            :src="artwork.image_url" 
                            :alt="artwork.title" 
                            loading="lazy"
                            class="w-full h-auto object-cover transform md:group-hover:scale-105 transition-transform duration-500"
                        >
                        
                        <!-- Desktop Overlay (Hover Only) -->
                        <div class="hidden md:flex absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 items-center justify-center">
                            <span class="bg-white text-black px-4 py-2 rounded-full font-bold text-sm transform scale-90 group-hover:scale-100 transition-transform duration-300">View Details</span>
                        </div>
                    </div>

                    <!-- Mobile Info Layout (Always Visible) -->
                    <!-- Compact textual info below image for clean reading on small screens -->
                    <div class="p-3 bg-white">
                        <div class="flex flex-col gap-0.5">
                            <h3 class="font-bold text-sm text-gray-900 leading-tight truncate">{{ artwork.title }}</h3>
                            <p class="text-[11px] text-gray-500 truncate">{{ artwork.artist.first_name }} {{ artwork.artist.last_name }}</p>
                        </div>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="font-bold text-black text-sm">₱{{ artwork.price }}</span>
                            <!-- Stock Indicator Dot -->
                            <div v-if="artwork.stock < 1" class="px-1.5 py-0.5 bg-gray-100 rounded text-[9px] text-gray-500 font-bold uppercase">Sold</div>
                        </div>
                    </div>
                </Link>
            </div>

        </div>
        
        <!-- Empty State -->
        <div v-if="artworks.length === 0" class="text-center py-20">
            <p class="text-gray-500 italic">No artworks found.</p>
        </div>
    </div>
</template>

<style scoped>
/* Ensure images behave well in columns */
img {
    display: block;
}
</style>
