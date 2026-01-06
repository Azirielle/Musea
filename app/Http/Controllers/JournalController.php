<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\JournalPost;

class JournalController extends Controller
{
    public function index()
    {
        $posts = JournalPost::with('author')
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->paginate(9);

        return Inertia::render('Journal/Index', [
            'posts' => $posts
        ]);
    }

    public function show(\App\Models\JournalPost $post)
    {
        if (!$post->published_at && auth()->id() !== $post->author_id) {
            abort(404);
        }

        return \Inertia\Inertia::render('Journal/Show', [
            'post' => $post->load('author')
        ]);
    }
}
