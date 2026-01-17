<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artwork;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ArtworkController extends Controller
{
    public function index()
    {
        $pendingArtworks = Artwork::where('status', 'pending')
            ->with('artist:id,first_name,last_name')
            ->latest()
            ->get()
            ->map(function ($artwork) {
                return [
                    'id' => $artwork->id,
                    'type' => 'Artwork',
                    'title' => $artwork->title,
                    'subtitle' => 'by ' . ($artwork->artist ? $artwork->artist->first_name . ' ' . $artwork->artist->last_name : 'Unknown'),
                    'image' => $artwork->image_url, // Accessor handles logic
                    'created_at' => $artwork->created_at,
                    'model' => 'artwork',
                    'details' => [
                        'Description' => $artwork->description,
                        'Price' => '₱' . number_format((float) $artwork->price, 2),
                        'Dimensions' => "{$artwork->height} x {$artwork->width} " . ($artwork->depth ? "x {$artwork->depth} " : "") . $artwork->unit,
                        'Category' => $artwork->category,
                        'Orientation' => ucfirst($artwork->orientation),
                        'Stock' => $artwork->stock
                    ]
                ];
            });

        $pendingVerifications = \App\Models\User::where('verification_status', \App\Models\User::VERIFICATION_PENDING)
            ->latest()
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'type' => 'Artist Application',
                    'title' => $user->first_name . ' ' . $user->last_name,
                    'subtitle' => $user->email,
                    'image' => $user->imageUrl(), // Standard helper
                    'image_url' => $user->imageUrl(), // For consistency with Artwork
                    'created_at' => $user->created_at,
                    'model' => 'user',
                    'details' => [
                        'Email' => $user->email,
                        'Phone' => $user->phone ?? 'N/A',
                        'Address' => $user->address ?? 'N/A',
                        'Bio' => $user->bio ?? 'No bio provided.',
                        'Portfolio' => $user->portfolio_url ?? 'N/A'
                    ]
                ];
            });

        $approvals = $pendingArtworks->concat($pendingVerifications)->sortByDesc('created_at')->values();

        return Inertia::render('Admin/Approvals/Index', [
            'approvals' => $approvals,
        ]);
    }

    public function approve(Artwork $artwork)
    {
        $artwork->update(['status' => 'active']);

        $artwork->artist->notify(new \App\Notifications\ArtworkApprovedNotification($artwork));

        return back();
    }

    public function reject(Artwork $artwork)
    {
        $artwork->update(['status' => 'declined']);
        return back();
    }
}
