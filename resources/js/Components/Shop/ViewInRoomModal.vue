<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    isOpen: Boolean,
    imageUrl: String,
    framingStatus: {
        type: String,
        default: 'Unframed'
    },
    category: {
        type: String,
        default: 'Painting'
    }
});

const emit = defineEmits(['close']);

const scale = ref(50);
const pedestalType = ref('high'); // 'low', 'high', 'floor'

const isSculpture = computed(() => props.category === 'Sculpture');

// Background Image Logic
const backgroundImage = computed(() => {
    return isSculpture.value
        ? "url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?q=80&w=2070&auto=format&fit=crop')" // Floor/Corner view
        : "url('https://images.unsplash.com/photo-1554995207-c18c203602cb?q=80&w=2070&auto=format&fit=crop')"; // Wall view
});

// Frame Styles (Paintings Only)
const frameStyles = computed(() => {
    if (isSculpture.value) return {};
    switch (props.framingStatus) {
        case 'Framed':
            return {
                border: '12px solid #1a1a1a',
                boxShadow: 'inset 2px 2px 5px rgba(0,0,0,0.5), 5px 5px 15px rgba(0,0,0,0.3)'
            };
        case 'Gallery Wrap':
            return {
                border: 'none',
                boxShadow: '1px 1px 0px #ccc, 2px 2px 0px #bbb, 3px 3px 0px #aaa, 10px 10px 20px rgba(0,0,0,0.4)'
            };
        case 'Unframed':
        default:
            return {
                border: 'none',
                boxShadow: '2px 2px 8px rgba(0,0,0,0.2)'
            };
    }
});

// Pedestal Styles (Sculptures Only)
const pedestalStyles = computed(() => {
    switch (pedestalType.value) {
        case 'low': return { height: '30px', width: '160px', marginTop: '-15px' }; // Low riser
        case 'floor': return { height: '0px', width: '0px', opacity: 0 }; // Floor standing
        case 'high': 
        default: return { height: '200px', width: '140px', marginTop: '0' }; // Standard museum plinth
    }
});

// Sizing Logic
const computedStyle = computed(() => {
    if (isSculpture.value) {
        // Sculpture: Resize height based on scale (simulating 1m to 2m)
        const height = 200 + (scale.value * 3);
        return { height: `${height}px`, width: 'auto' };
    } else {
        // Painting: Resize width (existing logic)
        return { width: `${200 + (scale.value * 4)}px` };
    }
});

const closeModal = () => emit('close');
</script>

<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-sm" @click.self="closeModal">
        <button @click="closeModal" class="absolute top-6 right-6 text-white/70 hover:text-white p-2 z-50">
             <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Room Container -->
        <div class="relative w-full h-full md:w-[90vw] md:h-[90vh] bg-zinc-900 rounded-lg overflow-hidden flex flex-col shadow-2xl">
            
            <!-- Room Background -->
            <div class="flex-1 relative bg-cover bg-center overflow-hidden transition-all duration-500" 
                 :style="{ backgroundImage: backgroundImage }">
                
                <div class="absolute inset-x-0 top-0 h-32 bg-gradient-to-b from-black/20 to-transparent pointer-events-none"></div>

                <!-- VISUALIZATION AREA -->
                <!-- SCULPTURE MODE -->
                <div v-if="isSculpture" class="absolute bottom-[20%] left-1/2 transform -translate-x-1/2 flex flex-col items-center justify-end">
                     <!-- The Sculpture -->
                    <img :src="imageUrl" 
                         alt="Sculpture View" 
                         class="object-contain relative z-10 filter drop-shadow-2xl"
                         :style="computedStyle"
                    >
                    <!-- The Pedestal (Plinth) -->
                    <div class="bg-white shadow-[0_20px_50px_rgba(0,0,0,0.5)] transition-all duration-300 relative z-0"
                         :style="pedestalStyles">
                         <!-- Plinth Top Detail -->
                         <div v-if="pedestalType !== 'floor'" class="absolute -top-4 left-0 w-full h-4 bg-gray-100 transform skew-x-12 opacity-50"></div>
                    </div>
                </div>

                <!-- PAINTING MODE -->
                <div v-else class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 transition-all duration-200 ease-out"
                     :style="computedStyle">
                    <img :src="imageUrl" 
                         alt="Artwork View" 
                         class="w-full h-auto bg-white"
                         :style="frameStyles"
                    >
                </div>

                <!-- Visual Label -->
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-center pointer-events-none">
                     <span class="bg-black/40 text-white text-[10px] px-3 py-1 rounded-full backdrop-blur-md border border-white/10">
                        Viewing: {{ category }} - {{ isSculpture ? (pedestalType === 'floor' ? 'Floor Standing' : 'On Plinth') : framingStatus }}
                    </span>
                </div>
            </div>

            <!-- Controls Footer -->
            <div class="bg-white border-t border-gray-200 flex flex-col items-center justify-center py-6 px-8 z-10 gap-4">
                
                <!-- Sculpture Controls -->
                <div v-if="isSculpture" class="flex gap-2 mb-2">
                    <button @click="pedestalType = 'high'" :class="pedestalType === 'high' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600'" class="px-3 py-1 text-xs font-bold rounded-full transition-colors">High Plinth</button>
                    <button @click="pedestalType = 'low'" :class="pedestalType === 'low' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600'" class="px-3 py-1 text-xs font-bold rounded-full transition-colors">Low Riser</button>
                    <button @click="pedestalType = 'floor'" :class="pedestalType === 'floor' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600'" class="px-3 py-1 text-xs font-bold rounded-full transition-colors">Floor Standing</button>
                </div>

                <!-- Scale Slider -->
                <div class="w-full max-w-md space-y-2">
                    <div class="flex justify-between text-xs font-bold text-gray-500 uppercase tracking-wider">
                        <span>Small</span>
                        <span>Adjust Scale</span>
                        <span>Large</span>
                    </div>
                    <input type="range" min="10" max="100" v-model="scale" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-black">
                </div>
            </div>
        </div>
    </div>
</template>
