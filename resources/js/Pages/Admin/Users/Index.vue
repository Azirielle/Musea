<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue';
import UserHistoryModal from '@/Pages/Admin/Components/UserHistoryModal.vue';
import axios from 'axios';

// Props
const props = defineProps({
    users: Object, // Paginated
    filters: Object, // { search, status } passed from controller if any
});

// State for Filters
const search = ref(props.filters?.search || '');
const statusFilter = ref(props.filters?.status || '');

// History Modal State
const showHistoryModal = ref(false);
const selectedUser = ref({});
const historyActivities = ref([]);
const loadingHistory = ref(false);

const openHistory = async (user) => {
    selectedUser.value = user;
    showHistoryModal.value = true;
    loadingHistory.value = true;
    historyActivities.value = [];

    try {
        const response = await axios.get(route('admin.users.history', user.id));
        historyActivities.value = response.data.activities;
    } catch (error) {
        console.error("Failed to fetch history", error);
    } finally {
        loadingHistory.value = false;
    }
};

// Debounce Search
let timeout = null;
watch([search, statusFilter], () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get(route('admin.users.index'), { 
            search: search.value, 
            status: statusFilter.value 
        }, { 
            preserveState: true, 
            preserveScroll: true,
            replace: true 
        });
    }, 300);
});

// Actions
const toggleStatus = (user) => {
    const action = user.status === 'active' ? 'suspend' : 'activate';
    if (confirm(`Are you sure you want to ${action} ${user.first_name}?`)) {
        router.post(route('admin.users.toggle', user.id));
    }
};

const toggleFeatured = (user) => {
    router.post(route('admin.users.featured', user.id), {}, { preserveScroll: true });
};

// Formatting
const formatCurrency = (val) => {
    return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(val);
};

const getAvatarUrl = (user) => {
    return `https://ui-avatars.com/api/?name=${user.first_name}+${user.last_name}&background=random&color=fff`;
}
</script>

<template>
    <Head title="User Management" />

    <AdminLayout>
        <!-- Header / Toolbar -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Users</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Manage platform users, artists, and their accounts.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <!-- Search -->
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input 
                        v-model="search" 
                        type="text" 
                        class="block w-full sm:w-64 pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition duration-150 ease-in-out dark:bg-slate-800 dark:border-slate-700 dark:text-white"
                        placeholder="Search name or email..." 
                    />
                </div>

                <!-- Filter -->
                <select 
                    v-model="statusFilter" 
                    class="block w-full sm:w-40 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg dark:bg-slate-800 dark:border-slate-700 dark:text-white"
                >
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="suspended">Suspended</option>
                </select>
                
                <!-- Export (Mock) -->
                <button class="flex items-center justify-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 dark:bg-slate-800 dark:border-slate-700 dark:text-white dark:hover:bg-slate-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export
                </button>
            </div>
        </div>

        <!-- Data Grid -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                    <thead class="bg-gray-50 dark:bg-slate-900/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">User</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">Featured</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Role</th> <!-- Added Role col if avail, else placeholder -->
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Balance</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                        <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors duration-150 group">
                            <!-- User Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0">
                                        <img class="h-10 w-10 rounded-full object-cover border border-gray-200 dark:border-slate-600" :src="getAvatarUrl(user)" alt="" />
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 transition-colors">
                                            {{ user.first_name }} {{ user.last_name }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ user.email }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Featured Column -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button 
                                    @click="toggleFeatured(user)"
                                    class="p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-slate-700 transition focus:outline-none"
                                    :title="user.is_featured ? 'Remove from Featured' : 'Mark as Featured'"
                                >
                                    <svg class="w-5 h-5 transition-colors duration-200" :class="user.is_featured ? 'text-yellow-400 fill-current' : 'text-gray-300 hover:text-yellow-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                </button>
                            </td>

                            <!-- Status Column -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full min-w-[80px] justify-center"
                                    :class="user.status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'">
                                    {{ user.status }}
                                </span>
                            </td>
                            
                             <!-- Role (Using created_at as proxy if role missing, or static) -->
                             <td class="px-6 py-4 whitespace-nowrap text-left text-sm text-gray-500 dark:text-gray-400">
                                {{ user.role || 'Artist' }}
                            </td>

                            <!-- Balance Column -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-mono text-gray-700 dark:text-gray-300">
                                {{ formatCurrency(user.balance) }}
                            </td>

                            <!-- Actions Column -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <Menu as="div" class="relative inline-block text-left">
                                    <MenuButton class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-slate-700 text-gray-400 hover:text-gray-600 transition focus:outline-none">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                    </MenuButton>

                                    <transition
                                        enter-active-class="transition duration-100 ease-out"
                                        enter-from-class="transform scale-95 opacity-0"
                                        enter-to-class="transform scale-100 opacity-100"
                                        leave-active-class="transition duration-75 ease-in"
                                        leave-from-class="transform scale-100 opacity-100"
                                        leave-to-class="transform scale-95 opacity-0"
                                    >
                                        <MenuItems class="absolute right-0 mt-2 w-48 origin-top-right divide-y divide-gray-100 rounded-lg bg-white dark:bg-slate-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                            <div class="px-1 py-1">
                                                <MenuItem v-slot="{ active }">
                                                    <button @click="openHistory(user)" 
                                                        :class="[active ? 'bg-indigo-50 dark:bg-slate-700 text-indigo-600 dark:text-white' : 'text-gray-700 dark:text-gray-300', 'group flex w-full items-center rounded-md px-2 py-2 text-sm']">
                                                        <svg class="mr-2 h-4 w-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                        View History
                                                    </button>
                                                </MenuItem>
                                                <MenuItem v-slot="{ active }">
                                                    <button @click="toggleStatus(user)" 
                                                        :class="[active ? 'bg-red-50 dark:bg-red-900/20 text-red-600' : 'text-gray-700 dark:text-gray-300', 'group flex w-full items-center rounded-md px-2 py-2 text-sm']">
                                                        <svg v-if="user.status === 'active'" class="mr-2 h-4 w-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                        <svg v-else class="mr-2 h-4 w-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        {{ user.status === 'active' ? 'Suspend User' : 'Activate User' }}
                                                    </button>
                                                </MenuItem>
                                            </div>
                                        </MenuItems>
                                    </transition>
                                </Menu>
                            </td>
                        </tr>
                        
                         <tr v-if="users.data.length === 0">
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No users found</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Try adjusting your search or filters.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Footer / Pagination -->
            <div class="bg-gray-50 dark:bg-slate-900/50 px-6 py-4 border-t border-gray-200 dark:border-slate-700">
                <Pagination :links="users.links" />
            </div>
        </div>
        
        <!-- User History Modal -->
        <UserHistoryModal 
            :show="showHistoryModal" 
            :user="selectedUser" 
            :activities="historyActivities" 
            :loading="loadingHistory"
            @close="showHistoryModal = false"
        />
    </AdminLayout>
</template>
