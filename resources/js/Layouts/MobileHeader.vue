<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref, nextTick } from 'vue';

const isSearchOpen = ref(false);
const searchQuery = ref('');
const searchInput = ref(null);

const toggleSearch = async () => {
    isSearchOpen.value = !isSearchOpen.value;
    if (isSearchOpen.value) {
        await nextTick();
        searchInput.value?.focus();
    } else {
        searchQuery.value = '';
    }
};

const handleSearch = () => {
    if (!searchQuery.value.trim()) return;
    router.get(route('shop.index'), { search: searchQuery.value });
    isSearchOpen.value = false;
};
</script>

<template>
    <header class="md:hidden sticky top-0 z-50 h-14 bg-white/80 backdrop-blur-md border-b border-gray-100 transition-all duration-300">
        <div class="h-full px-4 flex items-center justify-between relative overflow-hidden">
            
            <!-- Default View: Logo & Icons -->
            <div 
                class="absolute inset-0 px-4 flex items-center justify-between transition-transform duration-300"
                :class="isSearchOpen ? '-translate-y-full opacity-0' : 'translate-y-0 opacity-100'"
            >
                <!-- Logo -->
                <Link :href="route('home')" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-black text-white rounded-lg flex items-center justify-center font-serif font-bold italic text-lg shadow-sm">
                        M
                    </div>
                    <span class="font-serif font-bold text-lg tracking-tight">Musea</span>
                </Link>

                <!-- Actions -->
                <div class="flex items-center gap-4">
                    <button @click="toggleSearch" class="p-2 -mr-2 text-gray-600 hover:text-black transition-colors rounded-full active:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                    
                     <button class="p-2 -mr-2 text-gray-600 hover:text-black transition-colors rounded-full active:bg-gray-100 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span v-if="$page.props.auth.unreadNotificationsCount > 0" class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
                    </button>
                </div>
            </div>

            <!-- Expanded Search View -->
            <div 
                class="absolute inset-x-0 top-0 h-full bg-white px-4 flex items-center gap-2 transition-transform duration-300"
                :class="isSearchOpen ? 'translate-y-0' : 'translate-y-full'"
            >
                <div class="relative flex-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input 
                        ref="searchInput"
                        v-model="searchQuery"
                        @keydown.enter="handleSearch"
                        type="text" 
                        placeholder="Search art, artists..." 
                        class="w-full bg-gray-100 border-none rounded-full py-2 pl-9 pr-4 text-sm focus:ring-1 focus:ring-black focus:bg-white transition-all placeholder-gray-500"
                    >
                </div>
                <button 
                    @click="toggleSearch" 
                    class="text-sm font-bold text-gray-600 px-2 py-1 whitespace-nowrap active:opacity-70"
                >
                    Cancel
                </button>
            </div>

        </div>
    </header>
</template>

<style scoped>
/* Ensure smooth font rendering for the minimal UI */
</style>
