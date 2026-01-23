<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import SalesChart from '@/Components/Charts/SalesChart.vue';
import { computed } from 'vue';

const props = defineProps({
    stats: Object,
    charts: Object,
    trends: Object, // Added prop
});

// Format Currency Helper
const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(value);
};

// Trends prop is now used directly, no local mock needed.

// Chart Options for "Professional" Look
const areaChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: {
            mode: 'index',
            intersect: false,
            backgroundColor: '#1e293b',
            titleColor: '#f8fafc',
            bodyColor: '#f8fafc',
            borderColor: '#334155',
            borderWidth: 1,
            displayColors: false,
            padding: 10,
        }
    },
    scales: {
        x: {
            grid: { display: false },
            ticks: { font: { size: 10 }, color: '#94a3b8' }
        },
        y: {
            grid: { borderDash: [4, 4], color: '#e2e8f0' },
            ticks: { font: { size: 10 }, color: '#94a3b8', callback: (value) => '₱' + (value / 1000) + 'k' }
        }
    },
    interaction: {
        mode: 'nearest',
        axis: 'x',
        intersect: false
    }
};

const doughnutOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'right',
            labels: { usePointStyle: true, boxWidth: 8, font: { size: 11 }, color: '#64748b' }
        }
    },
    cutout: '70%',
};
</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout>
        <div class="px-6 py-8 w-full">
            <!-- Header Section -->
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">Dashboard Overview</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Welcome back, here's what's happening with your platform today.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-slate-500 bg-white dark:bg-slate-800 px-3 py-1.5 rounded-full border border-slate-200 dark:border-slate-700 shadow-sm">
                        Last updated: {{ stats.lastUpdated }}
                    </span>
                    <Link :href="route('admin.reports.index')" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        View Reports
                    </Link>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
                <!-- Card 1: Total Sales -->
                <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 relative overflow-hidden group">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Revenue</p>
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ formatCurrency(stats.totalSales) }}</h3>
                        </div>
                        <div class="p-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg text-indigo-600 dark:text-indigo-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <span :class="trends.sales.isPositive ? 'text-emerald-500' : 'text-rose-500'" class="flex items-center font-medium">
                            <svg v-if="trends.sales.isPositive" class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            <svg v-else class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                            {{ trends.sales.value }}
                        </span>
                        <span class="text-slate-400 ml-2">from last month</span>
                    </div>
                </div>

                 <!-- Card 2: Net Profit -->
                 <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 group">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Net Profit (10%)</p>
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ formatCurrency(stats.netProfit) }}</h3>
                        </div>
                        <div class="p-2 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg text-emerald-600 dark:text-emerald-400">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <span :class="trends.profit.isPositive ? 'text-emerald-500' : 'text-rose-500'" class="flex items-center font-medium">
                            <svg v-if="trends.profit.isPositive" class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            <svg v-else class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                            {{ trends.profit.value }}
                        </span>
                        <span class="text-slate-400 ml-2">from last month</span>
                    </div>
                </div>

                 <!-- Card 3: New Users -->
                 <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 group">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">New Users (Today)</p>
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ stats.newUsers }}</h3>
                        </div>
                         <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <span :class="trends.users.isPositive ? 'text-blue-500' : 'text-gray-500'" class="flex items-center font-medium">
                            {{ trends.users.value }}
                        </span>
                        <span class="text-slate-400 ml-2">vs yesterday</span>
                    </div>
                </div>

                <!-- Card 4: Pending Actions -->
                <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 group">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Pending Actions</p>
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ (stats.pendingApprovals || 0) + (stats.pendingVerifications || 0) + (stats.pendingWithdrawals || 0) }}</h3>
                        </div>
                        <div class="p-2 bg-amber-50 dark:bg-amber-900/30 rounded-lg text-amber-600 dark:text-amber-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                    </div>
                    <div class="flex items-center text-sm">
                        <span class="text-amber-500 font-medium">
                            {{ stats.pendingApprovals }} Approvals
                        </span>
                        <span class="mx-1 text-slate-300">|</span>
                        <span class="text-slate-500 dark:text-slate-400">
                            {{ stats.pendingWithdrawals }} Payouts
                        </span>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Area Chart: Monthly Revenue -->
                <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 lg:col-span-2">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Financial Overview</h3>
                        <div class="flex items-center space-x-2">
                             <span class="w-3 h-3 rounded-full bg-indigo-500"></span>
                             <span class="text-xs text-slate-500">Gross Sales</span>
                        </div>
                    </div>
                    <div class="h-80 w-full">
                        <SalesChart 
                            v-if="charts && charts.monthlySales"
                            type="line"
                            :data="{
                                labels: charts.monthlySales.map(item => item.month),
                                datasets: [{
                                    label: 'Revenue',
                                    data: charts.monthlySales.map(item => item.total),
                                    borderColor: '#6366f1', // indigo-500
                                    backgroundColor: 'rgba(99, 102, 241, 0.1)', // indigo-500 with opacity
                                    borderWidth: 2,
                                    pointBackgroundColor: '#fff',
                                    pointBorderColor: '#6366f1',
                                    fill: true,
                                    tension: 0.4
                                }]
                            }"
                            :options="areaChartOptions"
                        />
                         <div v-else class="h-full flex items-center justify-center text-slate-400">
                            No chart data available
                        </div>
                    </div>
                </div>

                <!-- Doughnut Chart: Categories -->
                <div class="bg-white dark:bg-slate-800 rounded-xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6">Sales by Category</h3>
                    <div class="h-64 relative">
                        <SalesChart 
                             v-if="charts && charts.categorySales"
                            type="doughnut"
                            :data="{
                                labels: charts.categorySales.map(item => item.category),
                                datasets: [{
                                    data: charts.categorySales.map(item => item.total),
                                    backgroundColor: [
                                        '#6366f1', // indigo
                                        '#8b5cf6', // violet
                                        '#ec4899', // pink
                                        '#f43f5e', // rose
                                        '#10b981', // emerald
                                        '#06b6d4', // cyan
                                    ],
                                    borderWidth: 0,
                                    hoverOffset: 4
                                }]
                            }"
                            :options="doughnutOptions"
                        />
                         <div v-else class="h-full flex items-center justify-center text-slate-400">
                            No category data
                        </div>
                    </div>
                    <div class="mt-6 flex flex-col gap-2">
                        <div class="text-center text-sm text-slate-500">
                            Most popular: <span class="font-medium text-slate-900 dark:text-white">{{ charts?.categorySales?.[0]?.category || 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity / Quick Links (Optional bottom row) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-indigo-600 rounded-xl p-6 text-white shadow-lg shadow-indigo-200 dark:shadow-none bg-gradient-to-br from-indigo-600 to-violet-700">
                     <h3 class="text-lg font-bold mb-2">Need to verify artists?</h3>
                     <p class="text-indigo-100 text-sm mb-4">There are {{ stats.pendingVerifications }} artists waiting for your approval to start selling.</p>
                     <Link :href="route('admin.verifications.index')" class="inline-block bg-white text-indigo-600 px-4 py-2 rounded-lg text-sm font-bold hover:bg-indigo-50 transition-colors">
                        Go to Verifications
                     </Link>
                </div>

                 <div class="bg-slate-900 dark:bg-slate-800 rounded-xl p-6 text-white shadow-lg">
                     <h3 class="text-lg font-bold mb-2">Platform Health</h3>
                     <div class="flex items-center justify-between mt-4">
                        <div>
                             <p class="text-slate-400 text-xs uppercase tracking-wider">System Status</p>
                             <p :class="stats.systemStatus === 'Operational' ? 'text-emerald-400' : 'text-red-400'" class="font-medium flex items-center gap-2 mt-1">
                                <span class="w-2 h-2 rounded-full animate-pulse" :class="stats.systemStatus === 'Operational' ? 'bg-emerald-400' : 'bg-red-400'"></span>
                                {{ stats.systemStatus }}
                             </p>
                        </div>
                        <div>
                             <p class="text-slate-400 text-xs uppercase tracking-wider">Database</p>
                             <p class="text-white font-medium mt-1">{{ stats.systemStatus === 'Operational' ? 'Connected' : 'Disconnected' }}</p>
                        </div>
                        <div>
                             <p class="text-slate-400 text-xs uppercase tracking-wider">Cache</p>
                             <p class="text-white font-medium mt-1">Optimal</p>
                        </div>
                     </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

