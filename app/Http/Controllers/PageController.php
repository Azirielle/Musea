<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return \Inertia\Inertia::render('About');
    }

    public function contact()
    {
        return \Inertia\Inertia::render('Contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'reason' => 'required|string|max:255',
            'message' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'order_number' => 'nullable|string|max:50',
            'preferred_contact' => 'required|string|in:email,phone',
        ]);

        // Construct a detailed message for the report
        $details = "Name: {$validated['full_name']}\n";
        $details .= "Email: {$validated['email']}\n";
        if ($validated['phone'])
            $details .= "Phone: {$validated['phone']}\n";
        if ($validated['order_number'])
            $details .= "Order #: {$validated['order_number']}\n";
        $details .= "Preferred Contact: {$validated['preferred_contact']}\n\n";
        $details .= "Message:\n{$validated['message']}";

        // Check if user is logged in
        $reporterId = auth()->id(); // Null if guest

        \App\Models\Report::create([
            'reporter_id' => $reporterId,
            'reason' => "Contact: " . $validated['subject'] . " (" . ucfirst($validated['reason']) . ")",
            'details' => $details,
            // reported_item_type and reported_item_id will be null
        ]);

        return back()->with('success', 'Thank you! Your message has been sent to our support team.');
    }
}
