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
    <section class="relative w-full h-[85vh] min-h-[600px] flex items-center justify-center overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img 
                :src="featured?.image || '/images/hero-bg-2.jpg'" 
                class="w-full h-full object-cover object-center"
                alt="Hero Background"
            >
            <div class="absolute inset-0 bg-black/30"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 text-center text-white px-8 py-16 max-w-4xl mx-auto glass border-white/30 rounded-[3rem] shadow-2xl">
            <span class="inline-block px-6 py-2 mb-8 text-[10px] font-black tracking-[0.3em] uppercase glass border-white/40 rounded-full">
                {{ featured ? 'Featured Masterpiece' : 'Welcome to Musea' }}
            </span>
            
            <h1 class="text-6xl md:text-8xl font-black mb-8 tracking-tighter leading-none">
                {{ featured?.title || 'Art That Moves You' }}
            </h1>
            
            <p class="text-lg md:text-2xl text-white/90 mb-12 max-w-2xl mx-auto leading-relaxed font-medium">
                {{ featured ? `By ${featured.artist}` : 'A curated marketplace for artists and collectors.' }}
            </p>
            
            <div class="flex flex-col sm:flex-row gap-6 justify-center">
                <Link 
                    :href="featured?.link || '/onboarding'" 
                    class="px-10 py-4 bg-grape text-white rounded-full font-black uppercase text-xs tracking-widest hover:bg-grape/90 transition-all duration-300 transform hover:scale-110 active:scale-95 shadow-xl shadow-grape/30"
                >
                    {{ featured ? 'View Artwork' : 'Get Started' }}
                </Link>
                <Link 
                    href="/about" 
                    class="px-10 py-4 glass border-white/40 text-white rounded-full font-black uppercase text-xs tracking-widest hover:bg-white/20 transition-all duration-300 transform hover:scale-110 active:scale-95"
                >
                    Our Story
                </Link>
            </div>
        </div>
    </section>
</template>
