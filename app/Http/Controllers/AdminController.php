<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index()
    {
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

        // Sales Trend (Last 30 Days)
        $dailySales = \App\Models\Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->where('status', '!=', 'cancelled')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'totalSales' => \App\Models\Order::sum('total_amount'),
                'netProfit' => \App\Models\Order::sum('total_amount') * 0.10,
                'newUsers' => \App\Models\User::whereDate('created_at', today())->count(),
                'pendingApprovals' => \App\Models\Artwork::where('status', 'pending')->count(),
                'pendingVerifications' => \App\Models\User::where('verification_status', \App\Models\User::VERIFICATION_PENDING)->count(),
                'pendingWithdrawals' => \App\Models\WithdrawalRequest::where('status', 'pending')->count(),
                'activeArtists' => \App\Models\User::has('artworks')->count(),
            ],
            'charts' => [
                'monthlySales' => $monthlySales,
                'categorySales' => $categorySales,
                'dailySales' => $dailySales,
            ],
        ]);
    }
}
