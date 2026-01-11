<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Users/Index', [
            'users' => User::select('id', 'first_name', 'last_name', 'email', 'status', 'created_at', 'balance', 'is_featured')->paginate(10),
        ]);
    }

    public function toggleStatus(User $user)
    {
        $user->status = $user->status === 'active' ? 'suspended' : 'active';
        $user->save();

        return back();
    }

    public function toggleFeatured(User $user)
    {
        $user->is_featured = !$user->is_featured;
        $user->save();

        return back();
    }
}
