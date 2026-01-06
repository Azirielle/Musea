<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Artwork::with('artist');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        if ($request->has('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->has('price_range')) {
            $range = $request->input('price_range');
            switch ($range) {
                case '0-5000':
                    $query->where('price', '<', 5000);
                    break;
                case '5000-20000':
                    $query->whereBetween('price', [5000, 20000]);
                    break;
                case '20000-plus':
                    $query->where('price', '>', 20000);
                    break;
            }
        }

        $artworks = $query->paginate(12)->withQueryString();

        return \Inertia\Inertia::render('Shop/Index', [
            'artworks' => $artworks,
            'filters' => $request->only(['search', 'category', 'price_range'])
        ]);
    }

    public function show(\App\Models\Artwork $artwork)
    {
        $artwork->load(['artist', 'reviews.user']);

        return \Inertia\Inertia::render('Shop/Show', [
            'artwork' => $artwork,
            'isFollowing' => auth()->check() ? auth()->user()->following()->where('following_id', $artwork->artist_id)->exists() : false,
            'isLiked' => auth()->check() ? auth()->user()->likes()->where('artwork_id', $artwork->id)->exists() : false,
        ]);
    }
}
