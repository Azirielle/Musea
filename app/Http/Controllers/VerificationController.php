<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class VerificationController extends Controller
{
    /**
     * Submit a verification request (for Member -> Verified Member OR Artist).
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_requested' => 'required|in:verified_member,artist',
            'portfolio_url' => 'nullable|url',
            'bio' => 'nullable|string|max:1000',
        ]);

        $user = $request->user();

        $user->update([
            'verification_status' => User::VERIFICATION_PENDING,
            'requested_role' => $request->role_requested,
            'portfolio_url' => $request->portfolio_url,
            'bio' => $request->bio ?? $user->bio,
        ]);

        return Redirect::back()->with('success', 'Verification request submitted successfully.');
    }
}
