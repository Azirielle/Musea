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
  <section class="py-20 px-6">
    <div class="container mx-auto">
      <h2 class="text-4xl font-black mb-12 text-center text-charcoal">Find Your Medium</h2>
      
      <!-- Shop by Medium -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
        <Link 
          v-for="category in categories" 
          :key="category.name"
          :href="`/shop?category=${category.name}`"
          class="group relative h-80 rounded-[2.5rem] overflow-hidden glass border-white/40 shadow-xl hover:shadow-grape/10 transition-all duration-500 hover:-translate-y-2 p-3"
        >
          <div class="relative h-full w-full rounded-[2rem] overflow-hidden">
            <!-- Background Image -->
            <img 
              :src="category.image"
              loading="lazy"
              alt=""
              class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
            />
            <!-- Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-charcoal/80 via-transparent to-transparent opacity-60 group-hover:opacity-40 transition-opacity"></div>
            
            <div class="absolute bottom-0 left-0 p-8 text-white z-10 w-full">
                <h3 class="text-2xl font-black mb-1 drop-shadow-md">{{ category.name }}</h3>
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold tracking-widest text-white/80 uppercase">{{ category.count }}+ pieces</p>
                    <div class="bg-white/20 backdrop-blur-md p-2 rounded-full transform translate-x-4 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M9 5l7 7-7 7" /></svg>
                    </div>
                </div>
            </div>
          </div>
        </Link>
      </div>

      <!-- Shop by Price -->
      <div class="max-w-4xl mx-auto glass p-10 rounded-[2.5rem] text-center">
        <h3 class="text-sm font-black uppercase tracking-[0.3em] mb-10 text-charcoal/40">Browse by Price Point</h3>
        <div class="flex flex-wrap justify-center gap-6">
          <Link 
            v-for="range in priceRanges" 
            :key="range.label"
            :href="`/shop?price_range=${range.value}`"
            class="px-10 py-4 rounded-full glass border-white/50 text-charcoal font-black text-xs uppercase tracking-widest hover:bg-grape hover:text-white hover:border-grape transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-lg shadow-charcoal/5"
          >
            {{ range.label }}
          </Link>
        </div>
      </div>
    </div>
  </section>
</template>
