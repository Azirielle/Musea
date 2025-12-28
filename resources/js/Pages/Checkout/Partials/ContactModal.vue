<script setup>
import { ref } from 'vue';

const props = defineProps({
    initialData: Object,
});

const emit = defineEmits(['close', 'save']);

const form = ref({
    name: props.initialData.name,
    email: props.initialData.email,
    phone: props.initialData.phone,
});

const save = () => {
    emit('save', form.value);
};
</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md animate-fade-in-up">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-bold font-serif">Edit Contact Info</h3>
                <button @click="$emit('close')" class="text-gray-400 hover:text-black">&times;</button>
            </div>
            
            <div class="p-6 space-y-4">
                <!-- Name and Email hidden as per user request to only show number -->
                <div class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input 
                        v-model="form.name"
                        type="text" 
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-accent focus:ring focus:ring-accent/20 transition"
                    />
                </div>
                
                <div class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input 
                        v-model="form.email"
                        type="email" 
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-accent focus:ring focus:ring-accent/20 transition"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input 
                        v-model="form.phone"
                        type="tel" 
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-accent focus:ring focus:ring-accent/20 transition"
                    />
                </div>
            </div>

            <div class="p-6 pt-0 flex justify-end gap-3">
                <button @click="$emit('close')" class="px-4 py-2 border border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button @click="save" class="px-6 py-2 bg-accent text-white font-bold rounded-lg hover:bg-black transition">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-up {
    animation: fade-in-up 0.3s ease-out forwards;
}
</style>
