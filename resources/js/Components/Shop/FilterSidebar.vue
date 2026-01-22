<script setup>
import { ref, watch, computed } from 'vue';

const props = defineProps({
    filters: Object,
    artists: Array, // [{id, name}]
    minPrice: { type: Number, default: 0 },
    maxPrice: { type: Number, default: 100000 }
});

const emit = defineEmits(['update']);

// Local state initialized from props
const priceRange = ref([
    props.filters.price_min ? parseInt(props.filters.price_min) : props.minPrice, 
    props.filters.price_max ? parseInt(props.filters.price_max) : props.maxPrice
]);

import { categories } from '@/Constants/Categories';

// ... (props defined above, keep checks)

// New Refs
const selectedCategory = ref(
    Array.isArray(props.filters.category) ? props.filters.category : (props.filters.category ? [props.filters.category] : [])
);
const selectedStyle = ref(
    Array.isArray(props.filters.style) ? props.filters.style : (props.filters.style ? [props.filters.style] : [])
);
const selectedSubject = ref(
    Array.isArray(props.filters.subject) ? props.filters.subject : (props.filters.subject ? [props.filters.subject] : [])
);
const selectedMedium = ref(
    Array.isArray(props.filters.medium) ? props.filters.medium : (props.filters.medium ? [props.filters.medium] : [])
);
const selectedFraming = ref(
    Array.isArray(props.filters.framing) ? props.filters.framing : (props.filters.framing ? [props.filters.framing] : [])
);
const readyToHang = ref(
    props.filters.ready_to_hang ? true : false
);
const selectedOrientation = ref(
    Array.isArray(props.filters.orientation) ? props.filters.orientation : (props.filters.orientation ? [props.filters.orientation] : [])
);
const selectedArtists = ref(
    Array.isArray(props.filters.artist_id) ? props.filters.artist_id : (props.filters.artist_id ? [props.filters.artist_id] : [])
);
const selectedSize = ref(
    Array.isArray(props.filters.size) ? props.filters.size : (props.filters.size ? [props.filters.size] : [])
);
const isUpdating = ref(false);

const applyFilters = () => {
    const payload = {
        price_min: priceRange.value[0],
        price_max: priceRange.value[1],
        category: selectedCategory.value,
        // subcategory: selectedSubcategory.value, // Removed/Legacy
        style: selectedStyle.value,
        subject: selectedSubject.value,
        medium: selectedMedium.value,
        framing: selectedFraming.value,
        ready_to_hang: readyToHang.value,
        orientation: selectedOrientation.value,
        artist_id: selectedArtists.value,
        size: selectedSize.value
    };
    
    emit('update', payload);
};

// Update watch logic to sync refs from props
watch(() => props.filters, (newFilters) => {
    const getArray = (val) => Array.isArray(val) ? val : (val ? [val] : []);
    
    selectedCategory.value = getArray(newFilters.category);
    selectedStyle.value = getArray(newFilters.style);
    selectedSubject.value = getArray(newFilters.subject);
    selectedMedium.value = getArray(newFilters.medium);
    selectedFraming.value = getArray(newFilters.framing);
    readyToHang.value = newFilters.ready_to_hang ? true : false;
    selectedOrientation.value = getArray(newFilters.orientation);
    selectedArtists.value = getArray(newFilters.artist_id);
    selectedSize.value = getArray(newFilters.size);
}, { deep: true });

watch([selectedCategory, selectedStyle, selectedSubject, selectedMedium, selectedFraming, readyToHang, selectedOrientation, selectedArtists, selectedSize], () => {
    if (!isUpdating.value) {
        applyFilters();
    }
}, { deep: true });


</script>

<template>
    <div class="space-y-6">
        <!-- Price Filter -->
        <details open class="group">
            <summary class="flex items-center justify-between cursor-pointer list-none">
                <h3 class="font-bold text-ink hover:text-accent transition-colors">Price Range</h3>
                <span class="text-ink-light transition-transform group-open:rotate-180">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </span>
            </summary>
            
            <div class="pt-4 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-ink-light font-medium">₱{{ priceRange[0] }} - ₱{{ priceRange[1] }}</span>
                </div>
                <div class="relative pt-2">
                    <input 
                        type="range" 
                        v-model="priceRange[1]" 
                        :min="minPrice" 
                        :max="maxPrice" 
                        step="1000"
                        class="w-full h-1 bg-divider rounded-lg appearance-none cursor-pointer accent-accent"
                    >
                    <p class="text-[11px] font-bold text-ink-light mt-2 text-right uppercase tracking-wider">Adjust budget</p>
                </div>
                
                <!-- Min/Max Inputs -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] uppercase font-bold text-ink-light tracking-widest">Min</label>
                        <div class="relative flex items-center">
                            <span class="absolute left-3 text-ink-light text-xs">₱</span>
                            <input 
                                type="number" 
                                v-model.number="priceRange[0]"
                                class="w-full pl-7 pr-2 py-2 bg-canvas border border-divider rounded-lg text-sm focus:outline-none focus:border-accent"
                                placeholder="0"
                            >
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] uppercase font-bold text-ink-light tracking-widest">Max</label>
                        <div class="relative flex items-center">
                            <span class="absolute left-3 text-ink-light text-xs">₱</span>
                            <input 
                                type="number" 
                                v-model.number="priceRange[1]"
                                class="w-full pl-7 pr-2 py-2 bg-canvas border border-divider rounded-lg text-sm focus:outline-none focus:border-accent"
                                placeholder="100000"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </details>

        <div class="h-px bg-divider"></div>

        <!-- Size Filter -->
        <details class="group">
            <summary class="flex items-center justify-between cursor-pointer list-none">
                <h3 class="font-bold text-ink hover:text-accent transition-colors">Size</h3>
                <span class="text-ink-light transition-transform group-open:rotate-180">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </span>
            </summary>
            
            <div class="pt-4 space-y-2">
                 <label v-for="size in ['Small', 'Medium', 'Large', 'Extra Large']" :key="size" class="flex items-center gap-3 cursor-pointer group">
                    <div class="relative flex items-center">
                         <input type="checkbox" :value="size" v-model="selectedSize" class="peer h-4 w-4 border-2 border-divider rounded text-accent focus:ring-accent/20 cursor-pointer transition-all checked:border-accent">
                    </div>
                    <span class="text-sm text-ink-light group-hover:text-ink transition-colors">{{ size }}</span>
                </label>
                 <p class="text-[10px] text-ink-light text-right pt-2 italic opacity-60">Based on longest side</p>
            </div>
        </details>

        <div class="h-px bg-divider"></div>

        <!-- Category Filter -->
        <details open class="group">
            <summary class="flex items-center justify-between cursor-pointer list-none">
                <h3 class="font-bold text-ink hover:text-accent transition-colors">Category</h3>
                <span class="text-ink-light transition-transform group-open:rotate-180">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </span>
            </summary>
            
            <div class="pt-4 space-y-2">
                 <label v-for="(opts, cat) in categories" :key="cat" class="flex items-center gap-3 cursor-pointer group/cat">
                    <div class="relative flex items-center">
                         <input type="checkbox" :value="cat" v-model="selectedCategory" class="peer h-4 w-4 border-2 border-divider rounded text-accent focus:ring-accent/20 cursor-pointer transition-all checked:border-accent">
                    </div>
                    <span class="text-sm font-bold text-ink-light group-hover/cat:text-ink transition-colors">{{ cat }}</span>
                </label>
            </div>
        </details>

        <div class="h-px bg-divider"></div>

        <!-- Dynamic Sub-Filters (Only if EXACTLY ONE category is selected) -->
        <template v-if="selectedCategory.length === 1 && categories[selectedCategory[0]]">
            <template v-for="(options, key) in categories[selectedCategory[0]]" :key="key">
                <details open class="group">
                    <summary class="flex items-center justify-between cursor-pointer list-none">
                        <h3 class="font-bold text-ink hover:text-accent transition-colors">{{ key === 'Method' && selectedCategory[0] === 'Sculpture' ? 'Method' : (key === 'Medium' ? 'Medium' : key) }}</h3>
                        <span class="text-ink-light transition-transform group-open:rotate-180">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </span>
                    </summary>
                    
                    <div class="pt-4 space-y-2 max-h-48 overflow-y-auto custom-scrollbar">
                         <label v-for="opt in options" :key="opt" class="flex items-center gap-3 cursor-pointer group/opt">
                            <div class="relative flex items-center">
                                 <!-- We map specific keys to specific refs for simplicity, or we check key name -->
                                 <input 
                                    type="checkbox" 
                                    :value="opt" 
                                    :checked="
                                        key === 'Style' ? selectedStyle.includes(opt) :
                                        key === 'Subject' ? selectedSubject.includes(opt) :
                                        (key === 'Medium' || key === 'Method') ? selectedMedium.includes(opt) : false
                                    "
                                    @change="(e) => {
                                        const val = opt;
                                        const checked = e.target.checked;
                                        if (key === 'Style') selectedStyle = checked ? [...selectedStyle, val] : selectedStyle.filter(i => i !== val);
                                        else if (key === 'Subject') selectedSubject = checked ? [...selectedSubject, val] : selectedSubject.filter(i => i !== val);
                                        else if (key === 'Medium' || key === 'Method') selectedMedium = checked ? [...selectedMedium, val] : selectedMedium.filter(i => i !== val);
                                    }"
                                    class="peer h-3.5 w-3.5 border border-divider rounded text-accent focus:ring-accent/20 cursor-pointer transition-all checked:border-accent"
                                >
                            </div>
                            <span class="text-xs text-ink-light group-hover/opt:text-ink transition-colors">{{ opt }}</span>
                        </label>
                    </div>
                </details>

                <div class="h-px bg-divider"></div>
            </template>
        </template>

        <div class="h-px bg-divider"></div>

        <!-- Details Filter -->
        <details class="group">
            <summary class="flex items-center justify-between cursor-pointer list-none">
                <h3 class="font-bold text-ink hover:text-accent transition-colors">Details</h3>
                <span class="text-ink-light transition-transform group-open:rotate-180">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </span>
            </summary>
            
            <div class="pt-4 space-y-4">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <div class="relative flex items-center">
                        <input type="checkbox" v-model="readyToHang" class="peer h-4 w-4 border-2 border-divider rounded text-accent focus:ring-accent/20 cursor-pointer transition-all checked:border-accent">
                    </div>
                    <span class="text-sm text-ink group-hover:text-accent transition-colors">Ready to Hang</span>
                </label>

                <div class="space-y-2 pt-2">
                    <p class="text-[10px] font-bold text-ink-light uppercase tracking-wider">Framing</p>
                    <label v-for="frame in ['Framed', 'Unframed', 'Gallery Wrap']" :key="frame" class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative flex items-center">
                             <input type="checkbox" :value="frame" v-model="selectedFraming" class="peer h-4 w-4 border-2 border-divider rounded text-accent focus:ring-accent/20 cursor-pointer transition-all checked:border-accent">
                        </div>
                        <span class="text-sm text-ink-light group-hover:text-ink transition-colors">{{ frame }}</span>
                    </label>
                </div>
            </div>
        </details>

        <div class="h-px bg-divider"></div>

        <!-- Orientation Filter -->
        <details class="group">
            <summary class="flex items-center justify-between cursor-pointer list-none">
                <h3 class="font-bold text-ink hover:text-accent transition-colors">Orientation</h3>
                <span class="text-ink-light transition-transform group-open:rotate-180">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </span>
            </summary>
            
            <div class="pt-4">
                <div class="flex gap-2">
                    <button 
                        v-for="orient in ['landscape', 'portrait', 'square']" 
                        :key="orient"
                        @click="selectedOrientation.includes(orient) ? selectedOrientation = selectedOrientation.filter(o => o !== orient) : selectedOrientation.push(orient)"
                        class="flex-1 aspect-square rounded-lg border flex flex-col items-center justify-center gap-2 transition-all p-2"
                        :class="selectedOrientation.includes(orient) ? 'bg-ink text-white border-ink' : 'bg-transparent text-ink-light border-divider hover:border-ink hover:text-ink'"
                        :title="orient"
                    >
                        <!-- Icons -->
                        <div v-if="orient === 'landscape'" class="w-8 h-5 border-2 border-current rounded-sm"></div>
                        <div v-if="orient === 'portrait'" class="w-5 h-8 border-2 border-current rounded-sm"></div>
                        <div v-if="orient === 'square'" class="w-7 h-7 border-2 border-current rounded-sm"></div>
                        <span class="text-[9px] font-bold uppercase tracking-tighter">{{ orient }}</span>
                    </button>
                </div>
            </div>
        </details>

        <div class="h-px bg-divider"></div>

        <!-- Artist Filter -->
        <details class="group">
            <summary class="flex items-center justify-between cursor-pointer list-none">
                <h3 class="font-bold text-ink hover:text-accent transition-colors">Artists</h3>
                <span class="text-ink-light transition-transform group-open:rotate-180">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </span>
            </summary>
            
            <div class="pt-4 space-y-4">
                <input 
                    type="text" 
                    v-model="artistSearch" 
                    placeholder="Find an artist..." 
                    class="w-full text-xs px-3 py-2 bg-canvas border border-divider rounded-lg focus:outline-none focus:border-accent transition-all"
                >
                <div class="space-y-2 max-h-48 overflow-y-auto custom-scrollbar pr-2">
                    <label v-for="artist in filteredArtists" :key="artist.id" class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" :value="artist.id" v-model="selectedArtists" class="peer h-4 w-4 border-2 border-divider rounded text-accent focus:ring-accent/20 cursor-pointer transition-all checked:border-accent">
                        <span class="text-sm text-ink-light group-hover:text-ink transition-colors truncate">{{ artist.name }}</span>
                    </label>
                </div>
            </div>
        </details>
    </div>
</template>
