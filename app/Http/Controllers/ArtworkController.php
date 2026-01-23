<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Validation\Rule;

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

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
        $taxonomy = config('artwork_taxonomy', []);
        $validCategories = array_keys($taxonomy);

        $category = $request->input('category');
        $group = is_string($category) && isset($taxonomy[$category]) ? $taxonomy[$category] : null;
        $styleValues = $group['Style'] ?? [];
        $subjectValues = $group['Subject'] ?? [];
        $mediumOrMethodValues = $group['Medium'] ?? ($group['Method'] ?? []);

        // 1. Validate the Input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => ['required', 'string', Rule::in($validCategories)],
            'style' => ['required', 'string', Rule::in($styleValues)],
            'subject' => ['required', 'string', Rule::in($subjectValues)],
            // Stored in artworks.medium (UI labels it "Method" for Sculpture)
            'medium' => ['required', 'string', Rule::in($mediumOrMethodValues)],
            // 'subcategory' => 'nullable|string',
            'price' => 'required|numeric|min:0|max:99999999', // Max 99 Million (fits decimal 10,2)
            'width' => 'required|numeric|min:0|max:5000',     // Reasonable physical limit
            'height' => 'required|numeric|min:0|max:5000',    // Reasonable physical limit
            'depth' => 'nullable|numeric|min:0|max:5000',     // Reasonable physical limit
            'unit' => 'required|string',
            'stock' => 'required|integer|min:1|max:10000',    // Prevent massive numbers
            // 'ready_to_hang' => 'boolean',
            // 'framing' => 'string',
            'image' => 'required|image|max:10240', // Max 10MB
        ]);

        // 2. Upload to Cloudinary (Raw SDK)
        $url = null;
        if ($request->hasFile('image')) {
            try {
                // Manually configure the library (Bypassing Laravel Config)
                Configuration::instance('cloudinary://112719694583157:yGB2snsePNfMtODwrtjesYI9Jnw@du6bc1wjb?secure=true');

                $uploadApi = new UploadApi();
                $result = $uploadApi->upload($request->file('image')->getRealPath(), [
                    'folder' => 'artworks'
                ]);
                $url = $result['secure_url'];

            } catch (\Exception $e) {
                return back()->withErrors(['image' => 'Upload failed: ' . $e->getMessage()]);
            }
        } else {
            return back()->withErrors(['image' => 'Image upload failed.']);
        }

        // 3. Calculate Orientation
        $orientation = 'square';
        if ($validated['width'] > $validated['height']) {
            $orientation = 'landscape';
        } elseif ($validated['width'] < $validated['height']) {
            $orientation = 'portrait';
        }



        // 4. Create Database Record
        auth()->user()->artworks()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'style' => $validated['style'],
            'subject' => $validated['subject'],
            'medium' => $validated['medium'],
            'width' => $validated['width'],
            'height' => $validated['height'],
            'depth' => $validated['depth'] ?? null,
            'unit' => $validated['unit'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'image_url' => $url,
            'original_image_url' => $url,
            'status' => 'pending',
            'orientation' => $orientation,

        ]);

        // 5. Redirect
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
