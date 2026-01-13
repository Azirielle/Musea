<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

import SalesChart from '@/Components/Charts/SalesChart.vue';

defineProps({
    stats: Object,
    charts: Object,
});
</script>

<template>
    <Head title="Admin Dashboard" />

    <AdminLayout>
        <template #header>Dashboard</template>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Sales -->
            <Link :href="route('admin.sales.index')" class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg p-6 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition block">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Sales (GMV)</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">₱{{ stats.totalSales.toLocaleString() }}</p>
                    </div>
                </div>
            </Link>

            <!-- Net Profit -->
            <Link :href="route('admin.sales.index')" class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg p-6 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition block">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600 dark:bg-purple-900 dark:text-purple-300">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Net Profit (10%)</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">₱{{ stats.netProfit.toLocaleString() }}</p>
                    </div>
                </div>
            </Link>

            <!-- New Users -->
            <Link :href="route('admin.users.index')" class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg p-6 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition block">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900 dark:text-blue-300">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">New Users Today</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.newUsers }}</p>
                    </div>
                </div>
            </Link>

            <!-- Pending Approvals (Merged) -->
            <Link :href="route('admin.approvals.index')" class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg p-6 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition block">
                 <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 dark:bg-yellow-900 dark:text-yellow-300">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Approvals</p>
                        <!-- Summing stats here since controller might separate them still, or user can update controller next -->
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ (stats.pendingApprovals || 0) + (stats.pendingVerifications || 0) }}</p>
                        <p v-if="stats.pendingVerifications > 0" class="text-xs text-gray-400 mt-1">
                            ({{ stats.pendingApprovals }} Artworks, {{ stats.pendingVerifications }} Artists)
                        </p>
                    </div>
                </div>
            </Link>

            <!-- Pending Withdrawals -->
            <Link :href="route('admin.withdrawals.index')" class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg p-6 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition block">
                 <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-100 text-orange-600 dark:bg-orange-900 dark:text-orange-300">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Withdrawals</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.pendingWithdrawals || 0 }}</p>
                    </div>
                </div>
            </Link>

             <!-- Active Artists -->
             <Link :href="route('admin.users.index')" class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg p-6 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition block">
                 <div class="flex items-center">
                    <div class="p-3 rounded-full bg-teal-100 text-teal-600 dark:bg-teal-900 dark:text-teal-300">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Artists</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.activeArtists || 0 }}</p>
                    </div>
                </div>
            </Link>
            
        </div>

        <!-- Analytics Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8" v-if="charts">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Monthly Sales Revenue</h3>
                <div class="h-64">
                    <SalesChart 
                        type="line"
                        :data="{
                            labels: charts.monthlySales.map(item => item.month),
                            datasets: [{
                                label: 'Revenue',
                                data: charts.monthlySales.map(item => item.total),
                                borderColor: '#818cf8',
                                backgroundColor: '#818cf8',
                                tension: 0.4
                            }]
                        }"
                    />
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Sales by Category</h3>
                <div class="h-64">
                    <SalesChart 
                        type="doughnut"
                        :data="{
                            labels: charts.categorySales.map(item => item.category),
                            datasets: [{
                                data: charts.categorySales.map(item => item.total),
                                backgroundColor: [
                                    '#f87171', '#fb923c', '#fbbf24', '#a3e635', 
                                    '#34d399', '#22d3ee', '#818cf8', '#e879f9'
                                ]
                            }]
                        }"
                    />
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-white">Quick Links</h3>
                 <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <Link :href="route('admin.users.index')" class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition text-center text-gray-700 dark:text-gray-200 font-medium">
                        Manage Users
                    </Link>
                    <Link :href="route('admin.sales.index')" class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition text-center text-gray-700 dark:text-gray-200 font-medium">
                        View Sales
                    </Link>
                     <Link :href="route('admin.reports.index')" class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition text-center text-gray-700 dark:text-gray-200 font-medium">
                        Resolve Disputes
                    </Link>
                    <Link :href="route('admin.coupons.index')" class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition text-center text-gray-700 dark:text-gray-200 font-medium">
                        Manage Coupons
                    </Link>
                     <Link :href="route('admin.settings.index')" class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition text-center text-gray-700 dark:text-gray-200 font-medium">
                        Platform Settings
                    </Link>
                 </div>
            </div>
        </div>
    </AdminLayout>
</template>
