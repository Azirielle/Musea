<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JournalPost;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class JournalController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Journal/Index', [
            'posts' => JournalPost::with('author')->latest()->paginate(10),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Journal/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|max:5120', // 5MB max
            'author_name' => 'nullable|string|max:255',
            'published_at' => 'nullable|boolean', // We'll accept boolean from frontend and convert
        ]);

        $url = null;
        if ($request->hasFile('image')) {
            try {
                // Manually configure the library (Bypassing Laravel Config)
                Configuration::instance('cloudinary://112719694583157:yGB2snsePNfMtODwrtjesYI9Jnw@du6bc1wjb?secure=true');

                $uploadApi = new UploadApi();
                $result = $uploadApi->upload($request->file('image')->getRealPath(), [
                    'folder' => 'journal'
                ]);
                $url = $result['secure_url'];

            } catch (\Exception $e) {
                return back()->withErrors(['image' => 'Upload failed: ' . $e->getMessage()]);
            }
        }

        JournalPost::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . Str::random(6),
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'image_url' => $url,
            'author_id' => auth()->id(),
            'author_name' => $validated['author_name'] ?? null,
            'published_at' => $request->boolean('published_at') ? now() : null,
        ]);

        return redirect()->route('admin.journals.index')->with('success', 'Journal post created successfully.');
    }

    public function edit(JournalPost $journal)
    {
        return Inertia::render('Admin/Journal/Edit', [
            'post' => $journal
        ]);
    }

    public function update(Request $request, JournalPost $journal)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|max:5120',
            'author_name' => 'nullable|string|max:255',
            'is_published' => 'nullable|boolean', // Use distinct field name to avoid ambiguity if needed, or simply check existence
        ]);

        $data = [
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'author_name' => $validated['author_name'] ?? null,
        ];

        // Only update slug if title changed significantly? Or never update slug to preserve SEO?
        // Let's keep slug persistent for now unless explicitly requested.

        if ($request->hasFile('image')) {
            try {
                // Manually configure the library (Bypassing Laravel Config)
                Configuration::instance('cloudinary://112719694583157:yGB2snsePNfMtODwrtjesYI9Jnw@du6bc1wjb?secure=true');

                $uploadApi = new UploadApi();
                $result = $uploadApi->upload($request->file('image')->getRealPath(), [
                    'folder' => 'journal'
                ]);
                $data['image_url'] = $result['secure_url'];

            } catch (\Exception $e) {
                return back()->withErrors(['image' => 'Upload failed: ' . $e->getMessage()]);
            }
        }

        // Handle publishing toggle
        // If frontend sends 'is_published' true/false
        if ($request->has('published_at')) {
            if ($request->boolean('published_at') && !$journal->published_at) {
                $data['published_at'] = now();
            } elseif (!$request->boolean('published_at') && $journal->published_at) {
                $data['published_at'] = null;
            }
        }

        $journal->update($data);

        return redirect()->route('admin.journals.index')->with('success', 'Journal post updated successfully.');
    }

    public function destroy(JournalPost $journal)
    {
        $journal->delete();
        return redirect()->back()->with('success', 'Journal post deleted.');
    }
}
