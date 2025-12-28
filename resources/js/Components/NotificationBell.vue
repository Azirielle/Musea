<script setup>
import { ref, computed } from 'vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const page = usePage();
const notifications = computed(() => page.props.auth.notifications || []);
const unreadCount = computed(() => page.props.auth.unreadCount || 0);

const markAsRead = (notification) => {
    router.post(route('notifications.read', notification.id), {}, {
        preserveScroll: true,
        preserveState: true,
    });
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return new Intl.RelativeTimeFormat('en', { numeric: 'auto' }).format(
        -Math.round((new Date() - date) / 1000 / 60), // simple minutes ago
        'minute'
    );
};
</script>

<template>
    <div class="relative">
        <Dropdown align="right" width="80">
            <template #trigger>
                <button
                    class="relative p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>

                    <!-- Unread Badge -->
                    <div v-if="unreadCount > 0"
                        class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] text-white">
                        {{ unreadCount }}
                    </div>
                </button>
            </template>

            <template #content>
                <div class="w-80 max-h-96 overflow-y-auto">
                    <div v-if="notifications.length === 0" class="px-4 py-3 text-sm text-gray-500 text-center">
                        No new notifications
                    </div>

                    <div v-for="notification in notifications" :key="notification.id"
                        class="border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors duration-150 relative group">
                        
                        <div @click="markAsRead(notification)"
                            :class="{ 'opacity-50': !!notification.read_at }"
                            class="block px-4 py-3 cursor-pointer">
                            
                            <div class="flex items-start">
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-800">
                                        {{ notification.data.title }}
                                    </p>
                                    <p class="text-xs text-gray-600 mt-1 line-clamp-2">
                                        {{ notification.data.message }}
                                    </p>
                                    <p class="text-[10px] text-gray-400 mt-2">
                                        <!-- Need a better date formatter really, but let's assume specific format or just simple js -->
                                         {{ new Date(notification.created_at).toLocaleDateString() }}
                                    </p>
                                </div>
                                <span v-if="!notification.read_at" class="h-2 w-2 bg-blue-500 rounded-full mt-1.5 flex-shrink-0"></span>
                            </div>
                            
                            <!-- Action Link Overlay or Button -->
                            <div v-if="notification.data.action_url" class="mt-2">
                                <Link :href="notification.data.action_url" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                    View Details &rarr;
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </Dropdown>
    </div>
</template>
