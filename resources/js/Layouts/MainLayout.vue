<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Footer from '@/Components/Footer.vue';
import SplashScreen from '@/Components/SplashScreen.vue';
import MobileNavDock from '@/Components/MobileNavDock.vue';
import AuthModal from '@/Components/AuthModal.vue';

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
const isSearchActive = ref(false);
const searchQuery = ref('');
const isProfileOpen = ref(false);
const searchInput = ref(null);
const recentSearches = ref([]);

const isAuthModalOpen = ref(false);
const authModalInitialView = ref('login');

const openAuthModal = (view = 'login') => {
    authModalInitialView.value = view;
    isAuthModalOpen.value = true;
};

// Load recent searches from local storage
onMounted(() => {
    const saved = localStorage.getItem('musea_recent_searches');
    if (saved) {
        recentSearches.value = JSON.parse(saved);
    }
    window.addEventListener('keydown', handleEsc);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleEsc);
});

const handleEsc = (e) => {
    if (e.key === 'Escape' && isSearchActive.value) {
        dismissSearch();
    }
};

const saveSearch = (query) => {
    if (!query || !query.trim()) return;
    const trimmed = query.trim();
    let updated = [trimmed, ...recentSearches.value.filter(s => s !== trimmed)];
    updated = updated.slice(0, 5); // Keep last 5
    recentSearches.value = updated;
    localStorage.setItem('musea_recent_searches', JSON.stringify(updated));
};

const dismissSearch = () => {
    isSearchActive.value = false;
    showSuggestions.value = false;
    searchQuery.value = '';
};

const handleScroll = () => {
    isScrolled.value = window.scrollY > 50;
};

const handleSearch = (customQuery = null) => {
    const finalQuery = customQuery || searchQuery.value;
    if (finalQuery && finalQuery.trim()) {
        saveSearch(finalQuery);
        router.visit(route('shop.index', { query: finalQuery.trim() }));
        dismissSearch();
    }
};



const suggestions = ref({ artworks: [], artists: [] });
const showSuggestions = ref(false);
const isLoading = ref(false);
let searchTimeout;

// Custom Debounce Implementation
const handleInput = () => {
    showSuggestions.value = true;
    isLoading.value = true;
    clearTimeout(searchTimeout);
    
    if (!searchQuery.value.trim()) {
        suggestions.value = { artworks: [], artists: [] };
        isLoading.value = false;
        return;
    }

    searchTimeout = setTimeout(async () => {
        try {
            const res = await axios.get(route('shop.suggestions', { query: searchQuery.value }));
            suggestions.value = res.data;
        } catch (error) {
            console.error(error);
        } finally {
            isLoading.value = false;
        }
    }, 300);
};

// Simple Click Outside Directive
const vClickOutside = {
  mounted(el, binding) {
    el.clickOutsideEvent = function(event) {
      if (!(el === event.target || el.contains(event.target))) {
        binding.value(event, el);
      }
    };
    document.body.addEventListener('click', el.clickOutsideEvent);
  },
  unmounted(el) {
    document.body.removeEventListener('click', el.clickOutsideEvent);
  },
};

const toggleSearch = () => {
    isSearchActive.value = !isSearchActive.value;
    if (isSearchActive.value) {
        setTimeout(() => searchInput.value?.focus(), 100);
    } else {
        showSuggestions.value = false;
    }
};

const toggleProfile = () => {
    isProfileOpen.value = !isProfileOpen.value;
};

const getAvatarUrl = (user) => {
    if (!user.avatar_path) {
        return `https://ui-avatars.com/api/?name=${user.first_name}+${user.last_name}&color=7F9CF5&background=EBF4FF`;
    }
    if (user.avatar_path.startsWith('http')) {
        return user.avatar_path;
    }
    // Fix: Ensure we don't double-slash if path already has /storage/ or check relative
    if (user.avatar_path.startsWith('/storage/') || user.avatar_path.startsWith('storage/')) {
        return user.avatar_path.startsWith('/') ? user.avatar_path : '/' + user.avatar_path;
    }
    return `/storage/${user.avatar_path}`;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <div class="min-h-screen bg-canvas">
        <SplashScreen />
        <header 
            class="fixed top-0 left-0 w-full z-[100] transition-all duration-500"
            :class="[
                isScrolled ? 'bg-white/80 backdrop-blur-xl border-b border-divider shadow-sm py-2' : 'bg-transparent py-4'
            ]"
        >
            <div class="w-full px-6 md:px-12 flex items-center justify-between">
                <!-- Brand / Logo -->
                <Link href="/" class="relative z-10 flex items-center group">
                    <img 
                        src="/images/logo/Musea.png" 
                        alt="Musea" 
                        class="h-14 md:h-16 w-auto transition-transform duration-500 group-hover:scale-105"
                        :class="{ 'invert brightness-0': !isScrolled && !props.withHeaderPadding }"
                    />
                </Link>

                <!-- Navigation Desktop -->
                <nav class="hidden lg:flex items-center gap-10">
                    <div class="relative group">
                        <button class="flex items-center gap-1.5 text-base font-semibold tracking-wide hover:text-accent transition-colors py-2" :class="[!isScrolled && !props.withHeaderPadding ? 'text-white' : 'text-ink']">
                            Explore
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform duration-300 group-hover:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                        <div class="absolute top-full left-1/2 -translate-x-1/2 pt-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:translate-y-0 translate-y-2">
                            <div class="bg-white border border-divider shadow-2xl rounded-2xl p-2 min-w-[220px] backdrop-blur-xl">
                                <Link v-for="cat in ['Painting', 'Digital', 'Sculpture', 'Photography', 'Mixed Media']" :key="cat" :href="`/shop?category=${cat}`" class="block px-4 py-2.5 text-sm text-ink-light hover:text-accent hover:bg-canvas rounded-xl transition-all">
                                    {{ cat }}
                                </Link>
                                <div class="h-px bg-divider my-2 mx-2"></div>
                                <Link href="/shop" class="block px-4 py-2.5 text-sm font-bold text-accent hover:bg-canvas rounded-xl transition-all">
                                    View Collection
                                </Link>
                            </div>
                        </div>
                    </div>
                    <Link href="/journal" class="text-base font-semibold tracking-wide hover:text-accent transition-colors" :class="[!isScrolled && !props.withHeaderPadding ? 'text-white' : 'text-ink']">Journal</Link>
                    <Link href="/about" class="text-base font-semibold tracking-wide hover:text-accent transition-colors" :class="[!isScrolled && !props.withHeaderPadding ? 'text-white' : 'text-ink']">About</Link>
                </nav>

                <!-- Actions -->
                <div class="flex items-center gap-2 md:gap-5">
                    <!-- Search -->
                        <div class="relative hidden md:block" v-click-outside="() => showSuggestions = false">
                        <button 
                            @click="toggleSearch" 
                            class="p-2.5 rounded-full transition-all duration-300 hover:bg-black/5"
                            :class="[!isScrolled && !props.withHeaderPadding ? 'text-white hover:bg-white/10' : 'text-ink']"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        </button>
                        
                        <transition
                            enter-active-class="transition duration-300 ease-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition duration-200 ease-in"
                            leave-from-class="opacity-100"
                            leave-to-class="opacity-0"
                        >
                            <div v-show="isSearchActive" @click="dismissSearch" class="fixed inset-0 bg-ink/20 backdrop-blur-sm z-40"></div>
                        </transition>

                        <transition
                            enter-active-class="transition duration-300 ease-out"
                            enter-from-class="opacity-0 translate-y-2 scale-95"
                            enter-to-class="opacity-100 translate-y-0 scale-100"
                            leave-active-class="transition duration-200 ease-in"
                            leave-from-class="opacity-100 translate-y-0 scale-100"
                            leave-to-class="opacity-0 translate-y-2 scale-95"
                        >
                            <div v-show="isSearchActive" class="absolute right-0 top-full mt-4 w-[400px] bg-white border border-divider shadow-2xl rounded-3xl p-6 z-50 overflow-hidden">
                                <div class="flex items-center gap-3 bg-canvas px-4 py-3 rounded-2xl border border-divider focus-within:border-accent transition-all mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-ink-light" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                    <input 
                                        type="text" 
                                        placeholder="Search masterpieces, artists..." 
                                        ref="searchInput"
                                        v-model="searchQuery"
                                        class="bg-transparent border-none focus:ring-0 text-sm w-full p-0 placeholder:text-ink-light/50"
                                        @input="handleInput"
                                        @focus="showSuggestions = true"
                                        @keyup.enter="handleSearch"
                                    />

                                </div>

                                
                                <!-- Suggestions Results -->
                                <div v-if="showSuggestions && (suggestions.artworks.length > 0 || suggestions.artists.length > 0)" class="mt-4 space-y-4 max-h-[300px] overflow-y-auto custom-scrollbar">
                                    <div v-if="suggestions.artists.length > 0">
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-ink-light/60 mb-2 px-1">Artists</p>
                                        <div class="space-y-2">
                                            <Link 
                                                v-for="artist in suggestions.artists" 
                                                :key="artist.id" 
                                                :href="route('artists.show', artist.id)"
                                                class="flex items-center gap-3 p-2 hover:bg-canvas rounded-xl transition-colors"
                                            >
                                                <img :src="artist.avatar" class="w-8 h-8 rounded-full object-cover">
                                                <span class="text-sm font-bold text-ink">{{ artist.name }}</span>
                                            </Link>
                                        </div>
                                    </div>

                                    <div v-if="suggestions.artworks.length > 0">
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-ink-light/60 mb-2 px-1">Artworks</p>
                                        <div class="space-y-2">
                                            <Link 
                                                v-for="artwork in suggestions.artworks" 
                                                :key="artwork.id" 
                                                :href="route('shop.show', artwork.id)"
                                                class="flex items-center gap-3 p-2 hover:bg-canvas rounded-xl transition-colors group/item"
                                            >
                                                <img :src="artwork.image_url" class="w-10 h-10 rounded-lg object-cover">
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-bold text-ink truncate group-hover/item:text-accent">{{ artwork.title }}</p>
                                                    <p class="text-xs text-ink-light">₱{{ artwork.price }}</p>
                                                </div>
                                            </Link>
                                        </div>
                                    </div>
                                </div>

                                <!-- Default Tags and History when no input -->
                                <div v-else-if="!searchQuery" class="space-y-6">
                                    <div v-if="recentSearches.length > 0">
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-ink-light/60 mb-3 px-1">Recent Searches</p>
                                        <div class="flex flex-col gap-2">
                                            <button 
                                                v-for="s in recentSearches" 
                                                :key="s" 
                                                @click="handleSearch(s)"
                                                class="flex items-center gap-3 p-2 hover:bg-canvas rounded-xl transition-colors text-left"
                                            >
                                                <svg class="w-4 h-4 text-ink-light/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <span class="text-sm font-medium text-ink">{{ s }}</span>
                                            </button>
                                        </div>
                                    </div>

                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-ink-light/60 mb-3 px-1">Discover more</p>
                                        <div class="flex flex-wrap gap-2">
                                            <button 
                                                v-for="tag in ['Painting', 'Digital', 'Sculpture', 'Photography', 'Mixed Media', 'Drawing']" 
                                                :key="tag" 
                                                @click="handleSearch(tag)"
                                                class="px-4 py-2 bg-zinc-50 hover:bg-ink hover:text-white rounded-full text-xs font-bold transition-all border border-divider"
                                            >
                                                {{ tag }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div v-else-if="searchQuery && !suggestions.artworks.length && !suggestions.artists.length && !isLoading" class="mt-4 text-center py-4">
                                     <p class="text-sm text-ink-light italic">No matches found.</p>
                                </div>
                            </div>
                        </transition>
                    </div>

                    <!-- Cart -->
                    <Link 
                        href="/cart" 
                        class="p-2.5 rounded-full relative transition-all duration-300 hover:bg-black/5"
                        :class="[!isScrolled && !props.withHeaderPadding ? 'text-white hover:bg-white/10' : 'text-ink']"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        <span v-if="cartCount > 0" class="absolute -top-1 -right-1 bg-accent text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center ring-2" :class="[!isScrolled && !props.withHeaderPadding ? 'ring-transparent' : 'ring-white']">
                            {{ cartCount }}
                        </span>
                    </Link>

                    <!-- Auth -->
                    <div v-if="user" class="relative" v-click-outside="() => isProfileOpen = false">
                        <button @click="toggleProfile" class="relative h-9 w-9 rounded-full border-2 border-divider hover:border-accent transition-all duration-300 group">
                            <img 
                                :src="user.avatar" 
                                alt="Profile" 
                                class="w-full h-full object-cover rounded-full"
                            />
                            <div v-if="$page.props.auth.unreadCount > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center ring-2 ring-white z-10">
                                {{ $page.props.auth.unreadCount }}
                            </div>
                        </button>
                        
                        <transition
                            enter-active-class="transition duration-200 ease-out"
                            enter-from-class="opacity-0 translate-y-2 scale-95"
                            enter-to-class="opacity-100 translate-y-0 scale-100"
                            leave-active-class="transition duration-150 ease-in"
                            leave-from-class="opacity-100 translate-y-0 scale-100"
                            leave-to-class="opacity-0 translate-y-2 scale-95"
                        >
                            <div v-show="isProfileOpen" class="absolute right-0 mt-4 w-64 bg-white border border-divider shadow-2xl rounded-2xl py-2 z-50">
                                <div class="px-5 py-4 border-b border-divider/50">
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-ink-light/60 mb-1">Account</p>
                                    <p class="text-sm font-bold text-ink truncate">{{ user.first_name }} {{ user.last_name }}</p>
                                </div>
                                <div class="py-1">
                                    <Link v-for="link in [
                                        { href: '/profile', label: 'My Profile', icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' },
                                        { href: route('orders.index'), label: 'Orders', icon: 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z' },
                                        { href: route('dashboard.artworks.index'), label: 'My Gallery', icon: 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z' },
                                        { href: route('profile.favorites'), label: 'My Favorites', icon: 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z' },
                                        { href: route('messages.index'), label: 'Inbox', icon: 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z' }
                                    ]" :key="link.label" :href="link.href" class="flex items-center gap-3 px-5 py-2.5 text-sm text-ink-light hover:text-accent hover:bg-canvas transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" :d="link.icon"></path></svg>
                                        {{ link.label }}
                                    </Link>

                                    <div class="h-px bg-divider my-2 mx-2"></div>
                                    <div class="px-5 py-2 text-[10px] font-bold uppercase tracking-widest text-ink-light/60">
                                        Notifications
                                    </div>
                                    <div v-if="$page.props.auth.notifications.length === 0" class="px-5 py-2 text-xs text-ink-light italic">
                                        No new notifications
                                    </div>
                                    <template v-else>
                                        <Link 
                                            v-for="notification in $page.props.auth.notifications" 
                                            :key="notification.id"
                                            :href="route('notifications.read', notification.id)"
                                            method="post"
                                            :data="{ redirect_to: notification.data.action_url }"
                                            as="button"
                                            class="flex w-full text-left flex-col gap-0.5 px-5 py-2 hover:bg-canvas border-b border-divider/50 last:border-0 transition-colors"
                                        >
                                            <span class="font-bold text-xs text-ink">{{ notification.data.title }}</span>
                                            <span class="text-[10px] text-ink-light truncate">{{ notification.data.message }}</span>
                                        </Link>
                                    </template>
                                </div>
                                <div class="border-t border-divider/50 py-1 mt-1">
                                    <Link href="/logout" method="post" as="button" class="flex w-full items-center gap-3 px-5 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-all font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Sign Out
                                    </Link>
                                </div>
                            </div>
                        </transition>
                    </div>
                    <button 
                        v-else 
                        @click="openAuthModal('login')"
                        class="p-2.5 rounded-full transition-all duration-300 hover:bg-black/5"
                        :class="[!isScrolled && !props.withHeaderPadding ? 'text-white hover:bg-white/10' : 'text-ink']"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </button>

                    <!-- Sell Button -->
                    <!-- Sell Button -->
                    <Link 
                        v-if="user && user.role === 'artist'"
                        :href="route('dashboard.artworks.create')" 
                        class="hidden md:flex items-center gap-2 px-6 py-2.5 rounded-full text-xs font-bold tracking-widest uppercase transition-all duration-500 transform hover:-translate-y-0.5 active:scale-95 shadow-lg shadow-ink/10"
                        :class="[
                            !isScrolled && !props.withHeaderPadding 
                                ? 'bg-white text-ink hover:bg-canvas' 
                                : 'bg-ink text-white hover:bg-ink-light'
                        ]"
                    >
                        <span>Sell Art</span>
                    </Link>
                </div>
            </div>
        </header>

        <main :class="{ 'pt-[140px]': withHeaderPadding }">
            <slot />
        </main>
        <Footer />
        
        <MobileNavDock @open-auth="openAuthModal('login')" />
        
        <AuthModal 
            :show="isAuthModalOpen" 
            :initial-view="authModalInitialView" 
            @close="isAuthModalOpen = false" 
        />
    </div>
</template>

<style scoped>
/* No longer need heavy custom styles as we use Tailwind's power for the layout */
header.scrolled {
    backdrop-filter: blur(20px) saturate(180%);
}
</style>
<style scoped>
/* No longer need heavy custom styles as we use Tailwind's power for the layout */
header.scrolled {
    backdrop-filter: blur(20px) saturate(180%);
}
</style>

