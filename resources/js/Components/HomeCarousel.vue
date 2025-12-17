<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    title: String,
    id: String,
    items: Array
});

const currentSlide = ref(0);
const windowWidth = ref(1200); // Default
let intervalId = null;

const itemsToShow = computed(() => {
    if (windowWidth.value < 520) return 1;
    if (windowWidth.value < 900) return 2;
    return 5;
});

// Calculate width percentage and gap compensation
// 5 items: (100% - 4*12px) / 5 ... Gap is 12px
const slideStyle = computed(() => {
    if (itemsToShow.value === 1) return { width: '80%' };
    return { width: `calc((100% - ${(itemsToShow.value - 1) * 12}px) / ${itemsToShow.value})` };
});

const trackTransform = computed(() => {
    // We move by one slide width + gap
    // Slide Width % = 100 / itemsToShow roughly, but let's be precise with pixels/calc
    // Easier approach: Move by 100% / itemsToShow + gap proportion?
    
    // Let's use the same formula: -currentSlide * (SlideWidth + Gap)
    // SlideWidth = (100% - (items * gap)) / items ??? No, too complex string
    
    // Simplest: just assume equal distribution.
    // If 5 items: move 20% + gap adjustment.
    
    let percent = 0;
    let gap = 12; // px
    
    if (itemsToShow.value === 5) percent = 20;
    else if (itemsToShow.value === 2) percent = 50;
    else percent = 80; // Mobile single view often centered or just 100%
    
    if (itemsToShow.value === 1) {
        // For mobile, maybe just 80% width + gap?
        return `translateX(calc(-${currentSlide.value} * (80% + 12px)))`;
    }
    
    return `translateX(calc(-${currentSlide.value} * (${100 / itemsToShow.value}% + ${gap / itemsToShow.value}px)))`; 
    // Actually simpler: calc(-Index * (100% / Items + Gap)) ?
    // Let's stick to the previous calc which was working for desktop: 
    // calc(-${currentSlide} * (20% + 2.4px)) -> 2.4 is 12/5.
    
    return `translateX(calc(-${currentSlide.value} * ((100% + 12px) / ${itemsToShow.value})))`;
});


const next = () => {
    if (currentSlide.value < props.items.length - 1) {
        currentSlide.value++;
    } else {
        currentSlide.value = 0;
    }
    resetTimer();
};

const prev = () => {
    if (currentSlide.value > 0) {
        currentSlide.value--;
    } else {
        currentSlide.value = props.items.length - 1;
    }
    resetTimer();
};

const startTimer = () => {
    intervalId = setInterval(next, 3000);
};

const stopTimer = () => {
    if (intervalId) clearInterval(intervalId);
};

const resetTimer = () => {
    stopTimer();
    startTimer();
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
                    class="category-track flex gap-[12px] transition-transform duration-500 ease-out py-4"
                    :style="{ transform: trackTransform }"
                >
                    <div 
                        v-for="(item, index) in items" 
                        :key="index" 
                        class="category-slide flex-none bg-white rounded-xl border-2 border-[#CBA35C]/10 overflow-hidden hover:-translate-y-1 hover:shadow-lg transition duration-300"
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
                                    <Link :href="route('shop.show', { artwork: 1 })" class="btn-ghost border border-[#1A1A1A] text-[#1A1A1A] px-3 py-1 rounded text-xs hover:bg-[#1A1A1A] hover:text-white transition uppercase tracking-wide">View</Link>
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
    background: linear-gradient(90deg, #CBA35C, transparent);
    border-radius: 2px;
}
</style>
