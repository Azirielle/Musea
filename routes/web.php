<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\ShopController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\OnboardingController;

Route::get('/', function () {
    $recommendedArtworks = [];
    if (auth()->check() && auth()->user()->is_onboarded) {
        $interests = auth()->user()->interests->pluck('name');
        if ($interests->isNotEmpty()) {
            // Assuming Artwork model exists and has 'category' field matching Interest name
            // If Artwork model is not imported, we use full path or add import.
            // Let's use full path for safety in closure: \App\Models\Artwork
            $recommendedArtworks = \App\Models\Artwork::whereIn('category', $interests)
                ->inRandomOrder()
                ->take(10)
                ->get()
                ->map(function ($artwork) {
                    return [
                        'title' => $artwork->title,
                        'artist' => $artwork->artist ? $artwork->artist->first_name . ' ' . $artwork->artist->last_name : 'Unknown',
                        'price' => number_format($artwork->price, 0),
                        'image' => $artwork->image_url ?? '/images/placeholder-art.jpg', // Fallback
                    ];
                });
        }
    }

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'recommendedArtworks' => $recommendedArtworks,
    ]);
})->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{artwork}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/artists', [ArtistController::class, 'index'])->name('artists.index');
Route::get('/about', [PageController::class, 'about'])->name('pages.about');
Route::get('/contact', [PageController::class, 'contact'])->name('pages.contact');
Route::get('/cart', function () {
    return Inertia::render('Cart');
})->name('cart.index');

// Dashboard route removed as per request
// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('onboarding')->name('onboarding.')->group(function () {
        Route::get('/', [OnboardingController::class, 'index'])->name('index');
        Route::post('/profile', [OnboardingController::class, 'updateProfile'])->name('profile');
        Route::post('/interests', [OnboardingController::class, 'saveInterests'])->name('interests');
    });
});

require __DIR__ . '/auth.php';
