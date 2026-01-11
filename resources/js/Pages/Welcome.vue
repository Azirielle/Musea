<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import HeroSection from '@/Components/Home/HeroSection.vue';
import MasonryGrid from '@/Components/MasonryGrid.vue';
import DiscoverySection from '@/Components/Home/DiscoverySection.vue';
import CommunitySection from '@/Components/Home/CommunitySection.vue';
import TrustBar from '@/Components/Home/TrustBar.vue';

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    recommendedArtworks: {
        type: Array,
        default: () => []
    },
    featuredArtists: {
        type: Array,
        default: () => []
    },
    staffPicks: {
        type: Array,
        default: () => []
    },
    categories: {
        type: Array,
        default: () => []
    },
    featuredArtwork: {
        type: Object,
        default: null
    }
});
</script>

<template>
    <Head title="Home" />
    
    <!-- Use withHeaderPadding="false" so the Hero image bleeds to the top, 
         assuming the header is transparent or overlays nicely. 
         If standard behavior is preferred, remove the prop. -->
    <MainLayout :with-header-padding="false">
        
        <HeroSection :featured="featuredArtwork" />

        <div class="bg-canvas py-32 px-6" id="feed">
            <div class="max-w-[1400px] mx-auto">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-20 gap-8">
                    <div class="space-y-4">
                        <div class="inline-flex items-center gap-2 group">
                            <span class="w-8 h-px bg-accent/30 group-hover:w-12 transition-all duration-500"></span>
                            <span v-if="$page.props.auth.user" class="text-accent text-[10px] font-black tracking-[0.3em] uppercase">Curated for {{ $page.props.auth.user.first_name }}</span>
                            <span v-else class="text-accent text-[10px] font-black tracking-[0.3em] uppercase">Trending Now</span>
                        </div>
                        <h2 class="text-5xl md:text-7xl font-serif font-bold text-ink leading-tight italic">Your Daily Feed</h2>
                    </div>
                </div>
                
                <MasonryGrid :artworks="recommendedArtworks" />
                
                <div v-if="recommendedArtworks.length === 0" class="text-center py-32 bg-white/50 backdrop-blur-sm rounded-[3rem] border border-divider/50 mt-12">
                    <p class="text-ink-light text-lg mb-8 font-light italic">No masterpieces found in your orbit yet.</p>
                    <Link href="/shop" class="px-12 py-5 bg-ink text-white rounded-full font-bold text-sm tracking-widest hover:bg-accent transition-all duration-500 uppercase">Explore the Gallery</Link>
                </div>

                <div v-if="!$page.props.auth.user" class="mt-32 text-center p-20 bg-ink text-white rounded-[4rem] overflow-hidden relative group">
                    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1547891654-e66ed7ebb968?auto=format&fit=crop&q=80&w=1200')] opacity-10 grayscale group-hover:scale-110 transition-transform duration-1000"></div>
                    <div class="relative z-10 space-y-8">
                        <p class="text-white/60 text-sm font-bold tracking-[0.4em] uppercase">Join the Collective</p>
                        <h3 class="text-4xl md:text-6xl font-serif font-bold italic">A World of Art, Curated for You.</h3>
                        <div class="pt-6">
                            <Link href="/register" class="px-12 py-5 bg-white text-ink rounded-full font-bold text-sm tracking-widest hover:bg-accent hover:text-white transition-all duration-500 uppercase block sm:inline-block">Create Your Portfolio</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <DiscoverySection :categories="categories" />
        
        <CommunitySection :artists="featuredArtists" :staff-picks="staffPicks" />
        
        <TrustBar />
        
    </MainLayout>
</template>
