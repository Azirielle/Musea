<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'totalSales' => \App\Models\Order::sum('total_amount'),
                'netProfit' => \App\Models\Order::sum('total_amount') * 0.10,
                'newUsers' => \App\Models\User::whereDate('created_at', today())->count(),
                'pendingApprovals' => \App\Models\Artwork::where('status', 'pending')->count(),
            ],
        ]);
    }
}
