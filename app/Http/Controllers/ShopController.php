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

        $artworks = $query->paginate(12)->withQueryString();

        return \Inertia\Inertia::render('Shop/Index', [
            'artworks' => $artworks,
            'filters' => $request->only(['search', 'category'])
        ]);
    }

    public function show(\App\Models\Artwork $artwork)
    {
        $artwork->load('artist');
        return \Inertia\Inertia::render('Shop/Show', [
            'artwork' => $artwork
        ]);
    }
}
