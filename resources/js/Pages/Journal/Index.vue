<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

defineProps({
    posts: Object,
});
</script>

<template>
    <Head title="Journal" />
    <MainLayout>
        <div class="py-20 px-6 max-w-7xl mx-auto">
            <div class="text-center mb-16">
                 <h1 class="text-5xl font-bold mb-6 text-[#1A1A1A]">Musea Journal</h1>
                 <p class="text-xl text-gray-500 max-w-2xl mx-auto">Stories, interviews, and insights from the studio and beyond.</p>
            </div>
            
            <div v-if="posts.data.length === 0" class="text-center py-20 text-gray-400 italic">
                No stories published yet. Check back soon.
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 text-left">
                <Link 
                    v-for="post in posts.data" 
                    :key="post.id" 
                    :href="route('journal.show', post.slug)"
                    class="group"
                >
                    <div class="bg-gray-100 rounded-2xl h-64 overflow-hidden mb-6 relative">
                         <img 
                            v-if="post.image_url" 
                            :src="post.image_url" 
                            loading="lazy"
                            class="w-full h-full object-cover transition duration-500 group-hover:scale-105 will-change-transform"
                         >
                         <div v-else class="w-full h-full bg-zinc-200 flex items-center justify-center text-gray-400">
                            Musea
                         </div>
                    </div>
                    
                    <div class="flex items-center gap-2 mb-3 text-xs font-bold uppercase tracking-wider text-gray-500">
                        <span>{{ new Date(post.published_at).toLocaleDateString() }}</span>
                        <span>&bull;</span>
                        <span>{{ post.author.first_name }} {{ post.author.last_name }}</span>
                    </div>

                    <h3 class="font-bold text-2xl mb-3 group-hover:underline decoration-2 underline-offset-4">{{ post.title }}</h3>
                    <p class="text-gray-600 line-clamp-3 leading-relaxed">{{ post.excerpt }}</p>
                </Link>
            </div>

            <!-- Pagination -->
            <div class="mt-20 flex justify-center gap-2" v-if="posts.links.length > 3">
                 <template v-for="(link, k) in posts.links" :key="k">
                    <Link 
                        v-if="link.url" 
                        :href="link.url" 
                        v-html="link.label"
                        class="px-4 py-2 rounded-lg border text-sm font-medium transition"
                        :class="{'bg-black text-white border-black': link.active, 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300': !link.active}"
                    />
                    <span v-else v-html="link.label" class="px-4 py-2 text-gray-400 text-sm"></span>
                 </template>
            </div>
        </div>
    </MainLayout>
</template>
