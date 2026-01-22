<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Sales & Profit Calculations
        $currentMonthStart = now()->startOfMonth();
        $prevMonthStart = now()->subMonth()->startOfMonth();
        $prevMonthEnd = now()->subMonth()->endOfMonth();

        // Revenue for this month vs last month
        $currentMonthSales = \App\Models\Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', $currentMonthStart)
            ->sum('total_amount');

        $lastMonthSales = \App\Models\Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$prevMonthStart, $prevMonthEnd])
            ->sum('total_amount');

        // Calculate Percentage Change
        $salesGrowth = 0;
        if ($lastMonthSales > 0) {
            $salesGrowth = (($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100;
        } elseif ($currentMonthSales > 0) {
            $salesGrowth = 100; // 100% growth if prev was 0
        }

        // 2. User Growth (Today vs Yesterday)
        $usersToday = \App\Models\User::whereDate('created_at', today())->count();
        $usersYesterday = \App\Models\User::whereDate('created_at', today()->subDay())->count();

        $userGrowth = 0;
        if ($usersYesterday > 0) {
            $userGrowth = (($usersToday - $usersYesterday) / $usersYesterday) * 100;
        } elseif ($usersToday > 0) {
            $userGrowth = 100;
        }

        // 3. Charts Data
        // Sales over time (Last 6 months)
        $isSqlite = \Illuminate\Support\Facades\DB::connection()->getDriverName() === 'sqlite';
        $monthExpression = $isSqlite ? 'strftime("%Y-%m", created_at)' : 'DATE_FORMAT(created_at, "%Y-%m")';

        $monthlySales = \App\Models\Order::selectRaw("$monthExpression as month, SUM(total_amount) as total")
            ->where('status', '!=', 'cancelled')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Sales by Category
        $categorySales = \App\Models\OrderItem::select('artworks.category', \Illuminate\Support\Facades\DB::raw('SUM(order_items.price_each) as total'))
            ->join('artworks', 'order_items.artwork_id', '=', 'artworks.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('artworks.category')
            ->get();

        // Sales Trend (Last 30 Days) for Sparkline
        $dailySales = \App\Models\Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->where('status', '!=', 'cancelled')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'totalSales' => \App\Models\Order::where('status', '!=', 'cancelled')->sum('total_amount'),
                'netProfit' => \App\Models\Order::where('status', '!=', 'cancelled')->sum('total_amount') * 0.10,
                'newUsers' => $usersToday,
                'pendingApprovals' => \App\Models\Artwork::where('status', 'pending')->count(),
                'pendingVerifications' => \App\Models\User::where('verification_status', \App\Models\User::VERIFICATION_PENDING)->count(),
                'pendingWithdrawals' => \App\Models\WithdrawalRequest::where('status', 'pending')->count(),
                'activeArtists' => \App\Models\User::has('artworks')->count(),
            ],
            'trends' => [
                'sales' => [
                    'value' => ($salesGrowth > 0 ? '+' : '') . number_format($salesGrowth, 1) . '%',
                    'isPositive' => $salesGrowth >= 0
                ],
                'profit' => [
                    'value' => ($salesGrowth > 0 ? '+' : '') . number_format($salesGrowth, 1) . '%', // Profit correlates with sales
                    'isPositive' => $salesGrowth >= 0
                ],
                'users' => [
                    'value' => ($userGrowth > 0 ? '+' : '') . number_format($userGrowth, 1) . '%',
                    'isPositive' => $userGrowth >= 0
                ]
            ],
            'charts' => [
                'monthlySales' => $monthlySales,
                'categorySales' => $categorySales,
                'dailySales' => $dailySales,
            ],
        ]);
    }
}
