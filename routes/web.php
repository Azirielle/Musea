<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\ShopController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ArtworkController as AdminArtworkController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;

Route::get('/', function () {
    $recommendedArtworks = [];
    if (auth()->check() && auth()->user()->is_onboarded) {
        $interests = auth()->user()->interests->pluck('name');
        if ($interests->isNotEmpty()) {
            // Assuming Artwork model exists and has 'category' field matching Interest name
            // If Artwork model is not imported, we use full path or add import.
            // Let's use full path for safety in closure: \App\Models\Artwork
            // Fetch recommended artworks based on user interests
            // Note: 'category' in Artwork is a string. 'name' in Interest is a string.
            // We ensure matching is case-insensitive if possible, but for simplicity we rely on exact or approximate matching.
            $recommendedArtworks = \App\Models\Artwork::whereIn('category', $interests)
                ->orWhere(function ($query) use ($interests) {
                    foreach ($interests as $interest) {
                        $query->orWhere('category', 'LIKE', "%{$interest}%");
                    }
                })
                ->inRandomOrder()
                ->take(8)
                ->get()
                ->map(function ($artwork) {
                    return [
                        'id' => $artwork->id,
                        'title' => $artwork->title,
                        'artist' => $artwork->artist ? $artwork->artist->first_name . ' ' . $artwork->artist->last_name : 'Musea Artist',
                        'price' => number_format($artwork->price, 0),
                        'image' => $artwork->image_url ?? '/images/placeholder-art.jpg',
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
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
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

    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');

    Route::resource('dashboard/artworks', \App\Http\Controllers\ArtworkController::class)
        ->only(['index', 'create', 'store', 'destroy'])
        ->names('dashboard.artworks');

    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{order}/received', [\App\Http\Controllers\OrderController::class, 'received'])->name('orders.received');

    // Social & Engagement
    Route::post('/artists/{artist}/follow', [\App\Http\Controllers\SocialController::class, 'follow'])->name('artists.follow');
    Route::post('/artists/{artist}/unfollow', [\App\Http\Controllers\SocialController::class, 'unfollow'])->name('artists.unfollow');
    Route::post('/artworks/{artwork}/like', [\App\Http\Controllers\SocialController::class, 'like'])->name('artworks.like');
    Route::post('/artworks/{artwork}/unlike', [\App\Http\Controllers\SocialController::class, 'unlike'])->name('artworks.unlike');
    Route::post('/artworks/{artwork}/review', [\App\Http\Controllers\ReviewController::class, 'store'])->name('artworks.review');
    Route::post('/artworks/{artwork}/review', [\App\Http\Controllers\ReviewController::class, 'store'])->name('artworks.review');

    // Notifications
    Route::post('/notifications/{id}/read', function ($id) {
        auth()->user()->notifications()->findOrFail($id)->markAsRead();
        return back();
    })->name('notifications.read');
});

Route::middleware(['auth', \App\Http\Middleware\EnsureAdminPort::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');

    // Artwork Approval
    Route::get('/approvals', [AdminArtworkController::class, 'index'])->name('approvals.index');
    Route::post('/approvals/{artwork}/approve', [AdminArtworkController::class, 'approve'])->name('approvals.approve');
    Route::post('/approvals/{artwork}/reject', [AdminArtworkController::class, 'reject'])->name('approvals.reject');

    // Sales / Transactions
    Route::get('/sales', [TransactionController::class, 'index'])->name('sales.index');
    Route::post('/sales/{order}/ship', [TransactionController::class, 'ship'])->name('sales.ship');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/{report}/resolve', [ReportController::class, 'resolve'])->name('reports.resolve');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Coupons
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class)->except(['create', 'edit', 'show']);
    Route::post('coupons/{coupon}/toggle', [\App\Http\Controllers\Admin\CouponController::class, 'toggle'])->name('coupons.toggle');
});

// Checkout Coupon
Route::post('/checkout/validate-coupon', [\App\Http\Controllers\CouponController::class, 'validateCoupon'])->middleware('auth')->name('checkout.validate-coupon');

require __DIR__ . '/auth.php';
