<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArtistController extends Controller
{
    public function index()
    {
        $artists = \App\Models\User::whereHas('artworks', function ($q) {
            $q->where('status', 'active');
        })->paginate(20);

        return \Inertia\Inertia::render('Artists/Index', [
            'artists' => $artists
        ]);
    }

    public function show(\App\Models\User $artist)
    {
        $artist->loadCount([
            'artworks' => function ($query) {
                $query->where('status', 'active');
            },
            'followers'
        ]);

        $artworks = $artist->artworks()
            ->where('status', 'active')
            ->latest()
            ->paginate(12);

        return \Inertia\Inertia::render('Artists/Show', [
            'artist' => [
                'id' => $artist->id,
                'name' => $artist->first_name . ' ' . $artist->last_name,
                'location' => $artist->address,
                'avatar' => $artist->imageUrl(),
                'bio' => $artist->bio ?? "Hi, I'm {$artist->first_name}, a passionate artist at Musea. I believe that every piece of art tells a unique story, and I'm thrilled to share mine with you. Explore my collection and find something that speaks to you.",
                'artworks_count' => $artist->artworks_count,
                'followers_count' => $artist->followers_count,
            ],
            'artworks' => $artworks,
            'isFollowing' => auth()->check() ? auth()->user()->following()->where('following_id', $artist->id)->exists() : false,
        ]);
    }
}
