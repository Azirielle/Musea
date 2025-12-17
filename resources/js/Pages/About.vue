<script setup>
import { Head } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
</script>

<template>
    <Head title="About Us" />
    <MainLayout>
        <!-- Hero Section -->
        <div class="bg-gradient-to-br from-[#F7F1E3] to-[#EEE6D9] py-16 px-6 text-center">
             <h1 class="text-4xl md:text-5xl font-bold text-[#1A1A1A] mb-3">About Musea</h1>
             <p class="text-xl text-[#2E2E2E]">Celebrating craftsmanship and creativity</p>
        </div>

        <div class="max-w-6xl mx-auto px-6 py-12">
            
            <!-- Our Story -->
            <div class="text-center mb-16 max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold text-[#1A1A1A] mb-6">Our Story</h2>
                <div class="prose prose-lg text-gray-600 mx-auto leading-relaxed">
                    <p class="mb-4">
                        Musea is a digital haven for artisans and art lovers alike. We connect passionate creators with those who appreciate 
                        the beauty of handmade craftsmanship.
                    </p>
                    <p>
                        Every piece in our collection tells a story of dedication, skill, and the human touch — from intricate paintings 
                        to woven baskets and sculpted wonders. Founded with the vision to preserve traditional arts while inspiring modern creativity, 
                        Musea is more than a marketplace; it's a community where artistry thrives.
                    </p>
                </div>
            </div>

            <!-- Our Values -->
            <div class="bg-white rounded-2xl shadow-sm p-10 md:p-12">
                <h2 class="text-3xl font-bold text-[#1A1A1A] text-center mb-10">Our Values</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Value 1 -->
                    <div class="bg-[#F7F1E3] rounded-xl p-8 text-center hover:-translate-y-1 transition duration-300">
                        <h5 class="text-xl font-bold text-[#CBA35C] mb-3">Authenticity</h5>
                        <p class="text-gray-700 leading-relaxed">
                            We celebrate genuine craftsmanship, ensuring every item is made with love and traditional techniques passed down through generations.
                        </p>
                    </div>

                    <!-- Value 2 -->
                    <div class="bg-[#F7F1E3] rounded-xl p-8 text-center hover:-translate-y-1 transition duration-300">
                        <h5 class="text-xl font-bold text-[#CBA35C] mb-3">Quality</h5>
                        <p class="text-gray-700 leading-relaxed">
                            Each product in our collection meets the highest standards of durability and aesthetic excellence, crafted to last a lifetime.
                        </p>
                    </div>

                    <!-- Value 3 -->
                    <div class="bg-[#F7F1E3] rounded-xl p-8 text-center hover:-translate-y-1 transition duration-300">
                        <h5 class="text-xl font-bold text-[#CBA35C] mb-3">Community</h5>
                        <p class="text-gray-700 leading-relaxed">
                            We foster connections between artisans and enthusiasts, building a supportive network that values creativity and cultural heritage.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </MainLayout>
</template>
