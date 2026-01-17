<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    artists: {
        type: Array,
        default: () => []
    },
    staffPicks: {
        type: Array,
        default: () => []
    },
});

const hoveredArtist = ref(null);
</script>

<template>
    <section class="py-20 bg-zinc-50 border-y border-zinc-200">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-3xl font-bold mb-12 text-center text-[#1A1A1A]">Meet the Community</h2>
            
            <!-- Artists Horizontal Scroll -->
            <div class="relative mb-20 group">
                <div class="flex gap-8 overflow-x-auto pb-8 snap-x scrollbar-hide justify-center">
                    <div 
                        v-for="artist in artists" 
                        :key="artist.id" 
                        class="relative flex-shrink-0 flex flex-col items-center gap-4 snap-center w-32"
                        @mouseenter="hoveredArtist = artist.id"
                        @mouseleave="hoveredArtist = null"
                    >
                        <!-- Tooltip -->
                        <div 
                            v-if="hoveredArtist === artist.id && artist.bestSeller"
                            class="absolute -top-32 left-1/2 -translate-x-1/2 bg-white p-3 rounded-xl shadow-xl w-40 z-20 pointer-events-none transition-all duration-300 animate-fade-in-up"
                        >
                            <img :src="artist.bestSeller.image" class="w-full h-24 object-cover rounded-lg mb-2" alt="">
                            <h5 class="text-xs font-bold text-center truncate">{{ artist.bestSeller.title }}</h5>
                            <p class="text-[10px] text-center text-gray-500">Best Seller</p>
                            <!-- Triangle -->
                            <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-4 h-4 bg-white rotate-45"></div>
                        </div>

                        <Link :href="route('artists.show', artist.id)" class="w-24 h-24 rounded-full bg-white border-2 border-white shadow-lg overflow-hidden transition transform hover:scale-110 cursor-pointer">
                            <img 
                                :src="artist.image || `https://ui-avatars.com/api/?name=${artist.name}&background=random`" 
                                alt="" 
                                loading="lazy"
                                class="w-full h-full object-cover"
                            >
                        </Link>
                        <div class="text-center">
                            <Link :href="route('artists.show', artist.id)" class="font-bold text-[#1A1A1A] hover:text-accent transition-colors">{{ artist.name }}</Link>
                            <p class="text-xs text-gray-500">{{ artist.location }}</p>
                        </div>
                    </div>
                </div>
                <!-- Fade edges hints -->
                <div class="absolute inset-y-0 right-0 w-24 bg-gradient-to-l from-zinc-50 to-transparent pointer-events-none"></div>
                <div class="absolute inset-y-0 left-0 w-24 bg-gradient-to-r from-zinc-50 to-transparent pointer-events-none"></div>
            </div>

            <!-- Staff Picks -->
            <div class="bg-white rounded-3xl p-8 md:p-12 shadow-sm border border-zinc-100">
                <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-4">
                    <div>
                        <span class="text-accent font-bold tracking-widest uppercase text-xs">Curator's Choice</span>
                        <h3 class="text-3xl font-bold mt-2">Staff Picks</h3>
                    </div>
                    <Link href="/shop" class="text-sm font-bold border-b border-black pb-0.5 hover:opacity-70">View All Collections</Link>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <Link v-for="pick in staffPicks" :key="pick.id" :href="route('shop.show', pick.id)" class="group block">
                        <div class="aspect-[4/3] bg-gray-100 rounded-xl overflow-hidden mb-4 relative">
                            <img 
                                :src="pick.image || 'https://placehold.co/800x600/f3f4f6/1a1a1a?text=Musea+Artwork'" 
                                @error="$event.target.src = 'https://placehold.co/800x600/f3f4f6/1a1a1a?text=Musea+Artwork'" 
                                loading="lazy"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500 will-change-transform"
                                :class="{'grayscale opacity-60': pick.stock <= 0}"
                            >
                            <div v-if="pick.stock <= 0" class="absolute inset-0 flex items-center justify-center bg-black/5">
                                <span class="bg-white/90 backdrop-blur px-6 py-2 rounded-full text-sm font-black tracking-widest text-[#1A1A1A] shadow-xl border border-zinc-200 uppercase">
                                    Sold Out
                                </span>
                            </div>
                             <div class="absolute top-4 left-4 bg-accent text-white px-3 py-1 rounded-full text-xs font-bold shadow-md">
                                Staff Pick
                            </div>
                        </div>
                        <h4 class="font-bold text-lg group-hover:text-accent transition-colors">{{ pick.title }}</h4>
                        <p class="text-gray-500 text-sm">by {{ pick.artist }} — ₱{{ pick.price }}</p>
                    </Link>
                </div>
            </div>
        </div>
    </section>
</template>
