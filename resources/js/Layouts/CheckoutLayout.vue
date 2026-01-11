<script setup>
import { Link } from '@inertiajs/vue3';
import Footer from '@/Components/Footer.vue';
</script>

<template>
    <div class="min-h-screen bg-[#FAFAFA] flex flex-col">
        <!-- Minimal Header -->
        <header class="bg-white border-b border-gray-100 py-4 px-6 md:px-12 sticky top-0 z-50">
            <div class="max-w-4xl mx-auto flex items-center justify-between">
                <!-- Logo -->
                <Link href="/" class="flex items-center gap-2 group">
                    <img src="/images/logo/Musea.png" alt="Musea" class="h-8 md:h-10 w-auto invert brightness-0 transition-transform group-hover:scale-105" />
                </Link>

                <!-- Trust Badge -->
                <div class="flex items-center gap-2 text-green-600 bg-green-50 px-3 py-1.5 rounded-full">
                    <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <span class="text-[10px] md:text-xs font-bold uppercase tracking-wider">Secure Checkout</span>
                </div>
            </div>
        </header>

        <main class="flex-1">
            <slot />
        </main>

        <div class="border-t border-gray-200">
            <div class="max-w-4xl mx-auto px-6 py-6 text-center text-xs text-gray-400">
                &copy; {{ new Date().getFullYear() }} Musea. All rights reserved.
            </div>
        </div>
    </div>
</template>
