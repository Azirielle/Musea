<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
            $file = $request->file('avatar');

            // Debug: Log file details
            Log::info('Avatar Upload Debug', [
                'user_id' => $request->user()->id,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size_bytes' => $file->getSize(),
                'is_valid' => $file->isValid(),
                'error_code' => $file->getError(),
                'real_path' => $file->getRealPath(),
                'extension' => $file->getClientOriginalExtension(),
            ]);

            // Validate file before upload
            if (!$file->isValid()) {
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE => 'File exceeds server maximum upload size',
                    UPLOAD_ERR_FORM_SIZE => 'File exceeds form maximum size',
                    UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                    UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                    UPLOAD_ERR_NO_TMP_DIR => 'Server missing temp folder',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                    UPLOAD_ERR_EXTENSION => 'Upload stopped by extension',
                ];
                $errorMessage = $errorMessages[$file->getError()] ?? 'Unknown upload error (code: ' . $file->getError() . ')';

                Log::error('Avatar Upload Invalid File', [
                    'user_id' => $request->user()->id,
                    'error_code' => $file->getError(),
                    'error_message' => $errorMessage,
                ]);

                return back()->withErrors(['avatar' => 'Upload failed: ' . $errorMessage]);
            }


            try {
                // MANUAL CONFIGURATION - BYPASS CONFIG FILE
                // This ensures we are absolutely using the correct credentials
                $cloudinaryUrl = env('CLOUDINARY_URL');
                if (!$cloudinaryUrl) {
                    // Try to reconstruct if missing
                    $cloudinaryUrl = 'cloudinary://' . env('CLOUDINARY_API_KEY') . ':' . env('CLOUDINARY_API_SECRET') . '@' . env('CLOUDINARY_CLOUD_NAME');
                }

                // Force config into SDK
                \Cloudinary\Configuration\Configuration::instance($cloudinaryUrl);

                Log::info('Attempting Cloudinary upload (Manual)', [
                    'user_id' => $request->user()->id,
                    'url' => substr($cloudinaryUrl, 0, 25) . '...',
                ]);

                // Upload to Cloudinary using v3 API
                $cloudinaryResponse = \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::upload(
                    $file->getRealPath(),
                    [
                        'folder' => 'avatars',
                        'resource_type' => 'auto'
                    ]
                );

                Log::info('Cloudinary Response Received', [
                    'user_id' => $request->user()->id,
                    'response_dump' => print_r($cloudinaryResponse, true),
                ]);

                // Try multiple methods to extract the secure URL
                $avatarPath = null;

                // Method 1: Check for getSecurePath() method (v2 API)
                if (is_object($cloudinaryResponse) && method_exists($cloudinaryResponse, 'getSecurePath')) {
                    $avatarPath = $cloudinaryResponse->getSecurePath();
                }
                // Method 2: Check for getSecureUrl() method (v3 API)
                elseif (is_object($cloudinaryResponse) && method_exists($cloudinaryResponse, 'getSecureUrl')) {
                    $avatarPath = $cloudinaryResponse->getSecureUrl();
                }
                // Method 3: Array access
                elseif (is_array($cloudinaryResponse) && isset($cloudinaryResponse['secure_url'])) {
                    $avatarPath = $cloudinaryResponse['secure_url'];
                }
                // Method 4: Try casting to string
                elseif (is_object($cloudinaryResponse) && method_exists($cloudinaryResponse, '__toString')) {
                    $avatarPath = (string) $cloudinaryResponse;
                }

                if ($avatarPath && (str_starts_with($avatarPath, 'http://') || str_starts_with($avatarPath, 'https://'))) {
                    $request->user()->avatar_path = $avatarPath;
                    Log::info('Avatar path set successfully', ['user_id' => $request->user()->id, 'path' => $avatarPath]);
                } else {
                    throw new \Exception('Failed to get valid avatar URL from Cloudinary response. Got: ' . ($avatarPath ?? 'null'));
                }
            } catch (\Exception $e) {
                // NUCLEAR OPTION: DIE AND DUMP THE ERROR
                dd('CLOUDINARY DEBUG ERROR: ' . $e->getMessage(), $e->getTraceAsString());

                Log::error('Cloudinary Upload Failed', [
                    'user_id' => $request->user()->id,
                    'error_message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                // **DEBUG: Flash Cloudinary error to session so it's visible in browser**
                session()->flash('cloudinary_debug', 'Cloudinary Error: ' . $e->getMessage());

                // Fallback to public storage if Cloudinary fails
                try {
                    Log::info('Attempting fallback to local storage', ['user_id' => $request->user()->id]);
                    $path = $file->store('avatars', 'public');
                    $request->user()->avatar_path = $path;
                    Log::info('Local storage fallback successful', ['user_id' => $request->user()->id, 'path' => $path]);
                } catch (\Exception $fallbackError) {
                    Log::error('Local Storage Fallback Failed', [
                        'user_id' => $request->user()->id,
                        'error_message' => $fallbackError->getMessage(),
                    ]);
                    return back()->withErrors(['avatar' => 'Failed to upload avatar. Cloudinary error: ' . $e->getMessage() . '. Local storage error: ' . $fallbackError->getMessage()]);
                }
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

        // CRITICAL DEBUG: Log what was actually saved
        $request->user()->refresh(); // Reload from database
        Log::info('FINAL AVATAR CHECK', [
            'user_id' => $request->user()->id,
            'avatar_path_in_db' => $request->user()->avatar_path,
        ]);

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
