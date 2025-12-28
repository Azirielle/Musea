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
        return Inertia::render('Dashboard/Artworks/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:Painting,Canvas,Drawing,Sculpture,Vase,Basket,Other',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|max:10240', // 10MB max
            'stock' => 'integer|min:0',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('artworks', 'public');
        }

        auth()->user()->artworks()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'stock' => $request->input('stock', 1),
            'image_url' => $path ? '/storage/' . $path : null,
            'status' => 'pending',
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
