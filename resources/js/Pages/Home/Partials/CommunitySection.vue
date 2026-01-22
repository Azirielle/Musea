<script setup>
// Mock artist data for the marquee
const artists = [
    { id: 1, name: 'Elena R.', avatar: 'https://i.pravatar.cc/150?u=1' },
    { id: 2, name: 'Marco V.', avatar: 'https://i.pravatar.cc/150?u=2' },
    { id: 3, name: 'Sarah J.', avatar: 'https://i.pravatar.cc/150?u=3' },
    { id: 4, name: 'Daiki T.', avatar: 'https://i.pravatar.cc/150?u=4' },
    { id: 5, name: 'Lila M.', avatar: 'https://i.pravatar.cc/150?u=5' },
    { id: 6, name: 'Chris P.', avatar: 'https://i.pravatar.cc/150?u=6' },
    { id: 7, name: 'Anna K.', avatar: 'https://i.pravatar.cc/150?u=7' },
    { id: 8, name: 'Tom H.', avatar: 'https://i.pravatar.cc/150?u=8' },
];

// Duplicate list for seamless infinite scroll
const marqueeList = [...artists, ...artists];
</script>

<template>
    <section class="py-20 bg-canvas overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-ink">Invest in Excellence</h2>
                <p class="mt-4 text-ink-light max-w-2xl mx-auto">
                    Whether you're starting your journey or building a legacy, we have a curated path for you.
                </p>
            </div>

            <!-- Price Tier Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-24">
                
                <!-- Card 1: Starter -->
                <div class="group relative p-8 rounded-2xl bg-gray-50 border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 mb-6 flex items-center justify-center rounded-full bg-white text-green-600 shadow-sm group-hover:bg-green-600 group-hover:text-white transition-colors">
                        <!-- Leaf Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">The Starter</h3>
                    <p class="text-sm font-semibold text-green-600 mb-4 tracking-wide uppercase">Under ₱5,000</p>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Perfect for new collectors. Discover rising talents and accessible prints that bring life to any room.
                    </p>
                    <a href="#" class="inline-block mt-6 text-sm font-bold text-gray-900 underline decoration-gray-300 underline-offset-4 hover:decoration-green-600 transition-all">Explore Starter Collection</a>
                </div>

                <!-- Card 2: Collector -->
                <div class="group relative p-8 rounded-2xl bg-indigo-50/50 border border-indigo-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 mb-6 flex items-center justify-center rounded-full bg-white text-indigo-600 shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <!-- Star Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-indigo-900 mb-2">The Collector</h3>
                    <p class="text-sm font-semibold text-indigo-600 mb-4 tracking-wide uppercase">₱5,000 - ₱20,000</p>
                    <p class="text-indigo-800/80 text-sm leading-relaxed">
                        For those refining their taste. Original paintings and limited edition sculptures from established artists.
                    </p>
                    <a href="#" class="inline-block mt-6 text-sm font-bold text-indigo-900 underline decoration-indigo-200 underline-offset-4 hover:decoration-indigo-600 transition-all">View Collector's Picks</a>
                </div>

                <!-- Card 3: Connoisseur -->
                <div class="group relative p-8 rounded-2xl bg-slate-900 border border-slate-800 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-12 h-12 mb-6 flex items-center justify-center rounded-full bg-slate-800 text-amber-400 shadow-sm group-hover:bg-amber-400 group-hover:text-slate-900 transition-colors">
                        <!-- Diamond Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">The Connoisseur</h3>
                    <p class="text-sm font-semibold text-amber-400 mb-4 tracking-wide uppercase">Over ₱20,000</p>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Investment-grade masterpieces. Exclusive acquisitions for the serious investor and art lover.
                    </p>
                    <a href="#" class="inline-block mt-6 text-sm font-bold text-white underline decoration-slate-700 underline-offset-4 hover:decoration-amber-400 transition-all">Enter Private Gallery</a>
                </div>

            </div>

            <!-- Community Marquee Section -->
            <div class="relative w-full overflow-hidden border-t border-divider pt-16">
                <div class="text-center mb-10">
                    <h3 class="text-2xl font-serif font-bold text-ink">Join our Community of 500+ Artists</h3>
                </div>
                
                <!-- Infinite Scroll Gradient Masks -->
                <div class="absolute top-32 left-0 w-32 h-32 bg-gradient-to-r from-canvas to-transparent z-10 pointer-events-none"></div>
                <div class="absolute top-32 right-0 w-32 h-32 bg-gradient-to-l from-canvas to-transparent z-10 pointer-events-none"></div>

                <!-- Marquee Track -->
                <div class="flex animate-scroll hover:[animation-play-state:paused] w-max">
                    <div 
                        v-for="(artist, index) in marqueeList" 
                        :key="index"
                        class="flex flex-col items-center justify-center mx-8 group cursor-pointer"
                    >
                        <div class="relative">
                            <img 
                                :src="artist.avatar" 
                                :alt="artist.name" 
                                class="w-16 h-16 rounded-full object-cover ring-2 ring-transparent group-hover:ring-accent transition-all duration-300 grayscale group-hover:grayscale-0"
                            />
                            <!-- Checkmark Badge -->
                            <div class="absolute -bottom-1 -right-1 bg-white rounded-full p-0.5 shadow-sm">
                                <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <span class="mt-3 text-sm font-medium text-ink-light group-hover:text-ink transition-colors">
                            {{ artist.name }}
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </section>
</template>

<style scoped>
/* Ensure smooth marquee movement */
.animate-scroll {
    /* Tailwind will inject the animation logic, but we ensure structure here */
    display: flex;
}
</style>
