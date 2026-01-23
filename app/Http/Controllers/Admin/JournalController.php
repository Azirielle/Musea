<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JournalPost;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
            'image' => 'nullable|image|max:65536', // 64MB max (matching Dockerfile config)
            'author_name' => 'nullable|string|max:255',
            'published_at' => 'nullable|boolean',
        ]);

        $url = null;
        if ($request->hasFile('image')) {
            try {
                // Try Cloudinary first
                $cloudinaryResponse = \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::upload(
                    $request->file('image')->getRealPath(),
                    [
                        'folder' => 'journal',
                        'resource_type' => 'auto'
                    ]
                );

                // Get the secure URL
                if (is_object($cloudinaryResponse) && method_exists($cloudinaryResponse, 'getSecurePath')) {
                    $url = $cloudinaryResponse->getSecurePath();
                } elseif (is_array($cloudinaryResponse) && isset($cloudinaryResponse['secure_url'])) {
                    $url = $cloudinaryResponse['secure_url'];
                }

                if (!$url) {
                    throw new \Exception('Failed to get URL from Cloudinary response');
                }
            } catch (\Exception $e) {
                // Fallback to local storage if Cloudinary fails
                try {
                    $path = $request->file('image')->store('journal', 'public');
                    $url = Storage::url($path);
                } catch (\Exception $fallbackError) {
                    return back()->withErrors(['image' => 'Upload failed: ' . $fallbackError->getMessage()]);
                }
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
            'is_published' => 'nullable|boolean',
        ]);

        $data = [
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'author_name' => $validated['author_name'] ?? null,
        ];

        if ($request->hasFile('image')) {
            try {
                // Delete old image if it's local storage (not Cloudinary)
                if ($journal->image_url && Str::startsWith($journal->image_url, '/storage/')) {
                    $oldPath = str_replace('/storage/', '', $journal->image_url);
                    Storage::disk('public')->delete($oldPath);
                }

                // Try Cloudinary first
                $cloudinaryResponse = \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::upload(
                    $request->file('image')->getRealPath(),
                    [
                        'folder' => 'journal',
                        'resource_type' => 'auto'
                    ]
                );

                // Get the secure URL
                $url = null;
                if (is_object($cloudinaryResponse) && method_exists($cloudinaryResponse, 'getSecurePath')) {
                    $url = $cloudinaryResponse->getSecurePath();
                } elseif (is_array($cloudinaryResponse) && isset($cloudinaryResponse['secure_url'])) {
                    $url = $cloudinaryResponse['secure_url'];
                }

                if ($url) {
                    $data['image_url'] = $url;
                } else {
                    throw new \Exception('Failed to get URL from Cloudinary response');
                }
            } catch (\Exception $e) {
                // Fallback to local storage if Cloudinary fails
                try {
                    $path = $request->file('image')->store('journal', 'public');
                    $data['image_url'] = Storage::url($path);
                } catch (\Exception $fallbackError) {
                    return back()->withErrors(['image' => 'Upload failed: ' . $fallbackError->getMessage()]);
                }
            }
        }

        // Handle publishing toggle
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
        if ($journal->image_url && Str::startsWith($journal->image_url, '/storage/')) {
            $path = str_replace('/storage/', '', $journal->image_url);
            Storage::disk('public')->delete($path);
        }

        $journal->delete();
        return redirect()->back()->with('success', 'Journal post deleted.');
    }
}
