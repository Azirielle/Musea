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
        return Inertia::render('Admin/Approvals/Index', [
            'pendingArtworks' => Artwork::where('status', 'pending')
                ->with('artist:id,first_name,last_name')
                ->latest()
                ->get(),
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
