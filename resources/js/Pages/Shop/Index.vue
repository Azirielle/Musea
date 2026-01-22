<script setup>
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import FilterSidebar from '@/Components/Shop/FilterSidebar.vue';
import UserBadge from '@/Components/UserBadge.vue';
import { ref, watch } from 'vue';
import { useCart } from '@/composables/useCart';

const props = defineProps({
    artworks: Object,
    filters: Object,
    recommendations: { type: Array, default: () => [] },
    artists: { type: Array, default: () => [] },
});

const { addToCart } = useCart();


// Filters State
const currentFilters = ref({ ...props.filters });
const sidebarContainer = ref(null);
const isFilterDrawerOpen = ref(false);


// Sorting
const sortBy = ref(props.filters.sort || 'newest');

const updateFilters = (newFilters) => {
    currentFilters.value = { ...currentFilters.value, ...newFilters };
    applyParams();
};

const handleSort = () => {
    applyParams();
};

// Custom Debounce Function
const debounce = (fn, delay) => {
    let timeoutId;
    return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => fn(...args), delay);
    };
};

const applyParams = debounce(() => {
    router.visit(route('shop.index'), {
        method: 'get',
        data: { 
            ...currentFilters.value, 
            sort: sortBy.value 
        },
        preserveState: true, 
        preserveScroll: true,
        replace: true 
    });
}, 600);

const formatPrice = (price) => {
    return Number(price).toLocaleString('en-PH', { 
        minimumFractionDigits: 2, 
        maximumFractionDigits: 2 
    });
};

</script>

<template>
    <Head title="Shop Artworks" />
    <MainLayout>
        <div class="bg-canvas min-h-screen pb-20 pt-10">
            <div class="max-w-[1400px] mx-auto px-6">
                
                <!-- Page Header -->
                <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
                    <div>
                        <p class="text-xs font-bold tracking-[0.2em] text-ink-light uppercase mb-2">The Collection</p>
                        <h1 class="text-4xl md:text-5xl font-serif font-bold text-ink italic">Discover Masterpieces</h1>
                    </div>
                    
                    <!-- Sort Dropdown -->
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-medium text-ink-light">Sort by:</span>
                        <div class="relative group">
                            <select 
                                v-model="sortBy"
                                @change="handleSort"
                                class="appearance-none bg-white border border-divider rounded-lg px-4 py-2 pr-8 text-sm font-bold text-ink focus:outline-none focus:border-accent cursor-pointer transition-colors hover:border-ink/30"
                            >
                                <option value="newest">Newest Arrivals</option>
                                <option value="price_asc">Price: Low to High</option>
                                <option value="price_desc">Price: High to Low</option>
                            </select>
                            <svg class="w-4 h-4 text-ink absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row gap-12">
                    <!-- Mobile Filter Logic -->
                    <div class="lg:hidden mb-6 flex justify-between items-center bg-white p-4 rounded-xl border border-divider shadow-sm">
                        <span class="text-sm font-bold text-ink">{{ artworks.data.length }} Artworks</span>
                        <button 
                            @click="isFilterDrawerOpen = true"
                            class="flex items-center gap-2 px-4 py-2 bg-ink text-white rounded-lg text-sm font-bold active:scale-95 transition-transform"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                            Filters
                        </button>
                    </div>

                    <!-- Sidebar (Desktop) -->
                    <aside class="hidden lg:block w-64 flex-shrink-0">
                        <div class="sticky top-32 bg-white rounded-xl shadow-sm border border-divider">
                             <div 
                                ref="sidebarContainer"
                                class="max-h-[calc(100vh-140px)] overflow-y-auto custom-scrollbar p-6"
                            >
                                <FilterSidebar 
                                    :filters="filters" 
                                    :artists="artists" 
                                    @update="updateFilters" 
                                />

                                <!-- Back to Top Button for Sidebar -->
                                <button 
                                    @click="sidebarContainer.scrollTo({ top: 0, behavior: 'smooth' })"
                                    class="w-full mt-8 flex items-center justify-center gap-2 py-3 rounded-lg text-xs font-bold text-ink-light uppercase tracking-wider hover:bg-canvas hover:text-ink transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                    Back to Top
                                </button>
                            </div>
                        </div>
                    </aside>

                    <!-- Mobile Filter Drawer -->
                    <transition
                        enter-active-class="transition duration-300 ease-out"
                        enter-from-class="translate-x-full opacity-0"
                        enter-to-class="translate-x-0 opacity-100"
                        leave-active-class="transition duration-200 ease-in"
                        leave-from-class="translate-x-0 opacity-100"
                        leave-to-class="translate-x-full opacity-0"
                    >
                        <div v-if="isFilterDrawerOpen" class="fixed inset-0 z-[200] lg:hidden">
                            <!-- Backdrop -->
                            <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" @click="isFilterDrawerOpen = false"></div>
                            
                            <!-- Drawer Content -->
                            <div class="absolute right-0 top-0 h-full w-[85%] max-w-sm bg-white shadow-2xl flex flex-col">
                                <div class="flex items-center justify-between p-5 border-b border-divider">
                                    <h2 class="text-lg font-serif font-bold text-ink">Filters</h2>
                                    <button @click="isFilterDrawerOpen = false" class="p-2 hover:bg-canvas rounded-full transition-colors">
                                        <svg class="w-6 h-6 text-ink" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                                
                                <div class="flex-1 overflow-y-auto p-6 pb-24">
                                    <FilterSidebar 
                                        :filters="filters" 
                                        :artists="artists" 
                                        @update="updateFilters" 
                                    />
                                </div>
                                
                                <div class="absolute bottom-0 left-0 w-full p-4 bg-white border-t border-divider flex gap-3">
                                    <button 
                                        @click="updateFilters({ search: '', category: [], subcategory: [], framing: [], ready_to_hang: false, price_min: null, price_max: null, artist_id: [], orientation: [] }); isFilterDrawerOpen = false"
                                        class="flex-1 py-3 bg-canvas text-ink font-bold rounded-xl hover:bg-gray-200 transition-colors"
                                    >
                                        Reset
                                    </button>
                                    <button 
                                        @click="isFilterDrawerOpen = false"
                                        class="flex-1 py-3 bg-ink text-white font-bold rounded-xl hover:bg-ink-light transition-colors"
                                    >
                                        Done
                                    </button>
                                </div>
                            </div>
                        </div>
                    </transition>

                    <!-- Main Grid -->
                    <div class="flex-1">
                        <!-- Active Filters Tags (Optional enhancement could go here) -->

                        <div v-if="artworks.data.length === 0" class="text-center py-32 bg-white rounded-3xl border border-dashed border-divider">
                            <svg class="w-12 h-12 mx-auto text-ink-light mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-ink text-lg font-medium">No artworks found.</p>
                            <p class="text-sm text-ink-light">Try adjusting your filters.</p>
                            <button @click="updateFilters({ search: '', category: [], subcategory: [], framing: [], ready_to_hang: false, price_min: null, price_max: null, artist_id: [], orientation: [] })" class="mt-4 text-accent hover:underline text-sm font-bold">Clear all filters</button>
                        </div>

                        <div v-else class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-x-8 gap-y-12">
                            <div v-for="artwork in artworks.data" :key="artwork.id" class="group">
                                <Link :href="route('shop.show', artwork.id)" class="block relative aspect-[4/5] bg-gray-100 overflow-hidden rounded-sm mb-4">
                                    <img 
                                        :src="artwork.image_url" 
                                        loading="lazy" 
                                        class="w-full h-full object-cover transition duration-700 group-hover:scale-105 will-change-transform"
                                        :class="{'grayscale opacity-50': artwork.stock <= 0}"
                                    >
                                    
                                    <!-- Badges -->
                                    <div class="absolute top-4 left-4 flex flex-col gap-2">
                                        <span v-if="artwork.stock <= 0" class="px-3 py-1 bg-white/90 backdrop-blur text-[10px] font-bold tracking-widest uppercase border border-ink/10">Sold Out</span>
                                        <span v-if="artwork.is_staff_pick" class="px-3 py-1 bg-accent text-white text-[10px] font-bold tracking-widest uppercase shadow-lg">Staff Pick</span>
                                    </div>

                                    <!-- Quick Actions Overlay -->
                                    <div class="absolute inset-0 bg-ink/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-4">
                                        <button 
                                            v-if="artwork.stock > 0"
                                            @click.prevent="addToCart(artwork)"
                                            class="bg-white text-ink w-12 h-12 rounded-full flex items-center justify-center hover:bg-accent hover:text-white transition-all shadow-xl transform translate-y-4 group-hover:translate-y-0 duration-300"
                                            title="Add to Cart"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                        </button>
                                        <button class="bg-white text-ink w-12 h-12 rounded-full flex items-center justify-center hover:bg-accent hover:text-white transition-all shadow-xl transform translate-y-4 group-hover:translate-y-0 duration-300 delay-75" title="Quick View">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </button>
                                    </div>
                                </Link>

                                <div class="space-y-1">
                                    <div class="flex justify-between items-start">
                                        <h3 class="font-serif font-bold text-lg text-ink truncate pr-4 group-hover:text-accent transition-colors"><Link :href="route('shop.show', artwork.id)">{{ artwork.title }}</Link></h3>
                                        <span class="font-black text-ink whitespace-nowrap">₱{{ formatPrice(artwork.price) }}</span>
                                    </div>
                                    <Link :href="route('artists.show', artwork.artist_id)" class="text-sm text-ink-light hover:text-ink transition-colors flex items-center">
                                        {{ artwork.artist ? artwork.artist.first_name + ' ' + artwork.artist.last_name : 'Unknown Artist' }}
                                        <UserBadge v-if="artwork.artist" :role="artwork.artist.role" :is-verified="!!artwork.artist.is_verified" />
                                    </Link>
                                    <p class="text-xs text-ink-light/60 uppercase tracking-wider">{{ artwork.category }} • {{ artwork.orientation || 'Variable' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-20 border-t border-divider pt-12 flex justify-center" v-if="artworks.links.length > 3">
                            <div class="flex items-center gap-2">
                                <template v-for="(link, k) in artworks.links" :key="k">
                                    <Link 
                                        v-if="link.url" 
                                        :href="link.url" 
                                        preserve-scroll
                                        preserve-state
                                        class="flex items-center justify-center text-sm font-bold transition-all rounded-full"
                                        :class="[
                                            link.label.includes('Previous') || link.label.includes('Next') 
                                                ? 'px-6 h-10 w-auto rounded-lg' 
                                                : 'w-10 h-10',
                                            link.active 
                                                ? 'bg-ink text-white shadow-lg' 
                                                : 'bg-white text-ink-light hover:bg-gray-100 hover:text-ink'
                                        ]"
                                    >
                                        <span v-html="link.label"></span>
                                    </Link>
                                    
                                    <span 
                                        v-else 
                                        class="flex items-center justify-center text-ink-light/40 text-sm font-bold"
                                        :class="link.label.includes('Previous') || link.label.includes('Next') ? 'px-6 h-10 w-auto' : 'w-10 h-10'"
                                    >
                                        <span v-html="link.label"></span>
                                    </span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recommendations Section -->
                <div v-if="recommendations.length > 0" class="mt-32 border-t border-divider pt-20">
                    <div class="flex items-center gap-4 mb-12">
                        <span class="w-12 h-px bg-accent"></span>
                        <h2 class="text-2xl font-serif font-bold italic text-ink">We Thought You'd Like These</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        <Link v-for="rec in recommendations" :key="rec.id" :href="route('shop.show', rec.id)" class="group block">
                            <div class="aspect-square bg-gray-100 overflow-hidden rounded-2xl mb-4 relative">
                                <img 
                                    :src="rec.image_url" 
                                    class="w-full h-full object-cover transition duration-500 group-hover:scale-110 opacity-90 group-hover:opacity-100"
                                >
                                <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm">
                                    Recommended
                                </div>
                            </div>
                            <h4 class="font-bold text-ink group-hover:text-accent">{{ rec.title }}</h4>
                            <p class="text-xs text-ink-light mt-1">₱{{ formatPrice(rec.price) }}</p>
                        </Link>
                    </div>
                </div>

            </div>
        </div>
    </MainLayout>
</template>
