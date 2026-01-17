<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ArtworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $artworks = auth()->user()->artworks()->latest()->paginate(10);

        return Inertia::render('Dashboard/Artworks/Index', [
            'artworks' => $artworks
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth()->user()->role !== \App\Models\User::ROLE_ARTIST) {
            return redirect()->route('profile.edit')->with('error', 'You must be a verified artist to upload artwork. Please apply for verification below.');
        }

        return Inertia::render('Dashboard/Artworks/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== \App\Models\User::ROLE_ARTIST) {
            return redirect()->route('profile.edit')->with('error', 'You must be a verified artist to upload artwork. Please apply for verification below.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:Painting,Canvas,Drawing,Sculpture,Vase,Basket,Other,Photography,Digital,Mixed Media',
            'width' => 'required|numeric|min:0',
            'height' => 'required|numeric|min:0',
            'depth' => 'nullable|numeric|min:0',
            'unit' => 'required|in:cm,in',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|max:10240', // 10MB max
            'stock' => 'integer|min:0',
        ]);

        $fullUrl = null;
        $orientation = 'landscape';

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');

            // Upload using the Facade directly
            $uploadedFile = Cloudinary::upload($request->file('image')->getRealPath(), [
                'folder' => 'artworks'
            ]);
            $url = $uploadedFile->getSecurePath();
            $fullUrl = $url; // Map to existing logic variable

            // Calculate Orientation based on provided width/height (or we could fetch metadata if needed)
            // Using user input for dimensions since Cloudinary response doesn't give them directly without inspection
            if ($validated['width'] == $validated['height']) {
                $orientation = 'square';
            } elseif ($validated['width'] > $validated['height']) {
                $orientation = 'landscape';
            } else {
                $orientation = 'portrait';
            }
        }

        auth()->user()->artworks()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'width' => $validated['width'],
            'height' => $validated['height'],
            'depth' => $validated['depth'] ?? null,
            'unit' => $validated['unit'],
            'price' => $validated['price'],
            'stock' => $request->input('stock', 1),
            'image_url' => $fullUrl, // Saves full https://res.cloudinary.com... URL
            'original_image_url' => $fullUrl, // No separate original in this simplified flow
            'status' => 'pending',
            'orientation' => $orientation,
        ]);

        return redirect()->route('dashboard.artworks.index')->with('success', 'Artwork submitted successfully and is pending approval.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Artwork $artwork)
    {
        // Ensure user owns the artwork
        if ($artwork->artist_id !== auth()->id()) {
            abort(403);
        }

        $artwork->delete();

        return redirect()->back()->with('success', 'Artwork deleted.');
    }
}
