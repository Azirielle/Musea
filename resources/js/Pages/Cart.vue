<script setup>
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import { useCart } from '@/composables/useCart';
import { computed } from 'vue';

const { cart, removeFromCart, updateQuantity, clearCart } = useCart();
const page = usePage();

const total = computed(() => {
    return cart.items.reduce((sum, item) => sum + (item.price * item.quantity), 0).toFixed(2);
});

const handleCheckout = () => {
    if (!page.props.auth.user) {
        router.get(route('login'));
        return;
    }
    
    // Proceed to checkout logic (not implemented yet)
    alert('Proceeding to checkout...');
};
</script>

<template>
    <Head title="Your Cart" />
    <MainLayout>
        <div class="pt-24 pb-12 px-6 bg-[#FAF7F2] min-h-screen">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-4xl font-bold text-[#1A1A1A] mb-8">Shopping Cart</h1>

                <div v-if="cart.items.length === 0" class="text-center py-20 bg-white rounded-xl shadow-sm">
                    <p class="text-xl text-gray-500 mb-6">Your cart is empty.</p>
                    <Link :href="route('shop.index')" class="bg-[#CBA35C] text-white px-6 py-3 rounded-lg hover:bg-[#b08d4b] transition">
                        Browse Artworks
                    </Link>
                </div>

                <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Cart Items -->
                    <div class="md:col-span-2 space-y-4">
                        <div v-for="item in cart.items" :key="item.id" class="bg-white p-4 rounded-xl shadow-sm flex gap-4 items-center">
                            <div class="w-24 h-24 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                <img :src="item.image_url || '/images/placeholder-art.jpg'" :alt="item.title" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-grow">
                                <h3 class="font-bold text-[#1A1A1A]">{{ item.title }}</h3>
                                <p class="text-sm text-gray-500">${{ item.price }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button @click="updateQuantity(item.id, item.quantity - 1)" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center">-</button>
                                <span class="font-medium w-4 text-center">{{ item.quantity }}</span>
                                <button @click="updateQuantity(item.id, item.quantity + 1)" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center">+</button>
                            </div>
                            <button @click="removeFromCart(item.id)" class="text-red-500 hover:text-red-700 ml-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                        
                        <div class="flex justify-end">
                            <button @click="clearCart" class="text-sm text-gray-500 hover:text-red-500 underline">Clear Cart</button>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="bg-white p-6 rounded-xl shadow-sm h-fit">
                        <h2 class="text-xl font-bold mb-4">Summary</h2>
                        <div class="flex justify-between mb-2 text-gray-600">
                            <span>Subtotal</span>
                            <span>${{ total }}</span>
                        </div>
                        <div class="flex justify-between mb-6 text-gray-600">
                            <span>Shipping</span>
                            <span>Calculated at checkout</span>
                        </div>
                        <div class="border-t pt-4 flex justify-between font-bold text-lg mb-6">
                            <span>Total</span>
                            <span>${{ total }}</span>
                        </div>
                        
                        <button 
                            @click="handleCheckout"
                            class="w-full py-4 rounded-xl font-bold text-center transition shadow-lg"
                            :class="$page.props.auth.user ? 'bg-[#1A1A1A] text-white hover:bg-[#333]' : 'bg-gray-200 text-gray-500 hover:bg-gray-300'"
                        >
                            {{ $page.props.auth.user ? 'Proceed to Checkout' : 'Login to Checkout' }}
                        </button>
                        
                        <p v-if="!$page.props.auth.user" class="text-xs text-center text-red-500 mt-2">
                            * You must be logged in to verify your identity before purchasing.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
