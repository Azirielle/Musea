<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use App\Models\User;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    // Follow System
    public function follow(User $artist)
    {
        auth()->user()->following()->attach($artist->id);
        return back()->with('success', 'You are now following ' . $artist->first_name);
    }

    public function unfollow(User $artist)
    {
        auth()->user()->following()->detach($artist->id);
        return back()->with('success', 'Unfollowed ' . $artist->first_name);
    }

    // Like System
    public function like(Artwork $artwork)
    {
        auth()->user()->likes()->attach($artwork->id);
        return back()->with('success', 'Added to wishlist');
    }

    public function unlike(Artwork $artwork)
    {
        auth()->user()->likes()->detach($artwork->id);
        return back()->with('success', 'Removed from wishlist');
    }
}
