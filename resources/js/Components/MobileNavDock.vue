<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth.user);
const cartCount = computed(() => {
    // Assuming cart is available globally or passed down. 
    // Since this is a standalone component, we might need to use the composable if it shares state,
    // or rely on page props if the cart count is passed there.
    // Based on MainLayout, it uses a composable. Let's try to use the same composable.
    return page.props.cart ? page.props.cart.count : 0; 
});

// We can also use the composable directly if available in the project structure
// import { useCart } from '@/composables/useCart';
// const { cart } = useCart();
// const cartCount = computed(() => cart.items.reduce((acc, item) => acc + item.quantity, 0));
// BUT for now, let's rely on checking if we can import it. 
// MainLayout used: import { useCart } from '@/composables/useCart';
// So we will use that.

import { useCart } from '@/composables/useCart';
const { cart } = useCart();
const cartItemCount = computed(() => cart.items.reduce((acc, item) => acc + item.quantity, 0));


const isVisible = ref(true);
const lastScrollY = ref(0);

const handleScroll = () => {
    const currentScrollY = window.scrollY;
    
    // Show if scrolling up or at the very top
    if (currentScrollY < lastScrollY.value || currentScrollY < 50) {
        isVisible.value = true;
    } 
    // Hide if scrolling down and not at the top
    else if (currentScrollY > lastScrollY.value && currentScrollY > 50) {
        isVisible.value = false;
    }
    
    lastScrollY.value = currentScrollY;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll, { passive: true });
    lastScrollY.value = window.scrollY;
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

const links = [
    { label: 'Home', href: '/', icon: '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline>' },
    { label: 'Shop', href: '/shop', icon: '<circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>' }, // Using Search/Explore icon for Shop
    { label: 'Cart', href: '/cart', icon: '<circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>', isCart: true },
    { label: 'Profile', href: '/profile', icon: '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>' },
    // If user is not logged in, Profile link redirects to login usually, or we can conditional check
];

</script>

<template>
    <div 
        class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[90] w-[90%] max-w-sm transition-all duration-500 transform md:hidden"
        :class="isVisible ? 'translate-y-0 opacity-100' : 'translate-y-[150%] opacity-0'"
    >
        <div class="bg-white/80 backdrop-blur-xl border border-white/20 shadow-2xl rounded-2xl p-2 flex items-center justify-between px-6">
            <template v-for="link in links" :key="link.label">
                <button 
                    v-if="link.href === '/profile' && !$page.props.auth.user"
                    @click="$emit('open-auth')"
                    class="relative flex flex-col items-center justify-center p-2 group transition-all text-gray-400 hover:text-ink"
                >
                    <svg 
                        xmlns="http://www.w3.org/2000/svg" 
                        width="24" 
                        height="24" 
                        viewBox="0 0 24 24" 
                        fill="none" 
                        stroke="currentColor" 
                        stroke-width="2" 
                        stroke-linecap="round" 
                        stroke-linejoin="round" 
                        class="w-6 h-6 mb-0.5 transition-transform group-active:scale-95"
                        v-html="link.icon"
                    ></svg>
                </button>
                <Link 
                    v-else
                    :href="link.href"
                    class="relative flex flex-col items-center justify-center p-2 group transition-all"
                    :class="$page.url === link.href || ($page.url.startsWith(link.href) && link.href !== '/') ? 'text-accent' : 'text-gray-400 hover:text-ink'"
                >
                    <!-- Icon -->
                    <svg 
                        xmlns="http://www.w3.org/2000/svg" 
                        width="24" 
                        height="24" 
                        viewBox="0 0 24 24" 
                        fill="none" 
                        stroke="currentColor" 
                        stroke-width="2" 
                        stroke-linecap="round" 
                        stroke-linejoin="round" 
                        class="w-6 h-6 mb-0.5 transition-transform group-active:scale-95"
                        v-html="link.icon"
                    ></svg>
                    
                    <!-- Active Indicator -->
                    <span 
                        v-if="$page.url === link.href || ($page.url.startsWith(link.href) && link.href !== '/')" 
                        class="absolute -bottom-1 w-1 h-1 bg-accent rounded-full"
                    ></span>

                    <!-- Cart Badge -->
                    <span 
                        v-if="link.isCart && cartItemCount > 0" 
                        class="absolute top-1 right-0 sm:right-1 bg-red-500 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center border border-white"
                    >
                        {{ cartItemCount }}
                    </span>
                </Link>
            </template>
        </div>
    </div>
</template>
