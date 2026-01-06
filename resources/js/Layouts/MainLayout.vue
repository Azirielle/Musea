<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Footer from '@/Components/Footer.vue';

const props = defineProps({
    withHeaderPadding: {
        type: Boolean,
        default: true
    }
});

const page = usePage();
const user = computed(() => page.props.auth.user);
import { useCart } from '@/composables/useCart';
const { cart } = useCart();
const cartCount = computed(() => cart.items.reduce((acc, item) => acc + item.quantity, 0));
const isScrolled = ref(false);
const isScrolledUp = ref(true);
const lastScrollY = ref(0);
const isSearchActive = ref(false);
const isProfileOpen = ref(false);
const searchInput = ref(null);

const handleScroll = () => {
    const currentScrollY = window.scrollY;
    isScrolled.value = currentScrollY > 50;
    isScrolledUp.value = currentScrollY < lastScrollY.value || currentScrollY < 50;
    lastScrollY.value = currentScrollY;
};

const toggleSearch = () => {
    isSearchActive.value = !isSearchActive.value;
    if (isSearchActive.value) {
        setTimeout(() => searchInput.value?.focus(), 100);
    }
};

const toggleProfile = () => {
    isProfileOpen.value = !isProfileOpen.value;
};

const closeSearch = (e) => {
    // handled by click outside directive or simple logic if needed
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <div class="min-h-screen">
        <!-- Compact Glass Top Bar -->
        <header :class="{ 'scrolled shadow-lg': isScrolled }" class="topbar fixed top-0 left-0 w-full z-[100] glass border-b border-white/20 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
                <Link href="/" class="brand-small" aria-label="Home">
                    <img src="/images/logo/Musea.png" alt="Musea logo" class="h-10 w-auto opacity-90 hover:opacity-100 transition-opacity" />
                </Link>

                <div class="header-icons flex items-center gap-6">
                    <!-- Search remains at top -->
                    <div class="search-container relative">
                        <div class="search-input-wrapper flex items-center" :class="{ active: isSearchActive }">
                            <input 
                                type="text" 
                                placeholder="Search artworks..." 
                                ref="searchInput"
                                class="bg-white/40 border-white/30 text-charcoal placeholder-charcoal/50 rounded-full py-1.5 px-4 text-sm focus:bg-white/60 focus:ring-grape focus:border-grape transition-all w-full"
                                @focus="isSearchActive = true"
                                @blur="setTimeout(() => isSearchActive = false, 200)"
                            />
                        </div>
                        <button @click="toggleSearch" class="p-2 transition-colors hover:text-grape" :class="{ 'text-grape': isSearchActive }">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </button>
                    </div>

                    <Link href="/cart" class="relative p-2 transition-colors hover:text-grape group">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path><path d="M3 6h18"></path><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                        <span v-if="cartCount > 0" class="absolute -top-1 -right-1 bg-grape text-white text-[10px] font-bold h-4 w-4 rounded-full flex items-center justify-center animate-pulse">{{ cartCount }}</span>
                    </Link>

                    <template v-if="user">
                        <div class="profile-menu-container relative">
                            <button @click="toggleProfile" class="w-9 h-9 rounded-full overflow-hidden border-2 border-white/50 hover:border-grape transition-all duration-300 shadow-sm">
                                <img 
                                    :src="user.avatar_path && user.avatar_path.startsWith('http') ? user.avatar_path : (user.avatar_path ? `/storage/${user.avatar_path}` : `https://ui-avatars.com/api/?name=${user.first_name}+${user.last_name}&color=7209b7&background=EBF4FF`)" 
                                    alt="Profile" 
                                    class="w-full h-full object-cover"
                                />
                            </button>
                            
                            <transition
                                enter-active-class="transition ease-out duration-200"
                                enter-from-class="transform opacity-0 scale-95 translate-y-2"
                                enter-to-class="transform opacity-100 scale-100 translate-y-0"
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="transform opacity-100 scale-100 translate-y-0"
                                leave-to-class="transform opacity-0 scale-95 translate-y-2"
                            >
                                <div v-show="isProfileOpen" class="absolute right-0 mt-3 w-64 glass rounded-2xl shadow-2xl ring-1 ring-white/30 py-2 z-50 origin-top-right overflow-hidden">
                                    <div class="px-5 py-4 border-b border-white/20">
                                        <p class="text-[10px] text-charcoal/60 uppercase tracking-widest font-bold mb-1">User Profile</p>
                                        <p class="text-sm font-bold text-charcoal truncate">{{ user.first_name }} {{ user.last_name }}</p>
                                        <p class="text-xs text-charcoal/50 truncate">{{ user.email }}</p>
                                    </div>
                                    
                                    <div class="py-1">
                                        <Link href="/profile" class="flex items-center gap-3 px-5 py-3 text-sm text-charcoal hover:bg-white/40 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            My Profile
                                        </Link>
                                        <Link :href="route('orders.index')" class="flex items-center gap-3 px-5 py-3 text-sm text-charcoal hover:bg-white/40 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                            My Orders
                                        </Link>
                                    </div>
                                    
                                    <div class="border-t border-white/20 py-1">
                                        <Link href="/logout" method="post" as="button" class="flex w-full items-center gap-3 px-5 py-3 text-sm text-red-600 hover:bg-red-50/30 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                            Sign Out
                                        </Link>
                                    </div>
                                </div>
                            </transition>
                        </div>
                    </template>
                    <template v-else>
                         <Link href="/login" class="p-2 transition-colors hover:text-grape" title="Login">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                         </Link>
                    </template>
                </div>
            </div>
        </header>

        <!-- Floating Bottom Navigation Dock -->
        <nav class="fixed bottom-8 left-1/2 -translate-x-1/2 z-[100] transition-transform duration-500" :class="{ 'translate-y-24': isScrolled && !isScrolledUp }">
            <div class="glass px-6 py-3 rounded-full border border-white/30 shadow-[0_8px_32px_0_rgba(31,38,135,0.15)] flex items-center gap-8">
                <Link href="/" class="flex flex-col items-center gap-1 group" :class="{ 'text-grape': $page.url === '/' }">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-y-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-10 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    <span class="text-[10px] font-bold uppercase tracking-wider">Home</span>
                </Link>
                <Link href="/shop" class="flex flex-col items-center gap-1 group" :class="{ 'text-grape': $page.url.startsWith('/shop') }">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-y-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    <span class="text-[10px] font-bold uppercase tracking-wider">Shop</span>
                </Link>
                <Link href="/journal" class="flex flex-col items-center gap-1 group" :class="{ 'text-grape': $page.url.startsWith('/journal') }">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-y-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    <span class="text-[10px] font-bold uppercase tracking-wider">Journal</span>
                </Link>
                <Link href="/artists" class="flex flex-col items-center gap-1 group" :class="{ 'text-grape': $page.url.startsWith('/artists') }">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-y-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    <span class="text-[10px] font-bold uppercase tracking-wider">Community</span>
                </Link>
                <div class="h-6 w-px bg-white/20"></div>
                <Link href="/onboarding" class="bg-grape text-white text-[10px] font-black uppercase tracking-[0.2em] px-4 py-2 rounded-full hover:bg-grape/90 transition-all hover:scale-105 active:scale-95 shadow-lg shadow-grape/20">
                    Sell Art
                </Link>
            </div>
        </nav>

        <main :class="{ 'pt-20': withHeaderPadding }" class="pb-32">
            <slot />
        </main>
        
        <Footer />
    </div>
</template>

<style scoped>
/* Paste relevant CSS from header.php here, adapted for Vue (remove .php references logic) */
/* Main Layout Transitions */
.view-enter-active, .view-leave-active {
    transition: opacity 0.5s ease;
}
.view-enter-from, .view-leave-to {
    opacity: 0;
}

/* Custom search container width transition */
.search-input-wrapper {
    width: 0;
    opacity: 0;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
.search-input-wrapper.active {
    width: 300px;
    opacity: 1;
}

/* Base text color override for glass consistency */
:deep(h1), :deep(h2), :deep(h3), :deep(h4), :deep(h5), :deep(h6), :deep(p), :deep(a), :deep(span) {
    color: #1A1C22;
}

@media (max-width: 640px) {
    nav {
        width: 90%;
        bottom: 24px;
        padding-bottom: env(safe-area-inset-bottom);
    }
}
</style>

