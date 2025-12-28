<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const page = usePage();

const links = [
    { name: 'Dashboard', route: 'admin.dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
    { name: 'Users', route: 'admin.users.index', icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z' },
    { name: 'Approvals', route: 'admin.approvals.index', icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' },
    { name: 'Sales', route: 'admin.sales.index', icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
    { name: 'Reports', route: 'admin.reports.index', icon: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' },
    { name: 'Coupons', route: 'admin.coupons.index', icon: 'M15 5v2a2 2 0 002 2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 00-2 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-2a2 2 0 00-2-2H9a2 2 0 002-2v-2a2 2 0 012-2h2a2 2 0 012 2z' },
    { name: 'Settings', route: 'admin.settings.index', icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z' },
];
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 hidden md:block shrink-0">
            <div class="h-16 flex items-center justify-center border-b border-gray-200 dark:border-gray-700">
                <Link :href="route('admin.dashboard')" class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <span class="text-indigo-600 font-serif">M</span> Musea Admin
                </Link>
            </div>
            
            <nav class="mt-5 px-4 space-y-1">
                <Link v-for="link in links" :key="link.name" :href="route(link.route)"
                    :class="[
                        route().current(link.route)
                            ? 'bg-indigo-50 text-indigo-700 dark:bg-gray-700 dark:text-white'
                            : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white',
                        'group flex items-center px-2 py-2 text-sm font-medium rounded-md'
                    ]">
                    <svg class="mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="link.icon" />
                    </svg>
                    {{ link.name }}
                </Link>
            </nav>

            <div class="absolute bottom-0 w-64 p-4 border-t border-gray-200 dark:border-gray-700">
                <div class="text-xs text-gray-500 text-center">
                    Port 8001 (Admin Mode)
                </div>
            </div>
        </aside>

        <!-- Mobile Header (Visible on small screens) -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <div class="md:hidden flex items-center justify-between bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 h-16 px-4">
                 <Link :href="route('admin.dashboard')" class="text-lg font-bold text-gray-800 dark:text-white">
                    Musea Admin
                </Link>
                <!-- Hamburger menu implementation would go here -->
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                 <!-- Page Heading -->
                <header class="mb-6" v-if="$slots.header">
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                        <slot name="header" />
                    </h1>
                </header>
                
                <slot />
            </main>
        </div>
    </div>
</template>
