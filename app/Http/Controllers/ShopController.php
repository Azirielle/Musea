<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function visualSearch(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240', // 10MB limit
        ]);

        try {
            // Manual Config (Same as ArtworkController)
            \Cloudinary\Configuration\Configuration::instance('cloudinary://112719694583157:yGB2snsePNfMtODwrtjesYI9Jnw@du6bc1wjb?secure=true');

            // 1. Upload temp image
            $uploadApi = new \Cloudinary\Api\Upload\UploadApi();
            $result = $uploadApi->upload($request->file('image')->getRealPath(), [
                'folder' => 'temp_visual_search',
            ]);

            $publicId = $result['public_id'];
            $imageUrl = $result['secure_url'];

            // 2. Perform Visual Search using SearchApi
            // We search for images that are similar to the uploaded one
            // Note: This requires the "Visual Search" add-on or support in your Cloudinary plan.
            // If strictly using the 'search' API with expressions:

            $searchApi = new \Cloudinary\Api\Search\SearchApi();
            $searchResult = $searchApi
                ->expression("resource_type:image AND status:active AND folder:artworks/*")
                ->sortBy('similarity', 'desc')
                // .interaction('visual_search', ['image_url' => $imageUrl]) // Some SDKs use this
                // But generally for similarity you might use the Upload API 'similarity_search' or specialized endpoints.
                // However, standard Search API supports 'similar_to_image'?
                // Actually, correct PHP SDK usage for visual search often involves:
                // $search->expression("...")->withField("similarity_search", "url:...")?
                // Let's use the simplest reliable method: Upload API 'similarity_search' doesn't exist directly?
                // Actually, the Admin API or Search API has 'similar_to_image' parameter?
                // Let's try the modern expression: 'similar' is not a standard expression keyword without setup.
                // Wait, Cloudinary "Visual Search" usually implies:
                // search.expression('...').sort_by('similarity', 'desc').aggregate('format').execute() ??

                // ALTERNATIVE: Use the specific 'visual_search' endpoint if available or 'similar_image' param.
                // Since I can't easily debug the exact SDK version capabilities, I will use the *Admin API* to find similar resources 
                // OR better, try to use the raw Search API via the instance if the SDK wrapper is vague.

                // Let's look at the documentation pattern for PHP SDK v2:
                // (new SearchApi())->expression('...')->sortBy('similarity', 'desc')...
                // The key is HOW to pass the reference image.
                // Usually: ->add_param("similar_to_image", "id:".$publicId)

                // LET'S TRY THIS PATTERN:
                ->add_extra_param("similar_to_image", "id:" . $publicId)
                ->execute();

            /* 
               If the above specific method helper `add_extra_param` doesn't exist on the specific SearchApi class, 
               we might fallback to raw JSON POST if needed, but SDK usually has generic methods.
               Let's assume standard SearchApi usage. 
            */

        } catch (\Exception $e) {
            // Fallback or error
            return redirect()->back()->with('error', 'Visual search failed: ' . $e->getMessage());
        }

        // 3. Extract IDs
        // $searchResult['resources'] contains matches
        $resources = $searchResult['resources'] ?? [];

        // Map Cloudinary Public IDs (or filenames) back to Artworks.
        // Our Artworks generally store the full URL or a path.
        // We might need to match by extracted filename if strict.
        // Or, assume we can find them by parsing the result URL.

        $matches = [];
        foreach ($resources as $res) {
            // We can match loosely by filename if we trust uniqueness
            // $filename = pathinfo($res['public_id'], PATHINFO_FILENAME);
            // $matches[] = $filename;
            // OR match by full URL if possible?
            // Best is if we stored public_id in DB. We didn't explicitly store public_id in separate column, mostly image_url.
            // But image_url contains the public_id usually.

            // Strategy: Search Artworks where image_url LIKE '%public_id%'
            $matches[] = $res['public_id'];
        }

        if (empty($matches)) {
            return redirect()->route('shop.index')->with('error', 'No visually similar artworks found.');
        }

        // 4. Find Artworks
        // This query might be slow if standard SQL LIKE. 
        // Better: extract the unique part of public_id and search.

        // Let's pass the public_ids to the frontend (Shop/Index) or filter directly here?
        // The user wants "Return those specific artworks to the grid."
        // We can just query them here and pass to the Inertia render OR redirect to index with 'ids' param.
        // Direction: Redirect to index with ?visual_search_ids=...

        // Let's find IDs first to ensure valid IDs
        $artworkIds = \App\Models\Artwork::where(function ($q) use ($matches) {
            foreach ($matches as $mid) {
                $q->orWhere('image_url', 'LIKE', "%$mid%");
            }
        })->pluck('id')->toArray();

        return redirect()->route('shop.index', ['visual_search_ids' => $artworkIds]);
    }

    public function index(Request $request)
    {
        $query = \App\Models\Artwork::with('artist')->where('status', 'active');

        // Visual Search IDs
        if ($request->filled('visual_search_ids')) {
            $ids = is_array($request->input('visual_search_ids'))
                ? $request->input('visual_search_ids')
                : explode(',', $request->input('visual_search_ids'));

            // Preserve order of IDs for relevance
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
                $idsString = implode(',', $ids);
                $query->orderByRaw("FIELD(id, $idsString)");
            }
        }

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
            $category = $request->input('category');
            if (is_array($category)) {
                $query->whereIn('category', $category);
            } else {
                $query->where('category', $category);
            }
        }

        // Subcategory (Multiple support)
        if ($request->filled('subcategory')) {
            $subcategory = $request->input('subcategory');
            if (is_array($subcategory)) {
                $query->whereIn('subcategory', $subcategory);
            } else {
                $query->where('subcategory', $subcategory);
            }
        }

        // Ready to Hang
        if ($request->boolean('ready_to_hang')) {
            $query->where('ready_to_hang', true);
        }

        // Framing status
        if ($request->filled('framing')) {
            $framing = $request->input('framing');
            if (is_array($framing)) {
                $query->whereIn('framing', $framing);
            } else {
                $query->where('framing', $framing);
            }
        }

        // Artist ID (Multiple support)
        if ($request->filled('artist_id')) {
            $artistIds = $request->input('artist_id');
            if (is_array($artistIds)) {
                $query->whereIn('artist_id', $artistIds);
            } else {
                $query->where('artist_id', $artistIds);
            }
        }

        // Price Range
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }
        // Legacy range support
        if ($request->filled('price_range')) {
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

        // Orientation
        if ($request->filled('orientation')) {
            $orientation = $request->input('orientation');
            if (is_array($orientation)) {
                $query->whereIn('orientation', $orientation);
            } else {
                $query->where('orientation', $orientation);
            }
        }

        // Size Filter (Small, Medium, Large, Extra Large)
        // Buckets based on longest side (normalized to cm):
        // Small: < 40cm
        // Medium: 40-80cm
        // Large: 80-120cm
        // Extra Large: > 120cm
        if ($request->filled('size')) {
            $sizes = (array) $request->input('size');

            $query->where(function ($q) use ($sizes) {
                foreach ($sizes as $size) {
                    // Calculate longest side in CM using standard SQL for compatibility (SQLite matches MySQL/Postgres)
                    // We use a CASE statement standard instead of GREATEST (not in SQLite) or MAX (aggregate ambiguity)
                    $w = "CASE WHEN unit = 'in' THEN width * 2.54 ELSE width END";
                    $h = "CASE WHEN unit = 'in' THEN height * 2.54 ELSE height END";
                    $maxDimSql = "(CASE WHEN ($w) > ($h) THEN ($w) ELSE ($h) END)";

                    switch ($size) {
                        case 'Small':
                            $q->orWhereRaw("$maxDimSql < 40");
                            break;
                        case 'Medium':
                            $q->orWhereRaw("$maxDimSql >= 40 AND $maxDimSql < 80");
                            break;
                        case 'Large':
                            $q->orWhereRaw("$maxDimSql >= 80 AND $maxDimSql < 120");
                            break;
                        case 'Extra Large':
                            $q->orWhereRaw("$maxDimSql >= 120");
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

        $artworks = $query->paginate(12)->withQueryString();

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
