<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * POST /api/reports
     * Create a report against an artist.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reported_artist_id' => ['required', 'integer', 'exists:users,id'],
            'reason' => ['required', 'string', 'max:2000'],
            'proof' => ['required', 'image', 'max:5120'], // 5MB
        ]);

        $reportedArtist = User::findOrFail($validated['reported_artist_id']);

        // Store proof in public disk (storage/app/public/reports/...)
        $proofPath = $request->file('proof')->store('reports', 'public');

        $report = Report::create([
            'reporter_id' => $request->user()->id,
            'reported_artist_id' => $reportedArtist->id,
            // Keep polymorphic compatibility with existing schema
            'reported_item_type' => User::class,
            'reported_item_id' => $reportedArtist->id,
            'reason' => $validated['reason'],
            'proof' => $proofPath,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Report submitted. Our team will review it shortly.',
            'report_id' => $report->id,
        ], 201);
    }
}

