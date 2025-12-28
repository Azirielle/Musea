<script setup>
import { ref, computed } from 'vue';
import Swal from 'sweetalert2';
import { Head, Link, usePage } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import PaymentSelector from './Partials/PaymentSelector.vue';
import GCashModal from './Partials/GCashModal.vue';
import PayPalModal from './Partials/PayPalModal.vue';
import BankTransferModal from './Partials/BankTransferModal.vue';
import ProcessingOverlay from './Partials/ProcessingOverlay.vue';
import SuccessModal from './Partials/SuccessModal.vue';
import ContactModal from './Partials/ContactModal.vue';
import { useCart } from '@/composables/useCart';

const { cart, clearCart } = useCart();
const page = usePage(); // Fix: usePage() was not imported but used in original template, better to import

const currentStep = ref('selection'); // selection, verification, processing, success
const selectedPaymentMethod = ref('');
const showGCashModal = ref(false);
const showPayPalModal = ref(false);
const showBankModal = ref(false);
const showContactModal = ref(false); // New State
const orderId = ref('');

const contactInfo = ref({
    name: page.props.auth.user.first_name + ' ' + page.props.auth.user.last_name,
    email: page.props.auth.user.email,
    phone: '+63 912 345 6789'
});

const couponCode = ref('');
const appliedCoupon = ref(null);
const discountAmount = ref(0);

const subtotal = computed(() => {
    return cart.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
});

const shipping = 150; // Flat rate for demo
const total = computed(() => Math.max(0, (subtotal.value - discountAmount.value) + shipping));

const applyCoupon = () => {
    if (!couponCode.value) return;

    axios.post(route('checkout.validate-coupon'), {
        code: couponCode.value,
        subtotal: subtotal.value
    })
    .then(response => {
        discountAmount.value = response.data.discount;
        appliedCoupon.value = response.data.code;
        Swal.fire({
            icon: 'success',
            title: 'Coupon Applied',
            text: `You saved ₱${discountAmount.value}`,
            timer: 1500,
            showConfirmButton: false
        });
    })
    .catch(error => {
        discountAmount.value = 0;
        appliedCoupon.value = null;
        Swal.fire({
            icon: 'error',
            title: 'Invalid Coupon',
            text: error.response?.data?.message || 'Code not found',
            confirmButtonColor: '#1A1A1A'
        });
    });
};

const handlePaymentSelect = (methodId) => {
    selectedPaymentMethod.value = methodId;
};

import { router } from '@inertiajs/vue3';

const handleContactSave = (newData) => {
    // Split name into first and last name for the backend
    const nameParts = newData.name.trim().split(' ');
    const firstName = nameParts.current ? nameParts[0] : newData.name;
    // Handle cases with multiple names correctly or just simple split
    // Simple split:
    const fName = nameParts.shift();
    const lName = nameParts.join(' ');

    router.visit(route('profile.update'), {
        method: 'patch',
        data: {
            first_name: fName,
            last_name: lName,
            email: newData.email,
        },
        preserveScroll: true,
        onSuccess: () => {
            contactInfo.value = newData;
            showContactModal.value = false;
        }
    });
};

const proceedToPayment = () => {
    if (!selectedPaymentMethod.value) return;

    if (selectedPaymentMethod.value === 'gcash') {
        showGCashModal.value = true;
    } else if (selectedPaymentMethod.value === 'paypal') {
        showPayPalModal.value = true;
    } else if (selectedPaymentMethod.value === 'bank') {
        showBankModal.value = true;
    } else if (selectedPaymentMethod.value === 'cod') {
        processPayment(); // Direct process for COD
    }
};

const processPayment = () => {
    // Close any open modals
    showGCashModal.value = false;
    showPayPalModal.value = false;
    showBankModal.value = false;

    currentStep.value = 'processing';

    // Prepare data for backend
    const orderData = {
        items: cart.items.map(item => ({
            id: item.id,
            quantity: item.quantity
        })),
        contact: {
            name: contactInfo.value.name,
            email: contactInfo.value.email,
            phone: contactInfo.value.phone
        },
        payment_method: selectedPaymentMethod.value,
        coupon_code: appliedCoupon.value
    };

    // Use axios or Inertia form helper. Since we are in a method, axios might be cleaner for non-navigation POST,
    // but Inertia is standard. Let's use axios if available, or just fetch/router.
    // Actually, router.post causes navigation. We want to show success modal on SAME page.
    // So axios is better. Check if axios is available. Usually Laravel + Vue has axios.
    // If not, use fetch.
    
    // We will use axios (assuming it is globally available or we import it).
    // If not, we fall back to fetch. Let's try axios first, standard in Laravel.
    axios.post(route('checkout.store'), orderData)
        .then(response => {
            orderId.value = response.data.order_id;
            currentStep.value = 'success';
            clearCart();
        })
        .catch(error => {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Order Failed',
                text: error.response?.data?.message || 'Unknown error',
                confirmButtonColor: '#1A1A1A'
            });
            currentStep.value = 'selection'; // Go back
        });
};
</script>

<template>
    <Head title="Checkout" />
    <MainLayout :withHeaderPadding="true">
        <div class="bg-[#FAFAFA] min-h-screen pb-20">
            <div class="max-w-4xl mx-auto px-6 py-10">
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-8">
                    <Link href="/cart" class="hover:text-black">Cart</Link>
                    <span>/</span>
                    <span class="text-black font-semibold">Checkout</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                    <!-- Left Column: Payment Details -->
                    <div class="md:col-span-2 space-y-8">
                        
                        <!-- Contact Info (Editable) -->
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-bold">Contact Information</h2>
                                <button @click="showContactModal = true" class="text-sm text-accent hover:underline">Edit</button>
                            </div>
                            <div class="text-gray-600 text-sm space-y-1">
                                <p class="font-medium text-gray-900">{{ contactInfo.name }}</p>
                                <!-- <p>{{ contactInfo.email }}</p> -->
                                <p>{{ contactInfo.phone }}</p>
                            </div>
                        </div>

                         <!-- Payment Method -->
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            <h2 class="text-lg font-bold mb-6">Payment Method</h2>
                            <PaymentSelector 
                                :selectedMethod="selectedPaymentMethod" 
                                @select="handlePaymentSelect" 
                            />
                        </div>

                    </div>

                    <!-- Right Column: Order Summary -->
                    <div class="md:col-span-1">
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-32">
                            <h2 class="text-lg font-bold mb-6">Order Summary</h2>
                            
                            <!-- Items List -->
                            <div class="space-y-4 mb-6 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                                <div v-for="item in cart.items" :key="item.id" class="flex gap-3">
                                    <div class="w-16 h-16 bg-gray-100 rounded-md overflow-hidden flex-shrink-0">
                                         <img :src="item.image_url || '/images/placeholder-art.jpg'" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium line-clamp-2">{{ item.title }}</p>
                                        <p class="text-xs text-gray-500">Qty: {{ item.quantity }}</p>
                                    </div>
                                    <p class="text-sm font-medium">₱{{ (item.price * item.quantity).toLocaleString() }}</p>
                                </div>
                            </div>

                            <div class="border-t border-gray-100 pt-4 space-y-2 text-sm text-gray-600">
                                <div class="flex justify-between">
                                    <span>Subtotal</span>
                                    <span>₱{{ subtotal.toLocaleString() }}</span>
                                </div>
                                
                                <!-- Coupon Input -->
                                <div class="py-2">
                                    <div class="flex gap-2 items-center">
                                        <input 
                                            v-model="couponCode" 
                                            type="text" 
                                            placeholder="Enter coupon code" 
                                            class="flex-1 bg-gray-50 border border-gray-200 rounded px-3 py-2 text-sm uppercase placeholder-gray-400 focus:outline-none focus:border-black min-w-0"
                                            :disabled="!!appliedCoupon"
                                        >
                                        <button 
                                            @click="applyCoupon"
                                            class="bg-black text-white text-xs font-bold px-4 py-2.5 rounded hover:bg-gray-800 transition disabled:opacity-50 whitespace-nowrap"
                                            :disabled="!!appliedCoupon || !couponCode"
                                        >
                                            {{ appliedCoupon ? 'APPLIED' : 'APPLY' }}
                                        </button>
                                    </div>
                                    <p v-if="appliedCoupon" class="text-xs text-green-600 mt-1 font-bold">
                                        Code {{ appliedCoupon }} applied!
                                    </p>
                                </div>

                                <div class="flex justify-between text-green-600" v-if="discountAmount > 0">
                                    <span>Discount</span>
                                    <span>-₱{{ discountAmount.toLocaleString() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Shipping Fee</span>
                                    <span>₱{{ shipping.toLocaleString() }}</span>
                                </div>
                            </div>

                            <div class="border-t border-gray-100 pt-4 mt-4 flex justify-between items-center bg-gray-50 -mx-6 px-6 py-4 rounded-b-xl border-dashed border-t-2">
                                <span class="font-bold text-lg">Total</span>
                                <span class="font-bold text-xl">₱{{ total.toLocaleString() }}</span>
                            </div>

                            <button 
                                @click="proceedToPayment"
                                class="w-full bg-[#1A1A1A] text-white font-bold py-4 rounded-xl mt-6 hover:bg-black transition-all shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="!selectedPaymentMethod"
                            >
                                Place Order
                            </button>
                            
                            <p v-if="!selectedPaymentMethod" class="text-xs text-center text-gray-400 mt-3">Select a payment method to proceed</p>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals -->
        <ContactModal 
            v-if="showContactModal" 
            :initialData="contactInfo"
            @close="showContactModal = false" 
            @save="handleContactSave" 
        />
        <GCashModal v-if="showGCashModal" @close="showGCashModal = false" @success="processPayment" />
        <PayPalModal v-if="showPayPalModal" @close="showPayPalModal = false" @success="processPayment" />
        <BankTransferModal v-if="showBankModal" @close="showBankModal = false" @success="processPayment" />
        
        <ProcessingOverlay v-if="currentStep === 'processing'" />
        <SuccessModal v-if="currentStep === 'success'" :orderId="orderId" />

    </MainLayout>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: #f1f1f1; 
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #888; 
  border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #555; 
}
</style>
