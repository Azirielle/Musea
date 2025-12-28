<script setup>
import { ref, onMounted, onUnmounted, computed, nextTick } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    title: String,
    id: String,
    items: Array
});

const currentSlide = ref(0);
const windowWidth = ref(1200);
const isResetting = ref(false);
let intervalId = null;

// Duplicate items to create infinite loop illusion
const displayItems = computed(() => {
    // If we have enough items, duplicate. If very few (1?), maybe not needed? 
    // Assuming at least a few items.
    return [...props.items, ...props.items];
});

const itemsToShow = computed(() => {
    if (windowWidth.value < 520) return 1;
    if (windowWidth.value < 900) return 2;
    return 5;
});

// Gap is 12px
const slideStyle = computed(() => {
    if (itemsToShow.value === 1) return { width: '80%' }; // Original logic
    return { width: `calc((100% - ${(itemsToShow.value - 1) * 12}px) / ${itemsToShow.value})` };
});

const trackTransform = computed(() => {
    let gap = 12; // px
    
    if (itemsToShow.value === 1) {
         return `translateX(calc(-${currentSlide.value} * (80% + 12px)))`;
    }
    return `translateX(calc(-${currentSlide.value} * ((100% + 12px) / ${itemsToShow.value})))`;
});

const next = () => {
    if (currentSlide.value < displayItems.value.length - itemsToShow.value) {
        currentSlide.value++;
        
        // Check if we reached the start of the duplicated set (which matches start of original set)
        // Original set length is props.items.length.
        // If currentSlide == props.items.length, we are visually at the start (0).
        if (currentSlide.value === props.items.length) {
            // Wait for transition to finish (500ms), then reset silently
             resetTimer(); // Reset timer so we don't auto-advance while resetting
             setTimeout(() => {
                isResetting.value = true;
                currentSlide.value = 0;
                // Force layout/tick
                nextTick(() => {
                     // Small delay to ensure CSS applied without transition
                     setTimeout(() => {
                        isResetting.value = false;
                     }, 50);
                });
             }, 500);
             return; // Don't reset timer again in main path immediately
        }
    } else {
        // Should not happen with above logic, but safety:
        currentSlide.value = 0;
    }
    resetTimer();
};

const prev = () => {
    if (currentSlide.value > 0) {
        currentSlide.value--;
    } else {
        // We are at 0. We want to go to props.items.length - 1 (end of first set)
        // But visually we want to appear to come from left?
        // Actually, if we are at 0, we can silently jump to props.items.length (which is same visual),
        // THEN animate to props.items.length - 1.
        
        isResetting.value = true;
        currentSlide.value = props.items.length;
        
        nextTick(() => {
            setTimeout(() => {
                isResetting.value = false;
                currentSlide.value--;
            }, 50);
        });
        resetTimer();
        return;
    }
    resetTimer();
};

const startTimer = () => {
    stopTimer();
    intervalId = setInterval(next, 3000);
};

const stopTimer = () => {
    if (intervalId) clearInterval(intervalId);
};

const resetTimer = () => {
    stopTimer();
    // Only restart if not hovering? The mouseleave handles restart usually.
    // If called from click, we should restart.
    // However, if mouse is over, hover will stop it anyway.
    if (!document.querySelector(`#${props.id}:hover`)) {
         startTimer();
    }
};

const updateWidth = () => {
    windowWidth.value = window.innerWidth;
};

onMounted(() => {
    updateWidth();
    startTimer();
    window.addEventListener('resize', updateWidth);
});

onUnmounted(() => {
    stopTimer();
    window.removeEventListener('resize', updateWidth);
});
</script>

<template>
    <section class="category-section reveal" :id="id" @mouseenter="stopTimer" @mouseleave="startTimer">
        <h2 style="text-align:center;margin-bottom:18px;">{{ title }}</h2>
        <div class="category-carousel relative group max-w-[1100px] mx-auto">
            <button @click="prev" class="gallery-btn absolute left-2 top-1/2 -translate-y-1/2 z-10 bg-white/90 p-2 h-10 w-10 flex items-center justify-center rounded-full shadow-md hover:bg-white text-xl transition opacity-0 group-hover:opacity-100">‹</button>
            <button @click="next" class="gallery-btn absolute right-2 top-1/2 -translate-y-1/2 z-10 bg-white/90 p-2 h-10 w-10 flex items-center justify-center rounded-full shadow-md hover:bg-white text-xl transition opacity-0 group-hover:opacity-100">›</button>
            
            <div class="category-viewport overflow-hidden mx-12 rounded-xl">
                <div 
                    class="category-track flex gap-[12px] py-4"
                    :class="{ 'transition-transform duration-500 ease-out': !isResetting }"
                    :style="{ transform: trackTransform }"
                >
                    <div 
                        v-for="(item, index) in displayItems" 
                        :key="index" 
                        class="category-slide flex-none bg-paper rounded-xl border border-divider overflow-hidden hover:-translate-y-1 hover:shadow-lg transition duration-300"
                        :style="slideStyle"
                    >
                        <div class="cat-overlay flex flex-col h-full">

                            <div class="img-container h-[200px] md:h-[260px] bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center p-4">
                                <img :src="item.image" :alt="item.title" class="max-w-full max-h-full object-contain drop-shadow-sm">
                            </div>
                            <div class="slide-info p-3 text-center bg-white flex flex-col flex-1 justify-between">
                                <div>
                                    <h4 class="font-bold text-[#1A1A1A] mb-1 truncate text-sm md:text-base">{{ item.title }}</h4>
                                    <p class="text-xs text-gray-500 mb-2">{{ item.artist }} • ₱{{ item.price }}</p>
                                </div>
                                <div class="slide-actions flex justify-center gap-2 mt-2">
                                    <Link :href="route('shop.show', { artwork: item.id || 1 })" class="btn-ghost border border-ink text-ink px-3 py-1 rounded text-xs hover:bg-ink hover:text-white transition uppercase tracking-wide">View</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.category-section {
    padding: 70px 0;
    position: relative;
    content-visibility: auto;
}
.category-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80%;
    height: 2px;
    background: linear-gradient(90deg, transparent, rgba(203, 163, 92, 0.3), transparent);
}
.category-section h2 {
    font-size: 2.2em;
    font-weight: 800;
    letter-spacing: -0.5px;
    background: linear-gradient(135deg, #1A1A1A 0%, #4A4A4A 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    color: #1A1A1A;
    position: relative;
    display: inline-block;
    width: 100%;
}
.category-section h2::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #18181B, transparent);
    border-radius: 2px;
}
</style>
