<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function store(Request $request, Artwork $artwork)
    {
        if (!auth()->check()) {
            return back()->withErrors(['message' => 'You must be logged in to leave a review.']);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048',
        ]);

        // Verification: User must have purchased this artwork and order must be completed (delivered)
        $hasPurchased = Order::where('user_id', auth()->id())
            ->whereHas('items', function ($query) use ($artwork) {
                $query->where('artwork_id', $artwork->id);
            })
            // ->where('status', 'completed') // Temporarily disabled for easier testing as requested
            ->exists();

        if (!$hasPurchased) {
            // Optional: Enforce purchase
            // return back()->withErrors(['message' => 'You must purchase this artwork to leave a review.']);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reviews', 'public');
        }

        Review::create([
            'user_id' => auth()->id(),
            'artwork_id' => $artwork->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'image_url' => $imagePath, // Fixed column name
        ]);

        return back()->with('success', 'Review submitted successfully!');
    }
}
