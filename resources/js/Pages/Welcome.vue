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
});
</script>

<template>
    <Head title="Home" />
    
    <!-- Use withHeaderPadding="false" so the Hero image bleeds to the top, 
         assuming the header is transparent or overlays nicely. 
         If standard behavior is preferred, remove the prop. -->
    <MainLayout :with-header-padding="false">
        
        <HeroSection />

        <div class="py-20 px-6" id="feed">
            <div class="max-w-7xl mx-auto glass p-8 md:p-12 rounded-[2rem]">
                <div class="text-center mb-16">
                    <span v-if="$page.props.auth.user" class="text-grape text-xs font-bold tracking-[0.2em] uppercase mb-4 block">Curated for {{ $page.props.auth.user.first_name }}</span>
                    <span v-else class="text-grape text-xs font-bold tracking-[0.2em] uppercase mb-4 block">Trending Now</span>
                    
                    <h2 class="text-4xl md:text-6xl font-black text-charcoal">Your Daily Feed</h2>
                </div>
                
                <MasonryGrid :artworks="recommendedArtworks" />
                
                <div v-if="recommendedArtworks.length === 0" class="text-center py-12">
                    <p class="text-charcoal/60 mb-8 font-medium">No artworks found. Start following artists to see their latest work!</p>
                    <Link href="/shop" class="px-8 py-3 bg-grape text-white rounded-full font-bold shadow-lg shadow-grape/20 hover:scale-105 transition-transform">Browse All</Link>
                </div>

                <div v-if="!$page.props.auth.user" class="mt-16 text-center">
                    <p class="text-charcoal/50 mb-6 font-medium">Join Musea to get a personalized feed based on your taste.</p>
                    <Link href="/register" class="px-10 py-4 glass border-white/40 text-charcoal rounded-full font-bold hover:bg-white/40 transition-all inline-block hover:scale-105">Create Account</Link>
                </div>
            </div>
        </div>

        <DiscoverySection :categories="categories" />
        
        <CommunitySection :artists="featuredArtists" :staff-picks="staffPicks" />
        
        <TrustBar />
        
    </MainLayout>
</template>
