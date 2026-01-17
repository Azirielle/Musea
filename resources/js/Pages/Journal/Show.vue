<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

defineProps({
    post: Object,
});
</script>

<template>
    <Head :title="post.title" />
    <MainLayout>
        <article class="pt-32 pb-20">
            <!-- Header -->
            <div class="max-w-4xl mx-auto px-6 text-center mb-16">
                 <div class="flex items-center justify-center gap-2 mb-6 text-xs font-bold uppercase tracking-widest text-[#1A1A1A]">
                    <span>{{ new Date(post.published_at).toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' }) }}</span>
                    <span>&mdash;</span>
                    <span>{{ post.author_name || (post.author.first_name + ' ' + post.author.last_name) }}</span>
                </div>
                
                <h1 class="text-4xl md:text-6xl font-bold text-[#1A1A1A] leading-tight mb-8 font-serif">{{ post.title }}</h1>
                
                <p v-if="post.excerpt" class="text-xl md:text-2xl text-gray-500 leading-relaxed font-light">
                    {{ post.excerpt }}
                </p>
            </div>

            <!-- Featured Image -->
            <div class="max-w-7xl mx-auto px-6 mb-16" v-if="post.image_url">
                <div class="aspect-[21/9] rounded-2xl overflow-hidden shadow-sm">
                    <img :src="post.image_url" class="w-full h-full object-cover" :alt="post.title">
                </div>
            </div>

            <!-- Content -->
            <div class="max-w-3xl mx-auto px-6 prose prose-lg prose-zinc marker:text-black">
                <div v-html="post.content"></div>
            </div>

            <!-- Back Link -->
            <div class="max-w-3xl mx-auto px-6 mt-16 pt-8 border-t border-gray-200">
                <Link href="/journal" class="inline-flex items-center gap-2 font-bold hover:text-gray-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Journal
                </Link>
            </div>
        </article>
    </MainLayout>
</template>
