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
        // 1. Validate the Input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            // 'subcategory' => 'nullable|string', // Adapted: Field likely does not exist
            'price' => 'required|numeric|min:0',
            'width' => 'required|numeric|min:0',
            'height' => 'required|numeric|min:0',
            'depth' => 'nullable|numeric|min:0',
            'unit' => 'required|string',
            'stock' => 'required|integer|min:1',
            // 'ready_to_hang' => 'boolean', // Adapted: Field likely does not exist
            // 'framing' => 'string', // Adapted: Field likely does not exist
            'image' => 'required|image|max:10240', // Max 10MB
        ]);

        // 2. FORCE CONFIGURATION (The Magic Fix 🪄)
        // We manually set these values so the app doesn't need to look for a file.
        config([
            'cloudinary.cloud_url' => 'cloudinary://112719694583157:yGB2snsePNfMtODwrtjesYI9Jnw@du6bc1wjb',
            'cloudinary.cloud_name' => 'du6bc1wjb',
            'cloudinary.api_key' => '112719694583157',
            'cloudinary.api_secret' => 'yGB2snsePNfMtODwrtjesYI9Jnw',
            'cloudinary.secure' => true,
        ]);

        // 3. Upload to Cloudinary
        if ($request->hasFile('image')) {
            // Now this will work because we set the config above!
            $uploadedFile = \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'artworks']
            );
            $url = $uploadedFile->getSecurePath();
        } else {
            return back()->withErrors(['image' => 'Image upload failed.']);
        }

        // 4. Calculate Orientation
        $orientation = 'square';
        if ($validated['width'] > $validated['height']) {
            $orientation = 'landscape';
        } elseif ($validated['width'] < $validated['height']) {
            $orientation = 'portrait';
        }

        // 5. Create Database Record
        // Adapted to match existing DB Schema (width/height vs dimensions string)
        auth()->user()->artworks()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'width' => $validated['width'],
            'height' => $validated['height'],
            'depth' => $validated['depth'] ?? null,
            'unit' => $validated['unit'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'image_url' => $url, // DB column is 'image_url', not 'image_path'
            'original_image_url' => $url,
            'status' => 'pending',
            'orientation' => $orientation,
        ]);

        // 6. Redirect
        return redirect()->route('dashboard.artworks.index')
            ->with('success', 'Artwork submitted successfully and is pending approval!');
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
