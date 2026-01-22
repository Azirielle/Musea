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
            try {
                // Upload to Cloudinary using the proper method
                $cloudinaryResponse = \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::upload(
                    $request->file('avatar')->getRealPath(),
                    [
                        'folder' => 'avatars',
                        'resource_type' => 'auto'
                    ]
                );
                
                // Get the secure URL from the Cloudinary response
                $avatarPath = $cloudinaryResponse->getSecurePath() ?? $cloudinaryResponse['secure_url'] ?? null;
                
                if ($avatarPath) {
                    $user->avatar_path = $avatarPath;
                } else {
                    throw new \Exception('Failed to get avatar URL from Cloudinary');
                }
            } catch (\Exception $e) {
                // Fallback to public storage if Cloudinary fails
                try {
                    $path = $request->file('avatar')->store('avatars', 'public');
                    $user->avatar_path = $path;
                } catch (\Exception $fallbackError) {
                    return back()->withErrors(['avatar' => 'Failed to upload avatar: ' . $fallbackError->getMessage()]);
                }
            }
        } elseif ($request->default_avatar) {
            // Save the default avatar URL
            $user->avatar_path = $request->default_avatar;
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->save();

        return back();
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
