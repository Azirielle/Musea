<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import HomeCarousel from '@/Components/HomeCarousel.vue';

import SuccessModal from '@/Components/SuccessModal.vue';
import { usePage } from '@inertiajs/vue3';
import { ref, watch, onMounted } from 'vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

const page = usePage();
const showSuccessModal = ref(false);
const successMessage = ref('');

onMounted(() => {
    // Check if there's a flash message on mount (e.g. after redirect)
    if (page.props.flash.success) {
        successMessage.value = page.props.flash.success;
        showSuccessModal.value = true;
    }
});

// Watch for changes in flash messages (optional if we only care about initial load after redirect, but good for SPA nav)
watch(() => page.props.flash.success, (newMessage) => {
    if (newMessage) {
        successMessage.value = newMessage;
        showSuccessModal.value = true;
    }
});

const closeSuccessModal = () => {
    showSuccessModal.value = false;
    // Clear the flash message so it doesn't reappear on reload/nav if not desired? 
    // Usually inertia handles clearing on next request, but local state toggle is enough.
};

const canvasItems = [
    { title: 'Red Buds on Blue Floral', artist: 'Ken Tan', price: '4,200', image: '/images/team/Canvas/canvas1.png' },
    { title: 'Blue Diagonals with Black Drips', artist: 'Maya Ortiz', price: '6,000', image: '/images/team/Canvas/canvas2.png' },
    { title: 'Dark Rainbow Swirl Abstract', artist: 'Liam Becker', price: '3,000', image: '/images/team/Canvas/canvas3.png' },
    { title: 'Blue Brushstroke Texture', artist: 'Naomi Fields', price: '7,000', image: '/images/team/Canvas/canvas4.png' },
    { title: 'Tree Branches with Colorful Leaves', artist: 'Aria Chen', price: '4,750', image: '/images/team/Canvas/canvas5.png' },
];

const drawingItems = [
    { title: 'Bird Studies on Kraft Paper', artist: 'Avery Lane', price: '2,000', image: '/images/team/Drawings/birmingham-museums-trust-KfRUve5NtO8-unsplash.png' },
    { title: 'Woman Resting on Bed, Ink', artist: 'Quinn Harper', price: '3,000', image: '/images/team/Drawings/birmingham-museums-trust-x2g6ZnLO0_E-unsplash.png' },
    { title: 'Vase of Mixed Flowers on Black', artist: 'Noah Voss', price: '3,750', image: '/images/team/Drawings/europeana-5TK1F5VfdIk-unsplash.png' },
    { title: 'Botanical Bouquet with Lilies', artist: 'Iris Bennett', price: '2,750', image: '/images/team/Drawings/europeana-SMWPYQhVRuY-unsplash.png' },
    { title: 'Crowd of Cartoon Faces', artist: 'Riley Park', price: '4,000', image: '/images/team/Drawings/leeann-cline-I2RnOO8ojQ4-unsplash.png' },
];

const paintingItems = [
    { title: 'Baroque Ceiling with Angels', artist: 'Lucia Moretti', price: '1,500', image: '/images/team/Paintings/adrianna-geo-1rBg5YSi00c-unsplash.jpg' },
    { title: 'Castle on Rocky Cliff', artist: 'Graham Wells', price: '2,250', image: '/images/team/Paintings/birmingham-museums-trust-sJr8LDyEf7k-unsplash.jpg' },
    { title: 'Woodland Path with Trees', artist: 'Clara Benton', price: '1,250', image: '/images/team/Paintings/birmingham-museums-trust-zWE5pOLWkio-unsplash.jpg' },
    { title: 'Girl in White Picking Flowers', artist: 'Sophie Hart', price: '3,500', image: '/images/team/Paintings/europeana-VsnDYMWollM-unsplash.jpg' },
    { title: 'Still Life with Flowers', artist: 'Jonas Reed', price: '2,500', image: '/images/team/Paintings/europeana-YIfFVwDcgu8-unsplash.jpg' },
];
</script>

<template>
    <Head title="Home" />
    <SuccessModal :show="showSuccessModal" :message="successMessage" @close="closeSuccessModal" />
    <MainLayout>
        <!-- Hero Section -->
        <section class="hero bg-gradient-to-br from-[#faf0e1] to-white relative py-16 md:py-24 overflow-hidden">
             <!-- Background Texture/Gradient Overlay -->
            <div class="absolute inset-0 pointer-events-none opacity-50 bg-[radial-gradient(circle_at_80%_20%,_rgba(203,163,92,0.15)_0%,_transparent_50%)]"></div>
            
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-12 items-center relative z-10">
                <div class="hero-img">
                    <img 
                        src="/images/team/MainPicture/MarkLloyd.png" 
                        alt="Start of a New Day" 
                        class="w-full rounded-2xl shadow-2xl border-4 border-white/80 transform hover:scale-[1.02] transition duration-500"
                    >
                </div>
                
                <div class="hero-details text-center md:text-right">
                    <h1 class="text-4xl md:text-6xl font-extrabold text-[#1A1A1A] mb-4 tracking-tight drop-shadow-sm">Canvas Print — 'Sunrise'</h1>
                    <p class="text-lg text-gray-500 font-semibold uppercase tracking-wide mb-4">by Mark Lloyd</p>
                    <p class="text-lg text-gray-700 leading-relaxed max-w-lg ml-auto mb-6">
                        A study of morning light and texture — created to explore the subtle transition of color at dawn and captured on archival canvas to retain vibrancy.
                    </p>
                    <span class="block text-3xl font-extrabold text-[#CBA35C] mb-6 drop-shadow-sm">₱10,499.00</span>
                    
                    <div class="flex gap-4 justify-center md:justify-end">
                        <Link :href="route('shop.index')" class="bg-transparent border-2 border-[#CBA35C] text-[#CBA35C] px-6 py-3 rounded-xl font-bold hover:bg-[#CBA35C]/10 transition">
                            View Details
                        </Link>
                         <button class="bg-gradient-to-br from-[#CBA35C] to-[#B89350] text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Category Carousels -->
        <HomeCarousel title="Canvas" id="canvas" :items="canvasItems" />
        <HomeCarousel title="Drawings" id="drawings" :items="drawingItems" />
        <HomeCarousel title="Painting" id="painting" :items="paintingItems" />

        <!-- Featured Crafts Section -->
        <section class="section py-20 px-6 bg-[#FAF7F2] relative">
            <h2 class="section-title text-center text-4xl font-extrabold text-[#1A1A1A] mb-12 relative inline-block w-full">
                Featured Crafts
                <span class="absolute bottom-[-12px] left-1/2 -translate-x-1/2 w-20 h-1 bg-gradient-to-r from-transparent via-[#CBA35C] to-transparent rounded-full"></span>
            </h2>

            <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Craft 1 -->
                <div class="craft bg-gradient-to-br from-white to-[#fefefe] rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl hover:-translate-y-2 transition duration-300 border border-[#CBA35C]/10 flex flex-col">
                    <div class="thumb p-6 bg-white flex items-center justify-center flex-1">
                        <img src="/images/team/painted-vase.png" alt="Painted Vase" class="max-h-[300px] object-contain">
                    </div>
                    <div class="meta p-5 text-center bg-white border-t border-gray-100">
                        <h3 class="text-xl font-bold text-[#1A1A1A] mb-1">Painted Vase</h3>
                        <p class="text-gray-500 text-sm mb-2">by Sophie Hart</p>
                        <p class="text-2xl font-bold text-[#CBA35C]">₱4,200</p>
                    </div>
                </div>

                <!-- Craft 2 -->
                <div class="craft bg-gradient-to-br from-white to-[#fefefe] rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl hover:-translate-y-2 transition duration-300 border border-[#CBA35C]/10 flex flex-col">
                    <div class="thumb p-6 bg-white flex items-center justify-center flex-1">
                        <img src="/images/team/wooden-sculpture.png" alt="Wooden Sculpture" class="max-h-[300px] object-contain">
                    </div>
                    <div class="meta p-5 text-center bg-white border-t border-gray-100">
                        <h3 class="text-xl font-bold text-[#1A1A1A] mb-1">Wooden Sculpture</h3>
                        <p class="text-gray-500 text-sm mb-2">by Mark Lloyd</p>
                        <p class="text-2xl font-bold text-[#CBA35C]">₱7,000</p>
                    </div>
                </div>

                <!-- Craft 3 -->
                <div class="craft bg-gradient-to-br from-white to-[#fefefe] rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl hover:-translate-y-2 transition duration-300 border border-[#CBA35C]/10 flex flex-col">
                    <div class="thumb p-6 bg-white flex items-center justify-center flex-1">
                        <img src="/images/team/hand-woven-basket.png" alt="Handwoven Basket" class="max-h-[300px] object-contain">
                    </div>
                    <div class="meta p-5 text-center bg-white border-t border-gray-100">
                        <h3 class="text-xl font-bold text-[#1A1A1A] mb-1">Handwoven Basket</h3>
                        <p class="text-gray-500 text-sm mb-2">by Riley Park</p>
                        <p class="text-2xl font-bold text-[#CBA35C]">₱4,750</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section (Partial) -->
        <section class="about max-w-6xl mx-auto my-20 p-8 md:p-12 bg-white rounded-3xl shadow-lg flex flex-col md:flex-row items-center gap-12 border border-gray-100">
             <div class="md:w-1/2">
                <img src="/images/team/MainPicture/MarkLloyd.png" alt="About Musea" class="rounded-2xl shadow-xl transform rotate-2 hover:rotate-0 transition duration-500">
             </div>
             <div class="md:w-1/2 text-left">
                <h2 class="text-3xl md:text-4xl font-extrabold text-[#1A1A1A] mb-6">About Musea</h2>
                <p class="text-gray-600 text-lg leading-relaxed mb-6">
                    We are a curated marketplace for unique, handcrafted art. From canvas prints to sculptures, our goal is to connect you with independent artists who pour their soul into every piece.
                </p>
                <Link :href="route('pages.about')" class="inline-block text-[#CBA35C] font-bold text-lg hover:underline">
                    Read our story &rarr;
                </Link>
             </div>
        </section>

    </MainLayout>
</template>

<style scoped>
/* Scoped styles can handle specific legacy tweaks if needed, but Tailwind classes above handle most. */
</style>
