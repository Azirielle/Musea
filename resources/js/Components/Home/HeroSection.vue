<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    featured: {
        type: Object,
        default: null // { title, artist, description, image, link }
    }
});
</script>

<template>
    <section class="relative w-full h-[90vh] min-h-[700px] flex items-center justify-start overflow-hidden bg-ink">
        <!-- Background Image with sophisticated overlay -->
        <div class="absolute inset-0 z-0 scale-105 animate-[slow-zoom_20s_ease-in-out_infinite_alternate]">
            <img 
                :src="featured?.image || '/images/hero-bg-2.jpg'" 
                class="w-full h-full object-cover object-center opacity-70"
                alt="Hero Background"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-ink via-ink/40 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-ink/60 via-transparent to-transparent"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 px-6 md:px-20 lg:px-32 max-w-6xl w-full">
            <div class="max-w-3xl space-y-8">
                <div class="inline-flex items-center gap-3 px-4 py-1.5 rounded-full bg-white/5 border border-white/10 backdrop-blur-md">
                    <span class="w-1.5 h-1.5 rounded-full bg-accent animate-pulse"></span>
                    <span class="text-[10px] font-bold tracking-[0.25em] text-white/80 uppercase">
                        {{ featured ? 'Featured Masterpiece' : 'Museum Edition' }}
                    </span>
                </div>
                
                <h1 class="text-6xl md:text-8xl font-serif font-bold text-white tracking-tight leading-[0.95] drop-shadow-2xl">
                    {{ featured?.title || 'Where Art Finds Its Voice' }}
                </h1>
                
                <p class="text-xl md:text-2xl text-white/70 max-w-xl leading-relaxed font-light">
                    {{ featured ? `By ${featured.artist}` : 'A curated heritage of digital and physical masterpieces, redefined for the modern collector.' }}
                </p>
                
                <div class="flex flex-col sm:flex-row gap-6 pt-4">
                    <Link 
                        :href="featured?.link || '/onboarding'" 
                        class="group relative inline-flex items-center justify-center px-10 py-5 bg-white text-ink rounded-full font-bold text-sm tracking-widest overflow-hidden transition-all duration-500 hover:scale-105 active:scale-95 shadow-2xl shadow-white/10"
                    >
                        <span class="relative z-10 uppercase">{{ featured ? 'View Artwork' : 'Start Collecting' }}</span>
                        <div class="absolute inset-0 bg-accent translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                    </Link>
                    
                    <Link 
                        href="/about" 
                        class="inline-flex items-center justify-center px-10 py-5 border border-white/20 text-white rounded-full font-bold text-sm tracking-widest hover:bg-white hover:text-ink transition-all duration-500 uppercase backdrop-blur-sm"
                    >
                        Our Heritage
                    </Link>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-10 hidden md:block">
            <div class="flex flex-col items-center gap-4 group">
                <span class="text-[10px] font-bold text-white/30 uppercase tracking-[0.3em] transition-colors group-hover:text-white/60">Explore</span>
                <div class="w-px h-16 bg-gradient-to-b from-white/40 to-transparent relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1/2 bg-white animate-[scroll-draw_2s_infinite]"></div>
                </div>
            </div>
        </div>
    </section>
</template>

<style>
@keyframes slow-zoom {
    from { transform: scale(1); }
    to { transform: scale(1.15); }
}

@keyframes scroll-draw {
    0% { transform: translateY(-100%); }
    100% { transform: translateY(200%); }
}
</style>
