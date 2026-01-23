<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Users/Index', [
            'users' => User::select('id', 'first_name', 'last_name', 'email', 'status', 'created_at', 'balance', 'is_featured')->paginate(10),
        ]);
    }

    public function toggleStatus(User $user)
    {
        $user->status = $user->status === 'active' ? 'suspended' : 'active';
        $user->save();

        return back();
    }

    public function toggleFeatured(User $user)
    {
        $user->is_featured = !$user->is_featured;
        $user->save();

        return back();
    }

    public function history(User $user)
    {
        // Mock activity combining Orders, Artworks, and Auth events
        // In a real app, this would query an ActivityLog model or multiple tables unioned

        $activities = collect([]);

        // 1. Account Created
        $activities->push([
            'id' => 'reg_' . $user->id,
            'title' => 'Joined Musea',
            'type' => 'security',
            'timestamp' => $user->created_at->diffForHumans(),
            'created_at' => $user->created_at
        ]);

        // 2. Orders (Purchases)
        $user->orders()->latest()->take(5)->get()->each(function ($order) use ($activities) {
            $activities->push([
                'id' => 'ord_' . $order->id,
                'title' => "Placed Order #{$order->id}",
                'details' => "Total: ₱" . number_format($order->total_amount, 2),
                'type' => 'purchase',
                'timestamp' => $order->created_at->diffForHumans(),
                'created_at' => $order->created_at
            ]);
        });

        // 2b. Sales (As Artist)
        // Find OrderItems where the artwork belongs to this user
        \App\Models\OrderItem::whereHas('artwork', function ($q) use ($user) {
            $q->where('artist_id', $user->id);
        })->with(['artwork', 'order'])->latest()->take(10)->get()->each(function ($item) use ($activities) {
            $activities->push([
                'id' => 'sale_' . $item->id,
                'title' => "Sold Artwork: {$item->artwork->title}",
                'details' => "Earned: ₱" . number_format($item->price, 2),
                'type' => 'sale', // New type for styling
                'timestamp' => $item->created_at->diffForHumans(),
                'created_at' => $item->created_at
            ]);
        });

        // 3. Artworks Uploaded
        $user->artworks()->latest()->take(5)->get()->each(function ($artwork) use ($activities) {
            $activities->push([
                'id' => 'art_' . $artwork->id,
                'title' => "Uploaded Artwork: {$artwork->title}",
                'type' => 'upload',
                'timestamp' => $artwork->created_at->diffForHumans(),
                'created_at' => $artwork->created_at
            ]);
        });

        // 4. Verification
        if ($user->is_verified || $user->verification_status === 'approved') {
            $activities->push([
                'id' => 'ver_' . $user->id,
                'title' => 'Identity Verified',
                'type' => 'security',
                'timestamp' => $user->email_verified_at->diffForHumans(),
                'created_at' => $user->email_verified_at
            ]);
        }

        // Sort by date desc
        $sortedActivities = $activities->sortByDesc('created_at')->values()->all();

        return response()->json([
            'activities' => $sortedActivities
        ]);
    }
}
