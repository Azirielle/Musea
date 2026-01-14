<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

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

        $path = null;
        $originalPath = null;
        $orientation = 'landscape';

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');

            // 1. Save Original (Secure)
            $originalPath = $imageFile->store('artworks/originals', 'public');

            // 2. Generate Watermarked Version
            // Ensure public directory exists
            if (!Storage::disk('public')->exists('artworks')) {
                Storage::disk('public')->makeDirectory('artworks');
            }

            $watermarkedFilename = $imageFile->hashName();
            $watermarkedPath = 'artworks/' . $watermarkedFilename;
            $absolutePath = storage_path('app/public/' . $watermarkedPath);

            try {
                $image = \Intervention\Image\Laravel\Facades\Image::read($imageFile->getRealPath());

                // Apply Watermark
                $watermarkPath = public_path('images/logo/Musea.png');
                if (file_exists($watermarkPath)) {
                    $watermark = \Intervention\Image\Laravel\Facades\Image::read($watermarkPath);

                    // Resize watermark to 20% of image width
                    $watermark->scale(width: $image->width() * 0.2);

                    // Place watermark in center with 40% opacity
                    $image->place($watermark, 'center', 0, 0, 40);
                } else {
                    // Fallback text watermark if logo missing
                    // Note: Requires font file usually, skipping for safety to just use original if fails or simple overlay
                }

                $image->save($absolutePath);
                $path = $watermarkedPath;

            } catch (\Exception $e) {
                // If watermarking fails (e.g. driver issue), fallback to standard store
                $path = $imageFile->store('artworks', 'public');
            }

            // Detect orientation
            $width = $image->width();
            $height = $image->height();

            if ($width == $height) {
                $orientation = 'square';
            } elseif ($width > $height) {
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
            'image_url' => $path ? '/storage/' . $path : null,
            'original_image_url' => $originalPath ? '/storage/' . $originalPath : null,
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
