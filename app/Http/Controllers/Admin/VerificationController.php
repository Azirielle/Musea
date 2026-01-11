<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VerificationController extends Controller
{
    /**
     * Display a listing of verification applications.
     */
    public function index()
    {
        $applications = User::where('verification_status', User::VERIFICATION_PENDING)
            ->latest()
            ->paginate(10);

        return Inertia::render('Admin/Verifications/Index', [
            'applications' => $applications
        ]);
    }

    /**
     * Approve the application.
     */
    public function approve(User $user)
    {
        if ($user->verification_status !== User::VERIFICATION_PENDING) {
            return back()->with('error', 'This user is not pending verification.');
        }

        $role = $user->requested_role ?? User::ROLE_VERIFIED_MEMBER; // Fallback if null, but shouldn't be

        // Security check: ensure string matches allowed roles
        if (!in_array($role, [User::ROLE_VERIFIED_MEMBER, User::ROLE_ARTIST])) {
            $role = User::ROLE_VERIFIED_MEMBER;
        }

        $user->update([
            'role' => $role,
            'is_verified' => true,
            'verification_status' => User::VERIFICATION_APPROVED,
            // Keep requested_role for history or clear it? Keeping it is fine.
        ]);

        return back()->with('success', 'Application approved. User role updated.');
    }

    /**
     * Reject the application.
     */
    public function reject(User $user)
    {
        if ($user->verification_status !== User::VERIFICATION_PENDING) {
            return back()->with('error', 'This user is not pending verification.');
        }

        $user->update([
            'verification_status' => User::VERIFICATION_REJECTED,
            // is_verified remains false
            // role remains member (or whatever it was)
        ]);

        return back()->with('success', 'Application rejected.');
    }
}
