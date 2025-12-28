<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    reports: Object,
});

const resolve = (id) => {
    if (confirm('Mark this report as resolved?')) {
        router.post(route('admin.reports.resolve', id));
    }
};
</script>

<template>
    <Head title="Disputes & Reports" />

    <AdminLayout>
        <template #header>Disputes & Reports</template>

        <div class="mb-6">
            <Link :href="route('admin.dashboard')" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Dashboard
            </Link>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Reason</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Reporter</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Details</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-200 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="report in reports.data" :key="report.id">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-sm text-gray-900 dark:text-white">{{ report.reason }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ report.reporter.first_name }} {{ report.reporter.last_name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300 break-words max-w-xs">{{ report.details || 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="[
                                        report.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800',
                                        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize'
                                    ]">
                                        {{ report.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button v-if="report.status === 'pending'" @click="resolve(report.id)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        Resolve
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    <Pagination :links="reports.links" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
