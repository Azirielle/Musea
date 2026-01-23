<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    activeFilters: {
        type: Object,
        default: () => ({})
    },
    categories: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['update:activeFilters', 'apply', 'reset']);

const isOpen = ref(false);
const activeAccordion = ref('category'); // 'price', 'category', 'medium', etc.

const activeCount = computed(() => {
    // Logic to count active non-null/non-default filters
    let count = 0;
    Object.values(props.activeFilters).forEach(val => {
        if (val) count++;
    });
    return count;
});

const toggleDrawer = () => isOpen.value = !isOpen.value;
const toggleAccordion = (section) => activeAccordion.value = activeAccordion.value === section ? null : section;

const localFilters = ref({ ...props.activeFilters });

// Sync local with props when opening
const openDrawer = () => {
    localFilters.value = { ...props.activeFilters };
    isOpen.value = true;
};

const applyFilters = () => {
    emit('update:activeFilters', localFilters.value);
    emit('apply');
    isOpen.value = false;
};

const resetFilters = () => {
    localFilters.value = {}; // Or default structure
    emit('reset');
};
</script>

<template>
    <!-- Floating Trigger -->
    <div class="fixed bottom-24 left-1/2 transform -translate-x-1/2 z-40 md:hidden">
        <button 
            @click="openDrawer"
            class="bg-black text-white px-6 py-3 rounded-full shadow-2xl flex items-center gap-2 font-bold text-sm tracking-wide transition-transform hover:scale-105 active:scale-95"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
            </svg>
            Filters & Sort
            <span v-if="activeCount > 0" class="bg-white text-black text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-bold">
                {{ activeCount }}
            </span>
        </button>
    </div>

    <!-- Backdrop -->
    <div 
        v-if="isOpen" 
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 transition-opacity duration-300 md:hidden"
        @click="isOpen = false"
    ></div>

    <!-- Drawer -->
    <div 
        class="fixed bottom-0 left-0 w-full h-[85vh] bg-white rounded-t-3xl z-50 transform transition-transform duration-300 ease-out md:hidden flex flex-col shadow-[0_-10px_40px_rgba(0,0,0,0.1)]"
        :class="isOpen ? 'translate-y-0' : 'translate-y-full'"
    >
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
            <h3 class="text-xl font-bold font-serif italic">Filter Art</h3>
            <button @click="resetFilters" class="text-sm font-semibold text-gray-500 hover:text-black">Reset</button>
        </div>

        <!-- Body (Scrollable) -->
        <div class="flex-1 overflow-y-auto px-6 py-4 space-y-2">
            
            <!-- Category Accordion -->
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <button @click="toggleAccordion('category')" class="w-full flex items-center justify-between p-4 bg-gray-50 font-bold text-sm">
                    Category
                    <svg class="w-4 h-4 transition-transform" :class="activeAccordion === 'category' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div v-if="activeAccordion === 'category'" class="p-4 bg-white space-y-2">
                    <label v-for="cat in categories" :key="cat" class="flex items-center gap-3">
                        <input type="checkbox" :value="cat" class="rounded border-gray-300 text-black focus:ring-black">
                        <span class="text-sm">{{ cat }}</span>
                    </label>
                </div>
            </div>

            <!-- Price Accordion -->
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <button @click="toggleAccordion('price')" class="w-full flex items-center justify-between p-4 bg-gray-50 font-bold text-sm">
                    Price Range
                    <svg class="w-4 h-4 transition-transform" :class="activeAccordion === 'price' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div v-if="activeAccordion === 'price'" class="p-4 bg-white space-y-4">
                     <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="text-[10px] uppercase font-bold text-gray-400">Min</label>
                            <input type="number" placeholder="0" class="w-full mt-1 border-gray-200 rounded-lg text-sm focus:ring-black focus:border-black">
                        </div>
                        <div class="flex-1">
                             <label class="text-[10px] uppercase font-bold text-gray-400">Max</label>
                            <input type="number" placeholder="Any" class="w-full mt-1 border-gray-200 rounded-lg text-sm focus:ring-black focus:border-black">
                        </div>
                     </div>
                </div>
            </div>

             <!-- Medium Accordion -->
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <button @click="toggleAccordion('medium')" class="w-full flex items-center justify-between p-4 bg-gray-50 font-bold text-sm">
                    Medium
                    <svg class="w-4 h-4 transition-transform" :class="activeAccordion === 'medium' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div v-if="activeAccordion === 'medium'" class="p-4 bg-white">
                     <p class="text-xs text-gray-500 italic">Medium filters coming soon...</p>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="p-6 border-t border-gray-100 bg-white safe-pb">
            <button 
                @click="applyFilters"
                class="w-full bg-black text-white py-4 rounded-xl font-bold text-lg hover:bg-gray-800 transition-colors"
            >
                Show Results
            </button>
        </div>
    </div>
</template>

<style scoped>
.safe-pb {
    padding-bottom: env(safe-area-inset-bottom, 24px);
}
</style>
