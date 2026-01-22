<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';

// Props
const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    user: {
        type: Object,
        default: () => ({})
    },
    activities: {
        type: Array,
        default: () => []
    },
    loading: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['close']);

const close = () => {
    emit('close');
};

// Icon Mapping Helper
const getIcon = (type) => {
    switch (type) {
        case 'sales':
        case 'purchase':
            return {
                bg: 'bg-green-100 text-green-600',
                path: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
            };
        case 'security':
        case 'login':
            return {
                bg: 'bg-red-100 text-red-600',
                path: 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'
            };
        case 'edit':
        case 'update':
            return {
                bg: 'bg-blue-100 text-blue-600',
                path: 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'
            };
        case 'upload':
        case 'artwork':
            return {
                bg: 'bg-purple-100 text-purple-600',
                path: 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'
            };
        default:
            return {
                bg: 'bg-gray-100 text-gray-600',
                path: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
            };
    }
};

// Avatar Helper
const getAvatarUrl = (user) => {
    if (!user || !user.first_name) return '';
    return `https://ui-avatars.com/api/?name=${user.first_name}+${user.last_name}&background=random&color=fff`;
}
</script>

<template>
    <TransitionRoot appear :show="show" as="template">
        <Dialog as="div" @close="close" class="relative z-[100]">
            <!-- Backdrop -->
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel class="w-full max-w-2xl transform overflow-hidden rounded-xl bg-white dark:bg-slate-800 text-left align-middle shadow-xl transition-all border border-slate-200 dark:border-slate-700">
                            
                            <!-- Header (Sticky-ish feel via layout) -->
                            <div class="flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800">
                                <div class="flex items-center space-x-4">
                                    <img :src="getAvatarUrl(user)" alt="User Avatar" class="h-12 w-12 rounded-full border-2 border-slate-100 dark:border-slate-600">
                                    <div>
                                        <DialogTitle as="h3" class="text-lg font-bold leading-6 text-slate-900 dark:text-white">
                                            {{ user.first_name }} {{ user.last_name }}
                                        </DialogTitle>
                                        <p class="text-xs text-slate-500 font-medium">Activity History</p>
                                    </div>
                                </div>
                                <button @click="close" class="rounded-full p-2 text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-500 transition-colors focus:outline-none">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Body (Timeline) -->
                            <div class="p-6 max-h-[60vh] overflow-y-auto custom-scrollbar bg-slate-50/50 dark:bg-slate-800/50">
                                
                                <!-- Loading Skeleton -->
                                <div v-if="loading" class="space-y-6 animate-pulse">
                                    <div v-for="i in 4" :key="i" class="flex gap-4">
                                        <div class="flex flex-col items-center">
                                            <div class="h-8 w-8 rounded-full bg-slate-200 dark:bg-slate-700"></div>
                                            <div class="h-full w-0.5 bg-slate-200 dark:bg-slate-700 mt-2"></div>
                                        </div>
                                        <div class="flex-1 space-y-2 pt-1">
                                            <div class="h-4 bg-slate-200 dark:bg-slate-700 rounded w-3/4"></div>
                                            <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-1/4"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Empty State -->
                                <div v-else-if="activities.length === 0" class="text-center py-12">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-700 mb-4">
                                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <p class="text-slate-500 dark:text-slate-400 font-medium">No activity recorded yet.</p>
                                </div>

                                <!-- Timeline -->
                                <div v-else class="relative space-y-0">
                                    <div v-for="(activity, index) in activities" :key="activity.id" class="relative flex gap-6 pb-8 last:pb-0 group">
                                        
                                        <!-- Vertical Line -->
                                        <div 
                                            v-if="index !== activities.length - 1" 
                                            class="absolute left-[15px] top-8 bottom-0 w-0.5 bg-slate-200 dark:bg-slate-700 group-last:hidden"
                                        ></div>

                                        <!-- Icon Bubble -->
                                        <div class="relative flex-shrink-0">
                                            <span 
                                                class="flex h-8 w-8 items-center justify-center rounded-full ring-4 ring-white dark:ring-slate-800"
                                                :class="getIcon(activity.type).bg"
                                            >
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" :d="getIcon(activity.type).path" />
                                                </svg>
                                            </span>
                                        </div>

                                        <!-- Content -->
                                        <div class="flex-1 pt-0.5">
                                            <div class="flex justify-between items-start">
                                                <p class="text-sm font-bold text-slate-900 dark:text-gray-100">
                                                    {{ activity.title }}
                                                </p>
                                                <span class="text-xs text-slate-400 whitespace-nowrap ml-2">
                                                    {{ activity.timestamp }}
                                                </span>
                                            </div>
                                            <p v-if="activity.details" class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                                {{ activity.details }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            
                            <!-- Footer -->
                            <div class="bg-gray-50 dark:bg-slate-900/50 px-6 py-4 flex justify-end">
                                <button ref="closeButton" type="button" class="inline-flex justify-center rounded-lg border border-transparent bg-slate-900 dark:bg-slate-700 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800 dark:hover:bg-slate-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-500 focus-visible:ring-offset-2 transition-colors" @click="close">
                                    Done
                                </button>
                            </div>

                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<style scoped>
/* Custom Scrollbar for the modal body */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 20px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #475569;
}
</style>
