<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Display the user's favorite artworks.
     */
    public function favorites(Request $request): Response
    {
        $favorites = $request->user()->likes()
            ->with('artist')
            ->latest('likes.created_at')
            ->get()
            ->map(function ($artwork) {
                return [
                    'id' => $artwork->id,
                    'title' => $artwork->title,
                    'artist' => $artwork->artist ? $artwork->artist->first_name . ' ' . $artwork->artist->last_name : 'Musea Artist',
                    'price' => number_format((float) $artwork->price, 0),
                    'image_url' => $artwork->image_url,
                    'category' => $artwork->category,
                    'status' => $artwork->status,
                    'stock' => $artwork->stock,
                    'is_sold_out' => $artwork->stock <= 0,
                ];
            });

        return Inertia::render('Profile/Favorites', [
            'favorites' => $favorites,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Handle Avatar Upload
        if ($request->hasFile('avatar')) {
            try {
                // Upload to Cloudinary and get the secure URL
                $response = $request->file('avatar')->storeOnCloudinary('avatars');
                $path = $response->getSecurePath();
                $request->user()->avatar_path = $path;
            } catch (\Exception $e) {
                // Fallback to public storage if Cloudinary fails (e.g. no credentials)
                $path = $request->file('avatar')->store('avatars', 'public');
                $request->user()->avatar_path = $path;
            }
        } elseif ($request->filled('default_avatar')) {
            $request->user()->avatar_path = $request->default_avatar;
        }

        // Fill remaining data
        $request->user()->fill($data);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
