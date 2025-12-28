<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Reports/Index', [
            'reports' => Report::with('reporter:id,first_name,last_name,email')
                ->latest()
                ->paginate(10),
        ]);
    }

    public function resolve(Report $report)
    {
        $report->update(['status' => 'resolved']);
        return back();
    }
}
