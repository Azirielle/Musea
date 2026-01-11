<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WithdrawalController extends Controller
{
    public function index()
    {
        $requests = WithdrawalRequest::with('user')->latest()->get();

        return Inertia::render('Admin/Withdrawals/Index', [
            'requests' => $requests,
        ]);
    }

    public function approve(Request $request, WithdrawalRequest $withdrawalRequest)
    {
        if ($withdrawalRequest->status !== 'pending') {
            return back()->with('error', 'Request has already been processed.');
        }

        $withdrawalRequest->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
            'completed_at' => now(),
        ]);

        return back()->with('message', 'Withdrawal request approved.');
    }

    public function reject(Request $request, WithdrawalRequest $withdrawalRequest)
    {
        if ($withdrawalRequest->status !== 'pending') {
            return back()->with('error', 'Request has already been processed.');
        }

        $withdrawalRequest->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'completed_at' => now(),
        ]);

        // Refund the balance to the artist
        $withdrawalRequest->user->increment('balance', (float) $withdrawalRequest->amount);

        return back()->with('message', 'Withdrawal request rejected and funds refunded.');
    }
}