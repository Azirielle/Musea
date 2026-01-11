<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class WalletController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $withdrawalRequests = $user->withdrawalRequests()->latest()->get();

        return Inertia::render('Dashboard/Wallet', [
            'balance' => $user->balance,
            'payout_details' => [
                'gcash_number' => $user->gcash_number,
                'bank_details' => $user->bank_details,
            ],
            'withdrawal_requests' => $withdrawalRequests,
        ]);
    }

    public function updatePayoutDetails(Request $request)
    {
        $validated = $request->validate([
            'payout_method' => 'required|string|in:gcash,bank',
            'gcash_number' => 'required_if:payout_method,gcash|nullable|string',
            'bank_details' => 'required_if:payout_method,bank|nullable|string',
        ]);

        auth()->user()->update([
            'gcash_number' => $request->gcash_number,
            'bank_details' => $request->bank_details,
        ]);

        return back()->with('message', 'Payout details updated successfully.');
    }

    public function withdraw(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'amount' => 'required|numeric|min:100|max:' . $user->balance,
            'payout_method' => 'required|string|in:gcash,bank',
        ]);

        $details = $request->payout_method === 'gcash' ? $user->gcash_number : $user->bank_details;

        if (!$details) {
            return back()->withErrors(['payout_method' => 'Please set your payout details first.']);
        }

        $user->withdrawalRequests()->create([
            'amount' => $request->amount,
            'payout_method' => $request->payout_method,
            'payout_details' => $details,
            'status' => 'pending',
        ]);

        $user->decrement('balance', $request->amount);

        return back()->with('message', 'Withdrawal request submitted successfully.');
    }
}
