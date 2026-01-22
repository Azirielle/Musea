<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopController extends Controller
{


    public function index(Request $request)
    {
        $query = \App\Models\Artwork::with('artist')->where('status', 'active');



        // Search (Unified 'search' or 'query')
        if ($request->has('search') || $request->has('query')) {
            $search = $request->input('search') ?? $request->input('query');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('artist', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        // Category / Medium (Multiple support)
        if ($request->filled('category')) {
            $categories = (array) $request->input('category');
            $query->whereIn('category', $categories);
        }

        // Style
        if ($request->filled('style')) {
            $styles = (array) $request->input('style');
            $query->whereIn('style', $styles);
        }

        // Subject
        if ($request->filled('subject')) {
            $subjects = (array) $request->input('subject');
            $query->whereIn('subject', $subjects);
        }

        // Medium
        if ($request->filled('medium')) {
            $mediums = (array) $request->input('medium');
            $query->whereIn('medium', $mediums);
        }

        // Legacy Subcategory (Optional, for backward compat if needed)
        // if ($request->filled('subcategory')) { ... }

        // Ready to Hang
        if ($request->boolean('ready_to_hang')) {
            $query->where('ready_to_hang', true);
        }

        // Framing status
        if ($request->filled('framing')) {
            $framing = (array) $request->input('framing');
            $query->whereIn('framing', $framing);
        }

        // Artist ID (Multiple support)
        if ($request->filled('artist_id')) {
            $artistIds = (array) $request->input('artist_id');
            $query->whereIn('artist_id', $artistIds);
        }

        // Price Range
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        // Orientation
        if ($request->filled('orientation')) {
            $orientation = (array) $request->input('orientation');
            $query->whereIn('orientation', $orientation);
        }

        // Size Filter
        if ($request->filled('size')) {
            $sizes = (array) $request->input('size');
            $query->where(function ($q) use ($sizes) {
                foreach ($sizes as $size) {
                    $w = "CASE WHEN unit = 'in' THEN width * 2.54 ELSE width END";
                    $h = "CASE WHEN unit = 'in' THEN height * 2.54 ELSE height END";
                    // Correct logic for max dimension in SQLite/MySQL compatible way
                    $maxDimSql = "CASE WHEN ($w) > ($h) THEN ($w) ELSE ($h) END";

                    switch ($size) {
                        case 'Small':
                            $q->orWhereRaw("($maxDimSql) < 40");
                            break;
                        case 'Medium':
                            $q->orWhereRaw("($maxDimSql) >= 40 AND ($maxDimSql) < 80");
                            break;
                        case 'Large':
                            $q->orWhereRaw("($maxDimSql) >= 80 AND ($maxDimSql) < 120");
                            break;
                        case 'Extra Large':
                            $q->orWhereRaw("($maxDimSql) >= 120");
                            break;
                    }
                }
            });
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $artworks = $query->paginate(12)->withQueryString()->setPath(route('shop.index'));

        // Recommendations (Basic Logic: Same category as search or random if empty)
        $recommendations = [];
        if ($artworks->isEmpty() || $request->filled('search')) {
            // If search yielded no results OR user is searching, show trending/random
            $recommendations = \App\Models\Artwork::where('status', 'active')
                ->where('stock', '>', 0)
                ->inRandomOrder()
                ->take(4)
                ->get();
        } else {
            // Show recommendations based on current category filter if exists
            if ($request->filled('category')) {
                $recommendations = \App\Models\Artwork::where('status', 'active')
                    ->where('stock', '>', 0)
                    ->where('category', $request->input('category'))
                    ->whereNotIn('id', $artworks->pluck('id'))
                    ->inRandomOrder()
                    ->take(4)
                    ->get();
            }
        }

        return \Inertia\Inertia::render('Shop/Index', [
            'artworks' => $artworks,
            'filters' => $request->all(),
            'recommendations' => $recommendations,
            'artists' => \App\Models\User::whereHas('artworks', function ($q) {
                $q->where('status', 'active');
            })->select('id', 'first_name', 'last_name')->get()->map(function ($u) {
                return ['id' => $u->id, 'name' => $u->first_name . ' ' . $u->last_name];
            }),
        ]);
    }

    public function suggestions(Request $request)
    {
        $query = $request->input('query');
        if (!$query)
            return response()->json([]);

        $artworks = \App\Models\Artwork::where('title', 'like', "%{$query}%")
            ->where('status', 'active')
            ->take(20)
            ->get(['id', 'title', 'image_url', 'price']);

        $artists = \App\Models\User::where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->take(20)
            ->get(); // Get full model to use imageUrl() helper

        return response()->json([
            'artworks' => $artworks,
            'artists' => $artists->map(function ($artist) {
                return [
                    'id' => $artist->id,
                    'name' => $artist->first_name . ' ' . $artist->last_name,
                    'avatar' => $artist->imageUrl()
                ];
            })
        ]);
    }

    public function show(\App\Models\Artwork $artwork)
    {
        $artwork->load(['artist', 'reviews.user']);

        $isFollowing = false;
        $isLiked = false;

        if (auth()->check()) {
            try {
                // Wrap in try-catch to prevent crash if tables (follows/likes) are missing
                $isFollowing = auth()->user()->following()->where('following_id', $artwork->artist_id)->exists();
                $isLiked = auth()->user()->likes()->where('artwork_id', $artwork->id)->exists();
            } catch (\Exception $e) {
                // Silent fail to allow page load
            }
        }

        return \Inertia\Inertia::render('Shop/Show', [
            'artwork' => $artwork,
            'isFollowing' => $isFollowing,
            'isLiked' => $isLiked,
        ]);
    }
}
