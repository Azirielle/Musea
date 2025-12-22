<?php

namespace App\Http\Controllers;

use App\Models\Interest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class OnboardingController extends Controller
{
    /**
     * Display the onboarding wizard.
     */
    public function index(): Response
    {
        return Inertia::render('Onboarding/Wizard', [
            'interests' => Interest::all(['id', 'name', 'slug', 'image_url']),
            'user' => auth()->user(), // Pass user to pre-fill form
        ]);
    }

    /**
     * Handle the profile update (avatar + name).
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => 'nullable|image|max:2048', // 2MB Max
            'default_avatar' => 'nullable|string',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        $user = $request->user();

        if ($request->hasFile('avatar')) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_path = $path;
        } elseif ($request->default_avatar) {
            // If default avatar is selected (and no new file uploaded), separate logic if needed.
            // Typically we might download it or just save the URL.
            // Given 'avatar_path' is usually a relative local path, lets prepend a flag or just save the full URL if we support it.
            // Our User model accessor `imageUrl()` assumes 'storage/' prefix if not empty.
            // We should modify User model or just download it.
            // For simplicity, let's enable saving external URLs or special handling.

            // Quick fix: User model 'imageUrl' handles external?
            // Checking User model:
            // return $this->avatar_path ? asset('storage/' . $this->avatar_path) : ...
            // We need to support external URLs in User model or save these defaults locally?
            // Let's assume we save the string and update User model to check if it's a URL.
            $user->avatar_path = $request->default_avatar;
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->save();

        return back(); // Stay on page, frontend will advance step or we can return specialized response
    }

    /**
     * Save selected interests and complete onboarding.
     */
    public function saveInterests(Request $request): RedirectResponse
    {
        $request->validate([
            'interests' => 'required|array|min:1',
            'interests.*' => 'exists:interests,id',
        ]);

        $user = $request->user();
        $user->interests()->sync($request->interests);
        $user->is_onboarded = true;
        $user->save();

        return redirect()->route('home');
    }
}
