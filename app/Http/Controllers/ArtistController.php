<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArtistController extends Controller
{
    public function index()
    {
        // Assuming artists are users who have artworks?? Or just all users? 
        // For now, let's just get users who 'could' be artists, or maybe we add a role field later.
        // Legacy 'artists.php' likely listed all users or specific ones.
        // Let's assume all users for now.
        $artists = \App\Models\User::paginate(20);
        return \Inertia\Inertia::render('Artists/Index', [
            'artists' => $artists
        ]);
    }
}
