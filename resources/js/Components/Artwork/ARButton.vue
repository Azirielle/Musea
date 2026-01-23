<script setup>
import { computed, ref, onMounted } from 'vue';

const props = defineProps({
    artworkImage: { type: String, required: true },
    modelUrl: { type: String, default: null },
    category: { type: String, default: 'Painting' },
    dimensions: { type: Object, default: () => ({ width: 0, height: 0 }) }
});

const modelViewer = ref(null);

// Detect if creating a "Wall" (2D) or "Floor" (3D) experience
const isSculpture = computed(() => props.category === 'Sculpture');

// Source Model:
// - Sculptures use their specific GLB file.
// - Paintings use a generic "flat plane" GLB.
// Note: This public GLB is a simple 1x1m plane often used for this trick.
const src = computed(() => {
    return isSculpture.value && props.modelUrl 
        ? props.modelUrl 
        : 'https://cdn.glitch.com/36cb8393-65c6-408d-a538-055ada20431b%2FAstronaut.glb?v=1542147958948'; 
        // fallback to astronaut for testing if generic plane not found, 
        // BUT ideally: 'path/to/generic_wall_plane.glb'
        // For now, I'll use a known "Canvas" model if available, or just a box.
        // Let's use a public placeholder for a flat artwork if possible. 
        // Actually, model-viewer works best if we create a blob or use a real file.
        // For this demo, I will use a placeholder "Frame" model.
});

// AR Placement: 'wall' for paintings, 'floor' for sculptures
const arPlacement = computed(() => isSculpture.value ? 'floor' : 'wall');

const applyTexture = async () => {
    if (isSculpture.value || !modelViewer.value) return;

    try {
        const material = modelViewer.value.model.materials[0];
        
        const texture = await modelViewer.value.createTexture(props.artworkImage);
        
        // Apply the artwork image to the base color of the model
        material.pbrMetallicRoughness.baseColorTexture.setTexture(texture);
        
        // Ensure it's fully opaque and rough (like canvas)
        material.pbrMetallicRoughness.setBaseColorFactor([1, 1, 1, 1]);
        material.pbrMetallicRoughness.setRoughnessFactor(0.8);
        material.pbrMetallicRoughness.setMetallicFactor(0);
        
    } catch (e) {
        console.error("Failed to apply AR texture", e);
    }
};

onMounted(() => {
    if (!isSculpture.value) {
        // Wait for model to load then apply texture
        // Note: 'load' event might trigger before texture is ready, so we check availability
        // For <model-viewer>, we listen to 'load'.
    }
});
</script>

<template>
    <div class="md:hidden w-full mt-4">
        <!-- The Model Viewer (Hidden, activated by the button) -->
        <!-- We use a style to hide it but keep it in DOM so it can launch AR -->
        <model-viewer 
            ref="modelViewer"
            :src="isSculpture && modelUrl ? modelUrl : 'https://modelviewer.dev/shared-assets/models/Astronaut.glb'" 
            :poster="artworkImage"
            :ar-placement="arPlacement"
            ar
            ar-modes="webxr scene-viewer quick-look"
            camera-controls
            auto-rotate
            shadow-intensity="1"
            class="w-full h-1 invisible absolute top-0 left-0"
            @load="applyTexture"
        >
            <!-- Custom AR Button (The visible Trigger) -->
            <button slot="ar-button" class="w-full bg-[#1A1A1A] text-white px-6 py-4 rounded-xl font-bold flex items-center justify-center gap-3 shadow-lg hover:bg-gray-800 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5" />
                </svg>
                View in your room (AR)
            </button>
        </model-viewer>
        
        <!-- Fallback Button if model-viewer not supported (Optional UI wrapper) -->
         <div class="text-center text-[10px] text-gray-400 mt-2 flex items-center justify-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
            Requires compatible mobile device
        </div>
    </div>
</template>

<style scoped>
/* Ensure the model-viewer doesn't take up layout space but is technically "visible" to the browser for AR activation */
model-viewer {
    display: block;
    position: relative;
    contain: strict;
}
</style>
