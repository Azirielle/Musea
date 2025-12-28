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
const isSearchActive = ref(false);
const isProfileOpen = ref(false);
const searchInput = ref(null);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 50;
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
    <div>
        <header :class="{ scrolled: isScrolled }">
            <div class="topbar">
                <Link href="/" class="brand" aria-label="Home">
                    <img src="/images/logo/Musea.png" alt="Musea logo" class="brand-img" />
                    <!-- Placeholder logo path, needs legacy images copied to public -->
                </Link>

                <div class="header-icons">
                    <div class="search-container">
                        <div class="search-input-wrapper" :class="{ active: isSearchActive }">
                            <input type="text" placeholder="Search..." ref="searchInput" />
                        </div>
                        <a href="#" @click.prevent="toggleSearch" class="icon-link icon-search" :class="{ active: isSearchActive }">
                            <svg viewBox="0 0 24 24"><circle cx="10.5" cy="10.5" r="7.5"></circle><path d="M15.5 15.5L21 21"></path></svg>
                        </a>
                    </div>

                    <div class="cart-badge">
                        <Link href="/cart" class="icon-link icon-cart">
                            <svg viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 5.5M17 13l1.5 5.5M9 20.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2zM16 20.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"></path></svg>
                        </Link>
                        <span v-if="cartCount > 0" id="cartCount">{{ cartCount }}</span>
                    </div>

                    <template v-if="user">
                        <div class="profile-menu-container relative">
                            <a href="#" @click.prevent="toggleProfile" class="flex items-center gap-2 focus:outline-none group">
                                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-transparent group-hover:border-accent transition-all duration-300">
                                    <img 
                                        :src="user.avatar_path && user.avatar_path.startsWith('http') ? user.avatar_path : (user.avatar_path ? `/storage/${user.avatar_path}` : `https://ui-avatars.com/api/?name=${user.first_name}+${user.last_name}&color=7F9CF5&background=EBF4FF`)" 
                                        alt="Profile" 
                                        class="w-full h-full object-cover"
                                    />
                                </div>
                            </a>
                            
                            <transition
                                enter-active-class="transition ease-out duration-200"
                                enter-from-class="transform opacity-0 scale-95 translate-y-2"
                                enter-to-class="transform opacity-100 scale-100 translate-y-0"
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="transform opacity-100 scale-100 translate-y-0"
                                leave-to-class="transform opacity-0 scale-95 translate-y-2"
                            >
                                <div v-show="isProfileOpen" class="absolute right-0 mt-3 w-64 bg-white rounded-xl shadow-2xl ring-1 ring-black ring-opacity-5 py-2 z-50 origin-top-right">
                                    <div class="px-5 py-4 border-b border-gray-100">
                                        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Signed in as</p>
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ user.first_name }} {{ user.last_name }}</p>
                                        <p class="text-xs text-gray-400 truncate">{{ user.email }}</p>
                                    </div>
                                    
                                    <div class="py-1">
                                        <Link href="/profile" class="flex items-center gap-3 px-5 py-3 text-sm text-ink-light hover:bg-canvas hover:text-accent transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            My Profile
                                        </Link>
                                        <Link :href="route('orders.index')" class="flex items-center gap-3 px-5 py-3 text-sm text-ink-light hover:bg-canvas hover:text-accent transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                            My Orders
                                        </Link>
                                        <Link :href="route('dashboard.artworks.index')" class="flex items-center gap-3 px-5 py-3 text-sm text-ink-light hover:bg-canvas hover:text-accent transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            My Artworks
                                        </Link>
                                    </div>
                                    
                                    <div class="border-t border-gray-100 py-1">
                                        <Link href="/logout" method="post" as="button" class="flex w-full items-center gap-3 px-5 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                            Sign Out
                                        </Link>
                                    </div>
                                </div>
                            </transition>
                        </div>
                    </template>
                    <template v-else>
                         <Link href="/login" class="icon-link icon-user" title="Login">
                            <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="5"></circle><path d="M3 21c0-4.97 4.03-7 9-7s9 2.03 9 7"></path></svg>
                         </Link>
                    </template>
                </div>
            </div>
            <div class="navbar">
                <nav>
                    <ul>
                        <li><Link href="/">Home</Link></li>
                        <li><Link href="/shop">Shop</Link></li>
                        <li><Link href="/artists">Artists</Link></li>
                        <li><Link href="/about">About</Link></li>
                        <li><Link href="/contact">Contact</Link></li>
                    </ul>
                </nav>
            </div>
        </header>

        <main :class="{ 'pt-[140px]': withHeaderPadding }">
            <slot />
        </main>
        
        <Footer />
    </div>
</template>

<style scoped>
/* Paste relevant CSS from header.php here, adapted for Vue (remove .php references logic) */
:root{ --primary:#27272A; --text:#27272A; --bg:#FAFAFA; --highlight:#FFFFFF; --accent:#18181B; --accent-contrast:#fff; --muted:#E4E4E7 }

header { position: fixed; top: 0; left: 0; width: 100%; z-index: 1000; background: transparent; transition: background .35s cubic-bezier(0.4,0,0.2,1), box-shadow .35s cubic-bezier(0.4,0,0.2,1); }

.topbar { 
    display: flex; 
    align-items: center; 
    justify-content: space-between; 
    padding: 8px 50px; /* Compact padding to reduce excess height */
    background: rgba(255,255,255,0.90); 
    backdrop-filter: blur(8px); 
    transition: padding .35s cubic-bezier(0.4,0,0.2,1);
    position: relative;
    z-index: 20; /* Ensure logo sits above navbar */
    overflow: visible; /* Allow logo to bleed out */
}

.navbar { 
    display: flex; 
    justify-content: center; 
    background: #FFFFFF; 
    padding: 8px 50px; 
    border-top: 1px solid rgba(24,24,27,0.05); 
    transition: padding .35s cubic-bezier(0.4,0,0.2,1);
    position: relative;
    z-index: 10;
}

header.scrolled .topbar { padding: 4px 44px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
header.scrolled .navbar { padding: 4px 44px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }

/* Logo sizing and transition */
.brand {
    line-height: 0;
    display: block;
}

.brand img { 
    height: 72px; /* Layout height remains small to keep header compact */
    width: auto;
    display: block;
    /* Scale up to ~130px visual size (1.8x) */
    transform: scale(1.8); 
    transform-origin: left top; /* Anchor to top-left */
    transition: transform .35s cubic-bezier(0.4,0,0.2,1), height .35s cubic-bezier(0.4,0,0.2,1);
    margin-top: 4px; /* Slight nudge if needed */
}

header.scrolled .brand img { 
    height: 50px; /* Shrinks visually and physically */
    transform: scale(1); /* Reset scale so it fits inside the compact bar */
}

nav ul { list-style: none; display: flex; gap: 22px; margin: 0; padding: 0; }
nav ul li a { color: #1A1A1A; text-decoration: none; font-weight: 500; }

.icon-link svg { width: 24px; height: 24px; stroke: #222; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }

/* .profile-dropdown styles removed, replaced with tailwind in template */

.search-container { display: flex; align-items: center; position: relative; }
.search-input-wrapper { width: 0; opacity: 0; overflow: hidden; transition: all 0.4s ease-in-out; }
.search-input-wrapper.active { width: 250px; opacity: 1; margin-right: 10px; }
.search-input-wrapper input { width: 100%; padding: 8px 14px; border-radius: 20px; border: 2px solid #ddd; outline: none; }
.search-input-wrapper input:focus { border-color: #18181B; }

.header-icons { display: flex; align-items: center; gap: 20px; }
.cart-badge { position: relative; display: flex; align-items: center; }
.cart-badge span { 
    position: absolute; top: -8px; right: -8px; 
    background: #18181B; color: white; 
    font-size: 10px; font-weight: bold; 
    height: 16px; width: 16px; 
    border-radius: 50%; 
    display: flex; align-items: center; justify-content: center;
}

.profile-menu-container { position: relative; }

/* Responsive */
@media (max-width: 768px) {
    header { flex-direction: column; }
    .topbar, .navbar { padding: 6px 20px; }
    .header-icons { gap: 15px; }
    .brand img { height: 60px; transform: scale(1.5); } /* Smaller scale on mobile */
    header.scrolled .brand img { height: 40px; transform: scale(1); }
}
</style>

