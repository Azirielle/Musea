<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

defineProps({
    artwork: Object
});

import { useCart } from '@/composables/useCart';
const { addToCart } = useCart();
</script>

<template>
    <Head :title="artwork.title" />
    <MainLayout>
        <div class="pt-24 pb-12 px-6 bg-[#FAF7F2] min-h-screen flex items-center">
            <div class="max-w-6xl mx-auto w-full bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div class="bg-gray-100 relative overflow-hidden">
                         <img 
                            :src="artwork.image_url || '/images/placeholder-art.jpg'" 
                            :alt="artwork.title" 
                            class="w-full h-full object-cover min-h-[500px]"
                        >
                    </div>
                    <div class="p-8 md:p-12 flex flex-col justify-center relative">
                        <div class="flex items-center gap-3 mb-4">
                             <span class="text-xs font-bold tracking-wider uppercase bg-[#CBA35C] text-white px-3 py-1 rounded-full">{{ artwork.category }}</span>
                             <span :class="artwork.stock > 0 ? 'text-green-600' : 'text-red-500'" class="text-sm font-medium">
                                {{ artwork.stock > 0 ? 'In Stock' : 'Out of Stock' }}
                             </span>
                        </div>
                        
                        <h1 class="text-4xl md:text-5xl font-bold text-[#1A1A1A] mb-4">{{ artwork.title }}</h1>
                        
                        <!-- Semi-Blurred Details Section -->
                        <div class="relative mb-8">
                            <div :class="{ 'blur-sm opacity-50 select-none': !$page.props.auth.user }">
                                <div class="flex items-center gap-4 mb-8 pb-8 border-b border-gray-100">
                                     <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-lg font-bold text-gray-500">
                                        {{ artwork.artist.first_name[0] }}
                                     </div>
                                     <div>
                                        <p class="text-sm text-gray-500">Created by</p>
                                        <p class="font-bold text-[#1A1A1A]">{{ artwork.artist.first_name }} {{ artwork.artist.last_name }}</p>
                                     </div>
                                </div>

                                <p class="text-gray-600 leading-relaxed text-lg">
                                    {{ artwork.description || 'No description available for this masterpiece.' }}
                                </p>
                            </div>
                            
                            <!-- Overlay just for the description/artist part -->
                            <div v-if="!$page.props.auth.user" class="absolute inset-0 flex items-center justify-center z-10">
                                <div class="bg-white/80 backdrop-blur-md px-6 py-3 rounded-full shadow-lg border border-gray-100 text-center">
                                    <p class="text-sm font-bold text-[#1A1A1A] mb-1">Description Hidden</p>
                                    <Link :href="route('login')" class="text-xs text-[#CBA35C] font-bold hover:underline">Login to read full story</Link>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-auto border-t pt-6">
                            <div class="text-3xl font-bold text-[#1A1A1A]">${{ artwork.price }}</div>
                            <button 
                                v-if="artwork.stock > 0"
                                @click="addToCart(artwork)"
                                class="bg-[#1A1A1A] text-white px-8 py-4 rounded-xl font-bold hover:bg-[#333] transition transform hover:-translate-y-1 shadow-lg"
                            >
                                Add to Cart
                            </button>
                            <button 
                                v-else 
                                disabled
                                class="bg-gray-300 text-gray-500 px-8 py-4 rounded-xl font-bold cursor-not-allowed"
                            >
                                Sold Out
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
