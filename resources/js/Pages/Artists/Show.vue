<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import UserBadge from '@/Components/UserBadge.vue';
import { ref } from 'vue';

const props = defineProps({
    artist: Object,
    artworks: Object,
    isFollowing: Boolean,
});

const following = ref(props.isFollowing);

const toggleFollow = () => {
    const url = following.value 
        ? route('artists.unfollow', props.artist.id) 
        : route('artists.follow', props.artist.id);

    router.visit(url, {
        method: 'post',
        preserveScroll: true,
        onSuccess: () => {
            following.value = !following.value;
        }
    });
};
</script>

<template>
    <Head :title="artist.name + ' | Artist Profile'" />
    <MainLayout>
        <div class="bg-canvas min-h-screen">
            <!-- Artist Header Section -->
            <div class="pt-32 pb-20 bg-white border-b border-divider">
                <div class="max-w-7xl mx-auto px-6">
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-12">
                        <!-- Avatar -->
                        <div class="w-48 h-48 rounded-full overflow-hidden shadow-2xl border-4 border-white flex-shrink-0">
                            <img :src="artist.avatar" :alt="artist.name" class="w-full h-full object-cover">
                        </div>
                        
                        <!-- Info -->
                        <div class="flex-1 text-center md:text-left pt-4">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-6">
                                <div>
                                    <h1 class="text-4xl md:text-5xl font-serif font-bold text-ink mb-2 flex items-center gap-2">
                                        {{ artist.name }}
                                        <UserBadge :role="artist.role" :is-verified="!!artist.is_verified" />
                                    </h1>
                                    <p class="text-ink-light flex items-center justify-center md:justify-start gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        {{ artist.location || 'Musea Collective' }}
                                    </p>
                                </div>
                                <div class="flex justify-center md:justify-end gap-3">
                                    <button 
                                        v-if="$page.props.auth.user && $page.props.auth.user.id !== artist.id"
                                        @click="toggleFollow" 
                                        class="px-8 py-3 rounded-full font-bold transition-all shadow-lg"
                                        :class="following ? 'bg-zinc-100 text-ink border border-divider' : 'bg-accent text-white hover:bg-opacity-90'"
                                    >
                                        {{ following ? 'Following' : 'Follow Artist' }}
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex justify-center md:justify-start gap-8 border-y border-divider py-6 mb-8">
                                <div class="text-center md:text-left">
                                    <p class="text-2xl font-serif font-bold text-ink italic">{{ artist.artworks_count }}</p>
                                    <p class="text-xs font-bold tracking-widest text-ink-light uppercase">Artworks</p>
                                </div>
                                <div class="text-center md:text-left">
                                    <p class="text-2xl font-serif font-bold text-ink italic">{{ artist.followers_count }}</p>
                                    <p class="text-xs font-bold tracking-widest text-ink-light uppercase">Followers</p>
                                </div>
                            </div>
                            
                            <p class="text-ink-light leading-relaxed max-w-2xl text-lg italic">
                                "{{ artist.bio }}"
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Artist Artworks Grid -->
            <div class="max-w-7xl mx-auto px-6 py-20">
                <div class="flex flex-col md:flex-row justify-between items-center mb-12 gap-6">
                    <h2 class="text-3xl font-serif font-bold text-ink italic">The Collection</h2>
                    <Link href="/shop" class="group flex items-center gap-2 text-sm font-bold text-ink hover:text-accent transition-colors">
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        Back to All Artworks
                    </Link>
                </div>

                <div v-if="artworks.data.length === 0" class="text-center py-20 bg-white rounded-3xl border border-dashed border-divider">
                    <p class="text-ink-light">No artworks available in this collection yet.</p>
                </div>

                <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    <div v-for="artwork in artworks.data" :key="artwork.id" class="group">
                        <Link :href="route('shop.show', artwork.id)" class="block relative aspect-square bg-gray-100 overflow-hidden rounded-sm mb-4">
                            <img 
                                :src="artwork.image_url" 
                                loading="lazy" 
                                class="w-full h-full object-cover transition duration-700 group-hover:scale-105"
                            >
                            <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur px-3 py-1 text-[10px] font-bold tracking-widest uppercase shadow-sm">
                                ₱{{ artwork.price }}
                            </div>
                        </Link>
                        <h4 class="font-serif font-bold text-lg text-ink truncate group-hover:text-accent transition-colors">
                            {{ artwork.title }}
                        </h4>
                        <p class="text-xs text-ink-light uppercase tracking-wider mt-1">{{ artwork.category }}</p>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-20 flex justify-center" v-if="artworks.links.length > 3">
                    <div class="flex gap-2">
                        <template v-for="(link, k) in artworks.links" :key="k">
                            <Link 
                                v-if="link.url" 
                                :href="link.url" 
                                v-html="link.label"
                                class="w-10 h-10 flex items-center justify-center rounded-full text-sm font-bold transition-all"
                                :class="{'bg-ink text-white shadow-lg': link.active, 'bg-white text-ink-light hover:bg-gray-100 hover:text-ink': !link.active}"
                            />
                            <span v-else v-html="link.label" class="w-10 h-10 flex items-center justify-center text-ink-light/40 text-sm"></span>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
