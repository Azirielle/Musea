<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const emit = defineEmits(['open-auth']);

const page = usePage();
const user = computed(() => page.props.auth.user);

import { useCart } from '@/composables/useCart';
const { cart } = useCart();
const cartItemCount = computed(() => cart.items.reduce((acc, item) => acc + item.quantity, 0));

const isVisible = ref(true);
const lastScrollY = ref(0);
const isUserMenuOpen = ref(false);

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

const toggleUserMenu = () => {
    if (!user.value) {
        emit('open-auth');
        return;
    }
    isUserMenuOpen.value = !isUserMenuOpen.value;
};

const closeUserMenu = () => {
    isUserMenuOpen.value = false;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll, { passive: true });
    lastScrollY.value = window.scrollY;
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

const navLinks = [
    { label: 'Home', href: '/', icon: '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline>' },
    { label: 'Shop', href: '/shop', icon: '<circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>' },
    { label: 'Cart', href: '/cart', icon: '<circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>', isCart: true },
];

// User menu links (shown in the drawer)
const userMenuLinks = computed(() => {
    if (!user.value) return [];
    
    const links = [
        { label: 'My Profile', href: '/profile', icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' },
        { label: 'Orders', href: route('orders.index'), icon: 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z' },
    ];
    
    // Add Wallet only for artists
    if (user.value.role === 'artist') {
        links.push({ label: 'My Wallet', href: route('dashboard.wallet'), icon: 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-2m2-8h-5a2 2 0 00-2 2v6a2 2 0 002 2h5a1 1 0 001-1v-8a1 1 0 00-1-1z' });
    }
    
    links.push(
        { label: 'My Gallery', href: route('dashboard.artworks.index'), icon: 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z' },
        { label: 'My Favorites', href: route('profile.favorites'), icon: 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z' },
        { label: 'Inbox', href: route('messages.index'), icon: 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z' }
    );
    
    return links;
});

const getAvatarUrl = (user) => {
    if (!user.avatar_path) {
        return `https://ui-avatars.com/api/?name=${user.first_name}+${user.last_name}&color=7F9CF5&background=EBF4FF`;
    }
    if (user.avatar_path.startsWith('http')) {
        return user.avatar_path;
    }
    if (user.avatar_path.startsWith('/storage/') || user.avatar_path.startsWith('storage/')) {
        return user.avatar_path.startsWith('/') ? user.avatar_path : '/' + user.avatar_path;
    }
    return `/storage/${user.avatar_path}`;
};
</script>

<template>
    <!-- Backdrop for user menu -->
    <Transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div 
            v-if="isUserMenuOpen" 
            @click="closeUserMenu" 
            class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[85] md:hidden"
        ></div>
    </Transition>

    <!-- User Menu Drawer -->
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="translate-y-full opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="translate-y-full opacity-0"
    >
        <div 
            v-if="isUserMenuOpen && user" 
            class="fixed bottom-24 left-1/2 -translate-x-1/2 z-[88] w-[90%] max-w-sm md:hidden"
        >
            <div class="bg-white border border-gray-100 shadow-2xl rounded-2xl overflow-hidden">
                <!-- User Header -->
                <div class="p-4 border-b border-gray-100 flex items-center gap-3 bg-gradient-to-r from-gray-50 to-white">
                    <img 
                        :src="user.avatar || getAvatarUrl(user)" 
                        :alt="user.first_name" 
                        class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-md"
                    />
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-900 truncate">{{ user.first_name }} {{ user.last_name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ user.email }}</p>
                    </div>
                </div>

                <!-- Menu Links -->
                <div class="py-2 max-h-[280px] overflow-y-auto">
                    <Link 
                        v-for="link in userMenuLinks" 
                        :key="link.label" 
                        :href="link.href" 
                        @click="closeUserMenu"
                        class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-amber-600 transition-colors"
                    >
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="link.icon"></path>
                        </svg>
                        <span class="text-sm font-medium">{{ link.label }}</span>
                        
                        <!-- Unread badge for Inbox -->
                        <span 
                            v-if="link.label === 'Inbox' && $page.props.auth.unreadCount > 0" 
                            class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full"
                        >
                            {{ $page.props.auth.unreadCount }}
                        </span>
                    </Link>
                </div>

                <!-- Logout -->
                <div class="border-t border-gray-100 py-2">
                    <Link 
                        href="/logout" 
                        method="post" 
                        as="button" 
                        @click="closeUserMenu"
                        class="flex w-full items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="text-sm font-medium">Sign Out</span>
                    </Link>
                </div>
            </div>
        </div>
    </Transition>

    <!-- Main Navigation Dock -->
    <div 
        class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[90] w-[90%] max-w-sm transition-all duration-500 transform md:hidden"
        :class="isVisible ? 'translate-y-0 opacity-100' : 'translate-y-[150%] opacity-0'"
    >
        <div class="bg-white/80 backdrop-blur-xl border border-white/20 shadow-2xl rounded-2xl p-2 flex items-center justify-between px-6">
            <!-- Navigation Links -->
            <Link 
                v-for="link in navLinks" 
                :key="link.label"
                :href="link.href"
                class="relative flex flex-col items-center justify-center p-2 group transition-all"
                :class="$page.url === link.href || ($page.url.startsWith(link.href) && link.href !== '/') ? 'text-amber-600' : 'text-gray-400 hover:text-gray-700'"
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
                
                <!-- Active Indicator -->
                <span 
                    v-if="$page.url === link.href || ($page.url.startsWith(link.href) && link.href !== '/')" 
                    class="absolute -bottom-1 w-1 h-1 bg-amber-600 rounded-full"
                ></span>

                <!-- Cart Badge -->
                <span 
                    v-if="link.isCart && cartItemCount > 0" 
                    class="absolute top-1 right-0 sm:right-1 bg-red-500 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center border border-white"
                >
                    {{ cartItemCount }}
                </span>
            </Link>

            <!-- Profile Button (opens menu or auth) -->
            <button 
                @click="toggleUserMenu"
                class="relative flex flex-col items-center justify-center p-2 group transition-all"
                :class="isUserMenuOpen ? 'text-amber-600' : 'text-gray-400 hover:text-gray-700'"
            >
                <!-- User Avatar or Default Icon -->
                <template v-if="user">
                    <img 
                        :src="user.avatar || getAvatarUrl(user)" 
                        :alt="user.first_name"
                        class="w-7 h-7 rounded-full object-cover border-2 transition-all"
                        :class="isUserMenuOpen ? 'border-amber-600' : 'border-gray-200 group-hover:border-gray-300'"
                    />
                    <!-- Notification badge -->
                    <span 
                        v-if="$page.props.auth.unreadCount > 0" 
                        class="absolute top-0 right-0 bg-red-500 text-white text-[8px] font-bold w-4 h-4 rounded-full flex items-center justify-center border border-white"
                    >
                        {{ $page.props.auth.unreadCount > 9 ? '9+' : $page.props.auth.unreadCount }}
                    </span>
                </template>
                <template v-else>
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
                    >
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </template>
                
                <!-- Active Indicator -->
                <span 
                    v-if="isUserMenuOpen || $page.url.startsWith('/profile') || $page.url.startsWith('/dashboard')" 
                    class="absolute -bottom-1 w-1 h-1 bg-amber-600 rounded-full"
                ></span>
            </button>
        </div>
    </div>
</template>

