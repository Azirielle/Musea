<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

defineProps({
    artists: Object
});
</script>

<template>
    <Head title="Our Artists" />
    <MainLayout>
        <div class="pt-24 pb-12 px-6 bg-canvas">
            <div class="max-w-7xl mx-auto">
                <h1 class="text-4xl font-bold text-ink mb-8 text-center">Meet the Artists</h1>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    <div v-for="artist in artists.data" :key="artist.id" class="bg-white rounded-xl p-6 shadow-sm text-center hover:shadow-md transition">
                         <div class="w-24 h-24 mx-auto bg-gray-200 rounded-full mb-4 overflow-hidden">
                             <!-- Placeholder avatar if no image -->
                            <img 
                                :src="artist.avatar_path ? (artist.avatar_path.startsWith('http') ? artist.avatar_path : '/storage/' + artist.avatar_path) : `https://ui-avatars.com/api/?name=${artist.first_name}+${artist.last_name}&background=CBA35C&color=fff`" 
                                alt="Avatar" 
                                class="w-full h-full object-cover"
                            >
                         </div>
                         <h3 class="font-bold text-lg text-[#1A1A1A]">{{ artist.first_name }} {{ artist.last_name }}</h3>
                         <p class="text-sm text-gray-500 mb-4">{{ artist.email }}</p>
                         <Link href="#" class="text-accent font-medium hover:underline">View Collection</Link>
                    </div>
                </div>

                <!-- Pagination -->
                 <div class="mt-12 flex justify-center gap-2" v-if="artists.links.length > 3">
                     <template v-for="(link, k) in artists.links" :key="k">
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
