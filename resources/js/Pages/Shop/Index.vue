<script setup>
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import { ref, watch } from 'vue';

const props = defineProps({
    artworks: Object,
    filters: Object
});

const search = ref(props.filters.search || '');
const category = ref(props.filters.category || '');

// Debounce search
let timeout;
const handleSearch = () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get('/shop', { search: search.value, category: category.value }, { preserveState: true, replace: true });
    }, 300);
};

const filterCategory = (cat) => {
    category.value = cat;
    router.get('/shop', { search: search.value, category: category.value }, { preserveState: true, replace: true });
}

import { useCart } from '@/composables/useCart';
const { addToCart } = useCart();
</script>

<template>
    <Head title="Shop Artworks" />
    <MainLayout>
        <div class="pb-12 px-6 bg-canvas">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                    <h1 class="text-4xl font-bold text-[#1A1A1A]">Shop Artworks</h1>
                    
                    <div class="flex gap-4 w-full md:w-auto">
                        <input 
                            v-model="search" 
                            @input="handleSearch"
                            type="text" 
                            placeholder="Search artworks..." 
                            class="px-4 py-2 border border-divider rounded-lg focus:outline-none focus:border-accent w-full md:w-64"
                        >
                        <select v-model="category" @change="handleSearch" class="px-4 py-2 border border-divider rounded-lg focus:outline-none focus:border-accent">
                            <option value="">All Categories</option>
                            <option value="Painting">Painting</option>
                            <option value="Sculpture">Sculpture</option>
                            <option value="Canvas">Canvas</option>
                            <option value="Drawing">Drawing</option>
                            <option value="Vase">Vase</option>
                        </select>
                    </div>
                </div>

                <div v-if="artworks.data.length === 0" class="text-center py-20 text-gray-500">
                    No artworks found matching your criteria.
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div v-for="artwork in artworks.data" :key="artwork.id" class="group relative">
                        <Link :href="route('shop.show', artwork.id)" class="block bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition duration-300">
                            <div class="aspect-square bg-gray-200 overflow-hidden relative">
                                <img 
                                    :src="artwork.image_url || '/images/placeholder-art.jpg'" 
                                    :alt="artwork.title" 
                                    class="w-full h-full object-cover transition duration-500 group-hover:scale-105"
                                >
                                
                                <div v-if="$page.props.auth.user" class="absolute bottom-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-sm font-bold text-[#1A1A1A]">
                                    ₱{{ artwork.price }}
                                </div>
                            </div>
                            
                            <div class="p-4 relative">
                                <div>
                                    <h3 class="font-bold text-lg text-[#1A1A1A] truncate">{{ artwork.title }}</h3>
                                    <p class="text-sm text-gray-500 mb-2">{{ artwork.artist ? artwork.artist.first_name + ' ' + artwork.artist.last_name : 'Unknown Artist' }}</p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs bg-zinc-100 px-2 py-1 rounded text-ink-light">{{ artwork.category }}</span>
                                        <span v-if="artwork.stock > 0" class="text-xs text-green-600 font-medium">In Stock</span>
                                        <span v-else class="text-xs text-red-500 font-medium">Sold Out</span>
                                    </div>
                                </div>
                            </div>
                        </Link>
                        
                        <!-- Add to Cart (Overlay on hover for desktop, or visible) -->
                        <button 
                            @click.prevent="addToCart(artwork)"
                            class="absolute top-4 right-4 bg-accent text-white p-2 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 hover:bg-black"
                            title="Add to Cart"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-12 flex justify-center gap-2" v-if="artworks.links.length > 3">
                     <template v-for="(link, k) in artworks.links" :key="k">
                        <Link 
                            v-if="link.url" 
                            :href="link.url" 
                            v-html="link.label"
                            class="px-4 py-2 rounded-lg border text-sm font-medium transition"
                            :class="{'bg-accent text-white border-accent': link.active, 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300': !link.active}"
                        />
                        <span v-else v-html="link.label" class="px-4 py-2 text-gray-400 text-sm"></span>
                     </template>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
