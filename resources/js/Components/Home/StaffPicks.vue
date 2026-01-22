<script setup>
const staffPicks = [
    {
        id: 1,
        title: 'Golden Hour in Kyoto',
        artist: 'Satoshi M.',
        price: '₱45,000',
        image: 'https://images.unsplash.com/photo-1545569341-9eb8b30979d9?q=80&w=1000&auto=format&fit=crop',
        isMain: true
    },
    {
        id: 2,
        title: 'Geometric Blue',
        artist: 'Anne Davis',
        price: '₱12,000',
        image: 'https://images.unsplash.com/photo-1557672172-298e090bd0f1?q=80&w=1000&auto=format&fit=crop',
        isMain: false
    },
    {
        id: 3,
        title: 'Silent Mountains',
        artist: 'Chen Wei',
        price: '₱18,500',
        image: 'https://images.unsplash.com/photo-1519681393784-d120267933ba?q=80&w=1000&auto=format&fit=crop',
        isMain: false
    },
    {
        id: 4,
        title: 'Urban Decay',
        artist: 'Ramon L.',
        price: '₱8,500',
        image: 'https://images.unsplash.com/photo-1515405295579-ba7b45490132?q=80&w=1000&auto=format&fit=crop',
        isMain: false,
        // Optional styling if we want it to span
        colspanClass: 'md:col-span-1'
    },
    {
        id: 5,
        title: 'Abstract Thoughts',
        artist: 'Lila K.',
        price: '₱22,000',
        image: 'https://images.unsplash.com/photo-1541963463532-d68292c34b19?q=80&w=1000&auto=format&fit=crop',
        isMain: false,
        // Make the last one span 2 columns to complete the 3-column grid row
        colspanClass: 'md:col-span-2'
    }
];
</script>

<template>
    <section class="w-full bg-gray-950 py-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            <!-- Header -->
            <div class="flex items-end justify-between mb-12">
                <div>
                    <h2 class="text-3xl md:text-5xl font-serif font-bold text-white tracking-tight">Staff Picks</h2>
                    <p class="text-gray-400 mt-2">Curated selections from our expert curators.</p>
                </div>
                <a href="#" class="hidden md:inline-flex items-center text-amber-400 hover:text-amber-300 transition-colors font-medium">
                    View All Picks &rarr;
                </a>
            </div>

            <!-- Bento Grid -->
            <!-- Desktop: 3 columns. Mobile: 1 column. -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 auto-rows-[250px] md:auto-rows-[300px]">
                
                <div 
                    v-for="pick in staffPicks" 
                    :key="pick.id"
                    class="group relative overflow-hidden rounded-3xl cursor-pointer bg-gray-900 shadow-2xl"
                    :class="[
                        pick.isMain ? 'md:col-span-2 md:row-span-2' : (pick.colspanClass || 'md:col-span-1 md:row-span-1')
                    ]"
                >
                    <!-- Background Image -->
                    <img 
                        :src="pick.image" 
                        :alt="pick.title" 
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110 opacity-90 group-hover:opacity-100"
                    />
                    
                    <!-- Dark Gradient Overlay (Initially light, darkens on hover) -->
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/20 to-transparent opacity-80 transition-opacity duration-300 group-hover:opacity-90"></div>

                    <!-- Badge for Main Pick -->
                    <div v-if="pick.isMain" class="absolute top-6 left-6 z-10">
                        <span class="inline-flex items-center px-3 py-1 bg-amber-400 text-gray-950 text-xs font-bold uppercase tracking-widest rounded-full shadow-lg">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            Curator's Choice
                        </span>
                    </div>

                    <!-- Content (Slide Up) -->
                    <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500 ease-out">
                        <!-- We hide the text initially with a mask or just rely on the subtle shift and opacity -->
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-75">
                            <p class="text-amber-400 font-medium text-sm md:text-base mb-1">{{ pick.artist }}</p>
                            <div class="flex items-baseline justify-between">
                                <h3 class="text-xl md:text-2xl font-serif font-bold text-white leading-tight">{{ pick.title }}</h3>
                                <p class="text-lg font-semibold text-white/90">{{ pick.price }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</template>
