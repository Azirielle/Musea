<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  categories: {
    type: Array,
    default: () => []
  }
});

const priceRanges = [
  { label: 'Under ₱5k', value: '0-5000' },
  { label: '₱5k - ₱20k', value: '5000-20000' },
  { label: 'Over ₱20k', value: '20000-plus' },
];
</script>

<template>
  <section class="py-32 bg-canvas">
    <div class="max-w-[1400px] mx-auto px-6">
      <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-8">
        <div class="space-y-4">
            <div class="inline-flex items-center gap-2 group">
                <span class="w-8 h-px bg-accent/30 group-hover:w-12 transition-all duration-500"></span>
                <span class="text-accent text-[10px] font-black tracking-[0.3em] uppercase">Selection</span>
            </div>
            <h2 class="text-5xl md:text-7xl font-serif font-bold text-ink italic leading-tight">Find Your Medium</h2>
        </div>
      </div>
      
      <!-- Shop by Medium -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-32">
        <Link 
          v-for="category in categories" 
          :key="category.name"
          :href="`/shop?category=${category.name}`"
          class="group relative h-[450px] rounded-[2.5rem] overflow-hidden shadow-2xl transition-all duration-700 hover:-translate-y-2 hover:shadow-accent/10"
        >
          <!-- Background Image with Lazy Loading -->
          <img 
            :src="category.image"
            loading="lazy"
            decoding="async"
            alt=""
            class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 will-change-transform"
          />
          <!-- Sophisticated Overlay -->
          <div class="absolute inset-0 bg-gradient-to-t from-ink via-ink/20 to-transparent opacity-80 group-hover:opacity-60 transition-opacity duration-700"></div>
          
          <div class="absolute inset-0 p-10 flex flex-col justify-end text-white z-10 space-y-2 translate-y-4 group-hover:translate-y-0 transition-transform duration-700">
            <p class="text-[10px] font-bold tracking-[0.3em] uppercase opacity-60">{{ category.count }}+ Collection</p>
            <h3 class="text-3xl font-serif font-bold italic">{{ category.name }}</h3>
            
            <div class="pt-4 opacity-0 group-hover:opacity-100 transition-opacity duration-700 delay-100">
                <span class="inline-flex items-center gap-2 text-xs font-bold tracking-widest uppercase bg-white text-ink px-6 py-2.5 rounded-full">
                    Explore
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14m-7-7 7 7-7 7"/></svg>
                </span>
            </div>
          </div>
        </Link>
      </div>

      <!-- Shop by Price -->
      <div class="max-w-5xl mx-auto p-16 bg-paper rounded-[3rem] border border-divider/50 shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-64 h-64 bg-accent/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        
        <div class="relative z-10 text-center space-y-10">
            <div class="space-y-4">
                <h3 class="text-3xl font-serif font-bold text-ink italic">Invest in Excellence</h3>
                <p class="text-ink-light/60 text-sm font-medium tracking-wide">Select your preferred acquisition range</p>
            </div>
            
            <div class="flex flex-wrap justify-center gap-6">
              <Link 
                v-for="range in priceRanges" 
                :key="range.label"
                :href="`/shop?price_range=${range.value}`"
                class="px-10 py-4 rounded-full border border-divider text-ink-light font-bold text-xs tracking-[0.2em] uppercase hover:bg-ink hover:text-white hover:border-ink transition-all duration-500 transform hover:-translate-y-1 active:scale-95 shadow-sm"
              >
                {{ range.label }}
              </Link>
            </div>
        </div>
      </div>
    </div>
  </section>
</template>
