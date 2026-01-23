<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    status: Number,
    message: String
});

const title = computed(() => {
    return {
        503: 'Service Unavailable',
        500: 'Server Error',
        404: 'Page Not Found',
        403: 'Forbidden',
    }[props.status] || 'Error';
});

const description = computed(() => {
    return {
        503: 'Sorry, we are doing some maintenance. Please check back soon.',
        500: 'Whoops, something went wrong on our servers.',
        404: 'Sorry, the page you are looking for could not be found.',
        403: 'Sorry, you are forbidden from accessing this page.',
    }[props.status] || props.message || 'An unexpected error occurred.';
});
</script>

<template>
    <Head :title="title" />
    
    <div class="min-h-screen bg-canvas flex flex-col items-center justify-center p-6 text-center">
        <!-- Abstract Shape/Illustration -->
        <div class="relative w-64 h-64 mb-12">
             <div class="absolute inset-0 bg-ink rounded-full blur-3xl opacity-10 animate-pulse"></div>
             <div class="relative z-10 w-full h-full bg-paper rounded-[3rem] border border-divider shadow-xl flex items-center justify-center transform rotate-3 hover:rotate-6 transition-transform duration-500">
                <span class="text-9xl font-serif font-black text-ink">{{ status }}</span>
             </div>
        </div>

        <h1 class="text-4xl md:text-6xl font-serif font-bold text-ink mb-6 italic">{{ title }}</h1>
        <p class="text-lg text-ink-light max-w-md mb-12">{{ description }}</p>

        <div class="flex gap-4">
            <Link 
                href="/" 
                class="px-8 py-3 bg-ink text-white rounded-full font-bold text-sm tracking-widest hover:bg-accent transition-all duration-300 uppercase shadow-lg hover:shadow-accent/25"
            >
                Return Home
            </Link>
             <button 
                @click="$window.history.back()" 
                class="px-8 py-3 bg-white border border-divider text-ink rounded-full font-bold text-sm tracking-widest hover:bg-zinc-50 transition-all duration-300 uppercase"
            >
                Go Back
            </button>
        </div>
    </div>
</template>
