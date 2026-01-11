<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

const props = defineProps({
    favorites: Array,
});

const removeFavorite = (id) => {
    router.post(route('artworks.unlike', id), {}, {
        preserveScroll: true,
        only: ['favorites'],
    });
};
</script>

<template>
    <Head title="My Favorites" />

    <MainLayout>
        <div class="bg-canvas min-h-screen pt-32 pb-20">
            <div class="max-w-7xl mx-auto px-6">
                <!-- Header -->
                <div class="mb-12">
                    <h1 class="text-4xl font-serif font-bold text-ink mb-4 italic">My Favorites</h1>
                    <p class="text-ink-light">A collection of artworks you love.</p>
                </div>

                <!-- Empty State -->
                <div v-if="favorites.length === 0" class="text-center py-32 bg-white rounded-3xl border border-dashed border-divider">
                    <div class="mb-6 inline-flex p-4 rounded-full bg-canvas">
                        <svg class="w-8 h-8 text-ink-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-ink mb-2">No favorites yet</h3>
                    <p class="text-ink-light mb-8">Start exploring and save the artworks you love.</p>
                    <Link href="/shop" class="inline-flex items-center gap-2 px-8 py-3 bg-ink text-white rounded-full font-bold hover:bg-ink-light transition-all">
                        Explore Artworks
                    </Link>
                </div>

                <!-- Favorites Grid -->
                <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    <div v-for="artwork in favorites" :key="artwork.id" class="group relative">
                        <!-- Card -->
                        <div class="relative aspect-square bg-gray-100 overflow-hidden rounded-sm mb-4">
                            <!-- Image -->
                            <Link :href="route('shop.show', artwork.id)">
                                <img 
                                    :src="artwork.image_url" 
                                    :alt="artwork.title"
                                    class="w-full h-full object-cover transition duration-700 group-hover:scale-105"
                                    :class="{ 'grayscale opacity-70': artwork.is_sold_out }"
                                >
                            </Link>

                            <!-- Sold Out Overlay -->
                            <div v-if="artwork.is_sold_out" class="absolute inset-0 flex items-center justify-center bg-black/20 pointer-events-none">
                                <span class="bg-black/80 text-white px-4 py-2 text-xs font-bold uppercase tracking-widest backdrop-blur-sm">Sold Out</span>
                            </div>

                            <!-- Remove Button -->
                            <button 
                                @click.prevent="removeFavorite(artwork.id)"
                                class="absolute top-3 right-3 p-2 bg-white/90 backdrop-blur rounded-full text-red-500 hover:bg-red-50 transition-all opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0"
                                title="Remove from favorites"
                            >
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </button>

                            <!-- Price Tag -->
                            <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur px-3 py-1 text-[10px] font-bold tracking-widest uppercase shadow-sm">
                                ₱{{ artwork.price }}
                            </div>
                        </div>

                        <!-- Details -->
                        <div>
                            <Link :href="route('shop.show', artwork.id)">
                                <h3 class="font-serif font-bold text-lg text-ink truncate group-hover:text-accent transition-colors">
                                    {{ artwork.title }}
                                </h3>
                            </Link>
                            <p class="text-xs text-ink-light uppercase tracking-wider mt-1">{{ artwork.artist }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
