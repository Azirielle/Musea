<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Swal from 'sweetalert2';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    settings: Object,
});

const form = useForm({
    commission_rate: props.settings.commission_rate || 10,
    site_maintenance: props.settings.site_maintenance === '1' || props.settings.site_maintenance === true,
});

const updateSettings = () => {
    form.post(route('admin.settings.update'), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Settings updated!',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        },
    });
};
</script>

<template>
    <Head title="Platform Settings" />

    <AdminLayout>
        <div class="max-w-4xl mx-auto py-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white mb-4">Platform Settings</h1>
                <Link :href="route('admin.dashboard')" class="text-indigo-400 hover:text-indigo-300 flex items-center gap-2 transition-colors group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:-translate-x-1 transition-transform" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back to Dashboard
                </Link>
            </div>

            <div class="bg-[#1F2937] rounded-2xl shadow-xl border border-gray-700/50 overflow-hidden">
                <div class="p-8">
                    <form @submit.prevent="updateSettings" class="space-y-8">
                        
                        <div class="group">
                            <label class="block text-sm font-bold text-gray-200 mb-1 group-focus-within:text-indigo-400 transition-colors">Commission Rate (%)</label>
                            <p class="text-xs text-gray-400 mb-4">The percentage required from every sale.</p>
                            <div class="relative">
                                <input 
                                    v-model="form.commission_rate" 
                                    type="number" 
                                    min="0" 
                                    max="100" 
                                    class="block w-full bg-[#374151] border-gray-600 border-2 rounded-xl text-white py-3 px-4 focus:ring-0 focus:border-indigo-500 transition-all font-mono"
                                >
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                                    %
                                </div>
                            </div>
                            <div v-if="form.errors.commission_rate" class="text-red-400 text-xs mt-2 font-bold">{{ form.errors.commission_rate }}</div>
                        </div>

                        <div class="bg-[#374151]/30 p-6 rounded-xl border border-gray-700">
                            <div class="flex items-start gap-4">
                                <div class="pt-1">
                                    <input 
                                        v-model="form.site_maintenance" 
                                        id="maintenance" 
                                        type="checkbox" 
                                        class="h-5 w-5 text-indigo-500 focus:ring-offset-0 focus:ring-0 bg-[#374151] border-gray-600 rounded-md cursor-pointer"
                                    >
                                </div>
                                <div>
                                    <label for="maintenance" class="block text-sm font-bold text-gray-200 cursor-pointer">
                                        Maintenance Mode
                                    </label>
                                    <p class="text-xs text-gray-400 mt-1">If enabled, the main site will show a maintenance page.</p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 flex items-center justify-between">
                            <button 
                                type="submit" 
                                :disabled="form.processing" 
                                class="inline-flex items-center justify-center px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-500/20 transition-all active:scale-95 disabled:opacity-50"
                            >
                                <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Save Changes
                            </button>

                            <p v-if="form.recentlySuccessful" class="text-emerald-400 text-sm font-bold animate-pulse">
                                Changes saved successfully!
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
