<script setup>
import { ref, onMounted } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const isVisible = ref(true);
const isLeaving = ref(false);
const showContent = ref(false);

onMounted(() => {
    // Check if we've already shown the splash screen in this session
    const hasSeenSplash = sessionStorage.getItem('musea_splash_seen');
    
    if (hasSeenSplash) {
        isVisible.value = false;
        return;
    }

    // Start animation sequence
    // 1. Fade in logo (handled by CSS transition on mount + v-if)
    setTimeout(() => {
        showContent.value = true;
    }, 100);

    // 2. Hold for 0.5s + 0.5s fade in = 1s total, then slide up
    setTimeout(() => {
        isLeaving.value = true;
        // Mark as seen
        sessionStorage.setItem('musea_splash_seen', 'true');
        
        // Remove from DOM after slide up animation (0.7s)
        setTimeout(() => {
            isVisible.value = false;
        }, 700);
    }, 1500); // 0.5s fade in + 1.0s hold roughly
});
</script>

<template>
    <div 
        v-if="isVisible"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-[#F4F4F5] transition-transform duration-700 ease-[cubic-bezier(0.76,0,0.24,1)]"
        :class="{ '-translate-y-full': isLeaving }"
    >
        <div 
            class="flex flex-col items-center gap-6 transition-opacity duration-700 delay-100"
            :class="[showContent ? 'opacity-100' : 'opacity-0']"
        >
            <div class="w-24 h-24 md:w-32 md:h-32 text-ink">
                <ApplicationLogo class="w-full h-full" />
            </div>
            
            <div class="text-center space-y-2">
                <h1 class="text-2xl md:text-3xl font-serif text-ink tracking-tight">
                    The Collection
                </h1>
                <p class="text-xs md:text-sm uppercase tracking-[0.2em] text-ink-light">
                    Discover Masterpieces
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Ensure the background color matches the site theme 'bg-canvas' which is usually #F4F4F5 or #FAFAFA depending on config. 
   Using #F4F4F5 based on common Tailwind defaults for zinc-100/canvas-like colors if not explicitly defined.*/
</style>
