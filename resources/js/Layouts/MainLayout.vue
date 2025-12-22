<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const page = usePage();
const user = page.props.auth.user;
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

                        <div class="profile-menu-container">
                            <a href="#" @click.prevent="toggleProfile" class="icon-link icon-user">
                                <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="5"></circle><path d="M3 21c0-4.97 4.03-7 9-7s9 2.03 9 7"></path></svg>
                            </a>
                            
                            <div class="profile-dropdown" :class="{ active: isProfileOpen }">
                                <div class="profile-header">
                                    <div>
                                        <strong>{{ user.first_name }}</strong>
                                        <span>Welcome to Musea</span>
                                    </div>
                                </div>
                                <Link href="/profile" class="dropdown-item">My Profile</Link>
                                <Link href="/logout" method="post" as="button" class="dropdown-item logout-link">Logout</Link>
                            </div>
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

        <main>
            <slot />
        </main>
        
        <footer>
            <!-- Footer content -->
        </footer>
    </div>
</template>

<style scoped>
/* Paste relevant CSS from header.php here, adapted for Vue (remove .php references logic) */
:root{ --primary:#1A1A1A; --text:#2B2B2B; --bg:#FAF7F2; --highlight:#F7F1E3; --accent:#CBA35C; --accent-contrast:#fff; --muted:#F3E8D2 }

header { position: fixed; top: 0; left: 0; width: 100%; z-index: 1000; background: transparent; transition: background .35s; }
.topbar { display: flex; align-items: center; justify-content: space-between; padding: 8px 50px; background: rgba(255,255,255,0.96); backdrop-filter: blur(8px); }
.navbar { display: flex; justify-content: center; background: #F7F1E3; padding: 8px 50px; border-top: 1px solid rgba(203,163,92,0.25); }

header.scrolled .topbar { padding: 6px 44px; }
header.scrolled .navbar { padding: 6px 44px; }

.brand img { height: 72px; transition: transform .35s; }
header.scrolled .brand img { transform: scale(0.8); }

nav ul { list-style: none; display: flex; gap: 22px; margin: 0; padding: 0; }
nav ul li a { color: #1A1A1A; text-decoration: none; font-weight: 500; }

.icon-link svg { width: 24px; height: 24px; stroke: #222; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }

.profile-dropdown {
    position: absolute; right: 0; top: 100%; min-width: 220px; background: #fff;
    border-radius: 12px; box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    padding: 10px; visibility: hidden; opacity: 0; transform: translateY(10px); transition: all 0.2s;
}
.profile-dropdown.active { visibility: visible; opacity: 1; transform: translateY(0); pointer-events: auto; }

.search-container { display: flex; align-items: center; position: relative; }
.search-input-wrapper { width: 0; opacity: 0; overflow: hidden; transition: all 0.4s ease-in-out; }
.search-input-wrapper.active { width: 250px; opacity: 1; margin-right: 10px; }
.search-input-wrapper input { width: 100%; padding: 8px 14px; border-radius: 20px; border: 2px solid #ddd; outline: none; }
.search-input-wrapper input:focus { border-color: #CBA35C; }

.header-icons { display: flex; align-items: center; gap: 20px; }
.cart-badge { position: relative; display: flex; align-items: center; }
.cart-badge span { 
    position: absolute; top: -8px; right: -8px; 
    background: #CBA35C; color: white; 
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
}
</style>
