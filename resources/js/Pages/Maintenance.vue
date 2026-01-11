<script setup>
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Under Maintenance | Musea" />

    <div class="min-h-screen bg-canvas flex flex-col items-center justify-center p-6 text-center">
        <!-- Logo -->
        <div class="mb-12 animate-pulse">
            <img src="/images/logo/Musea.png" alt="Musea" class="h-16 w-auto mx-auto grayscale opacity-50">
        </div>

        <!-- Content -->
        <div class="max-w-md space-y-6">
            <h1 class="text-4xl md:text-5xl font-serif text-ink italic font-light italic">Art takes time.</h1>
            <p class="text-ink-light text-lg">
                We're currently curating some updates to enhance your experience. 
                Musea will be back shortly with more masterpieces.
            </p>
            
            <div class="h-px bg-divider w-24 mx-auto"></div>

            <div class="pt-4">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-ink-light/40">
                    Est. 2024 • Private Collection
                </p>
            </div>
        </div>

        <!-- Floating decorative elements (Optional, subtle) -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden opacity-20">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-accent/5 rounded-full blur-[100px]"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-accent/5 rounded-full blur-[100px]"></div>
        </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap');

.font-serif {
    font-family: 'Playfair Display', serif;
}
</style>
