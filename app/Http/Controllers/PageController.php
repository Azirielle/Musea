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
}
