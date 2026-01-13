<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';

const isLoading = ref(false);
const show = ref(false); // Controls the transition visibility
let timeout = null;

const startLoading = () => {
    isLoading.value = true;
    // Small delay to prevent flashing on super fast loads
    timeout = setTimeout(() => {
        show.value = true;
    }, 100);
};

const finishLoading = () => {
    clearTimeout(timeout);
    show.value = false;
    // Wait for transition to finish before hiding completely
    setTimeout(() => {
        isLoading.value = false;
    }, 500); // Match transition duration
};

onMounted(() => {
    router.on('start', startLoading);
    router.on('finish', finishLoading);
});

onUnmounted(() => {
    // Clean up listeners to avoid memory leaks if component is destroyed
    // Note: Inertia event listeners don't return a cleanup function directly in all versions, 
    // but this component is intended to be persistent or top-level.
});
</script>

<template>
    <Teleport to="body">
        <Transition name="fade">
            <div v-if="isLoading && show" class="fixed inset-0 z-[9999] flex items-center justify-center bg-canvas/90 backdrop-blur-sm">
                <div class="relative flex flex-col items-center">
                    <!-- Art Animation Container -->
                    <div class="relative w-24 h-24 sm:w-32 sm:h-32">
                        <!-- Paint Drops Animation -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="drop drop-1"></div>
                            <div class="drop drop-2"></div>
                            <div class="drop drop-3"></div>
                        </div>
                    </div>
                    
                    <!-- Loading Text -->
                    <p class="mt-4 text-accent font-serif tracking-widest text-sm sm:text-base animate-pulse">
                        MUSEA
                    </p>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.drop {
    position: absolute;
    border-radius: 50%;
    opacity: 0.8;
    mix-blend-mode: multiply;
    animation: float 3s infinite ease-in-out;
}

.drop-1 {
    width: 60%;
    height: 60%;
    background-color: #8B7355; /* Accent */
    top: 0;
    left: 20%;
    animation-delay: 0s;
}

.drop-2 {
    width: 50%;
    height: 50%;
    background-color: #1A1A1A; /* Ink */
    bottom: 0;
    left: 0;
    animation-delay: -1s;
}

.drop-3 {
    width: 55%;
    height: 55%;
    background-color: #CBA35C; /* Light Accent/Gold */
    bottom: 10%;
    right: 0;
    animation-delay: -2s;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0) scale(1);
        border-radius: 50%;
    }
    33% {
        transform: translateY(-10px) scale(1.1);
        border-radius: 40% 60% 60% 40% / 40% 50% 50% 60%;
    }
    66% {
        transform: translateY(5px) scale(0.95);
        border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
    }
}

/* Transition Classes */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.4s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
