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
    <section class="py-20 px-6">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-4xl font-black mb-16 text-center text-charcoal">Meet the Community</h2>
            
            <!-- Artists Horizontal Scroll -->
            <div class="relative mb-24 group">
                <div class="flex gap-10 overflow-x-auto pb-12 snap-x scrollbar-hide">
                    <div 
                        v-for="artist in artists" 
                        :key="artist.id" 
                        class="relative flex-shrink-0 flex flex-col items-center gap-6 snap-center w-40 group/artist"
                        @mouseenter="hoveredArtist = artist.id"
                        @mouseleave="hoveredArtist = null"
                    >
                        <!-- Tooltip with Glass effect -->
                        <div 
                            v-if="hoveredArtist === artist.id"
                            class="absolute -top-36 left-1/2 -translate-x-1/2 glass p-4 rounded-2xl shadow-2xl w-48 z-20 pointer-events-none transition-all duration-300 animate-fade-in-up border-white/50"
                        >
                            <img :src="artist.bestSeller.image" class="w-full h-28 object-cover rounded-xl mb-3 shadow-sm" alt="">
                            <h5 class="text-xs font-black text-center truncate text-charcoal">{{ artist.bestSeller.title }}</h5>
                            <p class="text-[10px] text-center text-grape font-bold tracking-widest uppercase mt-1">Best Seller</p>
                        </div>

                        <div class="w-32 h-32 rounded-full glass border-4 border-white/60 shadow-xl overflow-hidden transition-all duration-500 group-hover/artist:scale-110 group-hover/artist:border-grape/40 cursor-pointer p-1">
                            <div class="w-full h-full rounded-full overflow-hidden">
                                <img 
                                    :src="artist.image || `https://ui-avatars.com/api/?name=${artist.name}&background=random`" 
                                    alt="" 
                                    loading="lazy"
                                    class="w-full h-full object-cover"
                                >
                            </div>
                        </div>
                        <div class="text-center">
                            <h4 class="font-black text-lg text-charcoal group-hover/artist:text-grape transition-colors">{{ artist.name }}</h4>
                            <p class="text-xs font-bold text-charcoal/40 uppercase tracking-widest mt-1">{{ artist.location }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Staff Picks -->
            <div class="glass rounded-[3rem] p-10 md:p-16 border-white/40 shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-grape/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                
                <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6 relative z-10">
                    <div>
                        <span class="text-grape font-black tracking-[0.3em] uppercase text-xs mb-3 block">Curator's Choice</span>
                        <h3 class="text-5xl font-black text-charcoal">Staff Picks</h3>
                    </div>
                    <Link href="/shop" class="text-sm font-black text-charcoal border-b-2 border-grape pb-1 hover:text-grape transition-all hover:scale-105">View All Collections</Link>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 relative z-10">
                    <div v-for="pick in staffPicks" :key="pick.id" class="group cursor-pointer">
                        <div class="aspect-[4/3] glass p-3 rounded-[2.5rem] border-white/30 overflow-hidden mb-6 relative hover:shadow-grape/10 transition-all duration-500">
                            <div class="w-full h-full rounded-[2rem] overflow-hidden relative">
                                <img 
                                    :src="pick.image || 'https://placehold.co/800x600/f3f4f6/1a1a1a?text=Musea+Artwork'" 
                                    @error="$event.target.src = 'https://placehold.co/800x600/f3f4f6/1a1a1a?text=Musea+Artwork'" 
                                    loading="lazy"
                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                                    :class="{'grayscale opacity-60': pick.stock <= 0}"
                                >
                                <div v-if="pick.stock <= 0" class="absolute inset-0 flex items-center justify-center bg-charcoal/20 backdrop-blur-[2px]">
                                    <span class="glass px-8 py-3 rounded-full text-xs font-black tracking-[0.2em] text-charcoal shadow-2xl border-white/50 uppercase">
                                        Sold Out
                                    </span>
                                </div>
                                <div class="absolute top-6 left-6 glass px-5 py-2 rounded-full text-[10px] font-black tracking-widest text-grape shadow-lg border-white/40 uppercase">
                                    Staff Pick
                                </div>
                            </div>
                        </div>
                        <div class="px-4">
                            <h4 class="font-black text-2xl text-charcoal group-hover:text-grape transition-colors">{{ pick.title }}</h4>
                            <p class="text-charcoal/50 font-bold text-sm mt-2 uppercase tracking-widest">by {{ pick.artist }} — ₱{{ pick.price }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
