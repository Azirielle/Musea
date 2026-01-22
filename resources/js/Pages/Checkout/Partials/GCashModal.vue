<script setup>
import { ref } from 'vue';

const emit = defineEmits(['close', 'success']);

const step = ref(1); // 1: Login, 2: OTP
const countryCode = ref('+63');
const phoneNumber = ref('');
const otp = ref('');
const isLoading = ref(false);
const error = ref('');

const validatePhone = () => {
    // Basic validation: Check if number. For PH (+63), should be 10 digits starting with 9
    const cleanNumber = phoneNumber.value.replace(/\D/g, '');
    
    if (countryCode.value === '+63') {
        if (!/^9\d{9}$/.test(cleanNumber)) {
            error.value = 'Please enter a valid PH mobile number (e.g. 9123456789)';
            return false;
        }
    } else {
        if (cleanNumber.length < 7) {
             error.value = 'Please enter a valid mobile number';
             return false;
        }
    }
    return true;
};

const sendOtp = () => {
    if (!validatePhone()) return;

    isLoading.value = true;
    // Mock "Send Code" - simulates network request
    setTimeout(() => {
        step.value = 2;
        isLoading.value = false;
    }, 1000); // Reduced time for snappier feel
};

const verifyOtp = () => {
    isLoading.value = true;
    setTimeout(() => {
        isLoading.value = false;
        // Pass full formatted number back
        emit('success', `${countryCode.value}${phoneNumber.value}`);
    }, 1500);
};
</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden relative animate-fade-in-up">
            <!-- Header -->
            <div class="bg-[#007DFE] p-4 flex justify-between items-center text-white">
                <h3 class="font-bold text-lg">GCash</h3>
                <button @click="$emit('close')" class="text-white/80 hover:text-white">&times;</button>
            </div>

            <!-- Body -->
            <div class="p-6">
                <!-- Step 1: Login -->
                <div v-if="step === 1" class="space-y-4">
                    <div class="text-center mb-6">
                        <p class="text-gray-600 text-sm">Login to pay with GCash</p>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">MOBILE NUMBER</label>
                        <div class="flex border-b-2 border-gray-300 focus-within:border-[#007DFE] transition-colors">
                            <select v-model="countryCode" class="bg-transparent border-none outline-none py-2 font-mono text-lg text-gray-700 w-20">
                                <option value="+63">+63 (PH)</option>
                                <option value="+1">+1 (US)</option>
                                <option value="+44">+44 (UK)</option>
                                <option value="+81">+81 (JP)</option>
                                <option value="+86">+86 (CN)</option>
                            </select>
                            <input 
                                v-model="phoneNumber" 
                                type="tel" 
                                maxlength="10"
                                placeholder="9XXXXXXXXX" 
                                class="flex-1 bg-transparent border-none outline-none py-2 text-lg font-mono placeholder-gray-300"
                                @input="error = ''"
                                autofocus
                            />
                        </div>
                        <p v-if="error" class="text-xs text-red-500 mt-1 font-bold">{{ error }}</p>
                    </div>

                    <button 
                        @click="sendOtp" 
                        class="w-full bg-[#007DFE] text-white rounded-full py-3 font-bold mt-8 hover:bg-[#0063ca] transition disabled:opacity-50"
                        :disabled="!phoneNumber || isLoading"
                    >
                        <span v-if="isLoading">Sending Code...</span>
                        <span v-else>NEXT</span>
                    </button>
                </div>

                <!-- Step 2: OTP -->
                <div v-else class="space-y-4 text-center">
                    <div class="mb-4">
                        <h4 class="font-bold text-[#007DFE]">Authentication</h4>
                        <p class="text-xs text-gray-500 mt-1">We sent a code to {{ countryCode }} {{ phoneNumber }}</p>
                    </div>

                    <div class="flex justify-center gap-2 my-6">
                        <input 
                            v-model="otp"
                            type="text" 
                            maxlength="6"
                            class="w-64 text-center text-2xl tracking-[0.5em] border-b-2 border-gray-300 focus:border-[#007DFE] outline-none py-2 font-mono"
                            placeholder="000000"
                        />
                    </div>

                    <button 
                        @click="verifyOtp" 
                        class="w-full bg-[#007DFE] text-white rounded-full py-3 font-bold hover:bg-[#0063ca] transition disabled:opacity-50"
                        :disabled="otp.length < 6 || isLoading"
                    >
                         <span v-if="isLoading">Verifying...</span>
                         <span v-else>PAY PHP 2,340.00</span>
                    </button>
                    
                    <p class="text-xs text-gray-400 mt-4 cursor-pointer hover:text-[#007DFE]">Resend Code</p>
                </div>
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
