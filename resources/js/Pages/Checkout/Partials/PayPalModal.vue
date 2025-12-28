<script setup>
import { ref } from 'vue';

const emit = defineEmits(['close', 'success']);

const phoneNumber = ref('');
const countryCode = ref('+63');
const isLoading = ref(false);
const error = ref('');

const validatePhone = () => {
    const cleanNumber = phoneNumber.value.replace(/\D/g, '');
    if (countryCode.value === '+63') {
        if (!/^9\d{9}$/.test(cleanNumber)) {
            error.value = 'Please enter a valid PH mobile number';
            return false;
        }
    } else {
        if (cleanNumber.length < 7) {
            error.value = 'Invalid number';
            return false;
        }
    }
    return true;
};

const login = () => {
    if (!validatePhone()) return;

    isLoading.value = true;
    setTimeout(() => {
        isLoading.value = false;
        // Mock success with phone number
        emit('success', `${countryCode.value}${phoneNumber.value}`);
    }, 1500);
};
</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-sm overflow-hidden relative animate-fade-in-up">
            <div class="p-8">
                <div class="flex justify-center mb-8">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal" class="h-8">
                </div>
                
                <div class="space-y-6">
                    <div class="text-center">
                        <p class="text-gray-600 font-medium">Log in with your mobile number</p>
                    </div>

                    <div>
                         <div class="flex border border-gray-300 rounded overflow-hidden focus-within:border-[#0070BA] focus-within:ring-1 focus-within:ring-[#0070BA] transition-all">
                            <select v-model="countryCode" class="bg-gray-50 border-r border-gray-300 outline-none py-3 px-2 text-gray-700 w-20 text-sm">
                                <option value="+63">+63</option>
                                <option value="+1">+1</option>
                                <option value="+44">+44</option>
                                <option value="+81">+81</option>
                            </select>
                            <input 
                                v-model="phoneNumber" 
                                type="tel" 
                                maxlength="10"
                                placeholder="9XXXXXXXXX" 
                                class="flex-1 outline-none py-3 px-3 text-gray-700"
                                @input="error = ''"
                                autofocus
                            />
                        </div>
                        <p v-if="error" class="text-xs text-red-500 mt-1 font-bold">{{ error }}</p>
                    </div>
                    
                    <button 
                        @click="login" 
                        class="w-full bg-[#0070BA] text-white rounded font-bold py-3 hover:bg-[#003087] transition disabled:opacity-50"
                        :disabled="isLoading || !phoneNumber"
                    >
                         <span v-if="isLoading">Processing...</span>
                         <span v-else>Next</span>
                    </button>
                    
                    <div class="flex items-center gap-2 my-4">
                        <div class="h-px bg-gray-300 flex-grow"></div>
                        <span class="text-gray-400 text-sm">or</span>
                        <div class="h-px bg-gray-300 flex-grow"></div>
                    </div>
                    
                    <button class="w-full bg-gray-100 text-[#2D2D2D] font-bold py-3 rounded hover:bg-gray-200 transition">
                        Sign Up
                    </button>
                </div>
            </div>
            
            <button @click="$emit('close')" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 p-2">&times;</button>
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
