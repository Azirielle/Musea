<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
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

use Illuminate\Support\Str;

if (config('app.type') !== 'admin') {
    Route::get('/maintenance', function () {
        return Inertia::render('Maintenance');
    })->name('maintenance');



    Route::get('/', function () {
        $recommendedArtworks = [];
        if (auth()->check() && auth()->user()->is_onboarded) {
            $interests = auth()->user()->interests->pluck('name');
            if ($interests->isNotEmpty()) {
                $recommendedArtworks = \App\Models\Artwork::where('stock', '>', 0)
                    ->where(function ($query) use ($interests) {
                        foreach ($interests as $interest) {
                            $query->orWhere('category', 'LIKE', "%{$interest}%");
                        }
                    })
                    ->get();

                // Value Filter: Separate High & Low value to prevent crowding
                $highValue = $recommendedArtworks->where('price', '>=', 10000);
                $lowValue = $recommendedArtworks->where('price', '<', 10000);

                // Interleave logic: take 1 High, 2 Low, etc. to mix aesthetic
                $sorted = collect([]);
                while ($highValue->isNotEmpty() || $lowValue->isNotEmpty()) {
                    if ($highValue->isNotEmpty())
                        $sorted->push($highValue->shift());
                    if ($lowValue->isNotEmpty())
                        $sorted->push($lowValue->shift());
                    if ($lowValue->isNotEmpty())
                        $sorted->push($lowValue->shift());
                }

                $recommendedArtworks = $sorted->take(12)->map(function ($artwork) {
                    return [
                        'id' => $artwork->id,
                        'title' => $artwork->title,
                        'artist' => $artwork->artist ? $artwork->artist->first_name . ' ' . $artwork->artist->last_name : 'Musea Artist',
                        'price' => number_format((float) $artwork->price, 0),
                        'image' => $artwork->image_url ?? 'https://placehold.co/800x600/f3f4f6/1a1a1a?text=Musea+Artwork',
                        'category' => $artwork->category,
                        'stock' => $artwork->stock
                    ];
                })->values();
            }
        }

        // Fetch Featured Artwork for Hero
        $featuredArtwork = \App\Models\Artwork::where('status', 'active')
            ->where('stock', '>', 0)
            ->orderBy('price', 'desc')
            ->with('artist')
            ->first();

        $featuredArtworkData = $featuredArtwork ? [
            'title' => $featuredArtwork->title,
            'artist' => $featuredArtwork->artist ? $featuredArtwork->artist->first_name . ' ' . $featuredArtwork->artist->last_name : 'Musea Artist',
            'image' => $featuredArtwork->image_url,
            'link' => route('shop.show', $featuredArtwork->id)
        ] : null;

        // Fetch Featured Artists (Meet the Community)
        $featuredArtistsQuery = \App\Models\User::where('is_featured', true);
        if ($featuredArtistsQuery->count() === 0) {
            $featuredArtistsQuery = \App\Models\User::whereHas('artworks', function ($query) {
                $query->where('status', 'active');
            })->latest();
        }

        $featuredArtists = $featuredArtistsQuery->take(8)
            ->with('artworks')
            ->get()
            ->map(function ($artist) {
                $bestSeller = $artist->artworks->where('status', 'active')->sortByDesc('price')->first();
                return [
                    'id' => $artist->id,
                    'name' => $artist->first_name,
                    'location' => $artist->address ? Str::words($artist->address, 2, '') : 'Musea',
                    'image' => $artist->imageUrl(),
                    'bestSeller' => $bestSeller ? [
                        'id' => $bestSeller->id,
                        'title' => $bestSeller->title,
                        'image' => $bestSeller->image_url,
                    ] : null,
                ];
            });

        // Fetch Staff Picks
        $staffPicksQuery = \App\Models\Artwork::where('is_staff_pick', true)
            ->where('stock', '>', 0);

        if ($staffPicksQuery->count() === 0) {
            $staffPicksQuery = \App\Models\Artwork::where('status', 'active')
                ->where('stock', '>', 0)
                ->orderBy('price', 'desc');
        }

        $staffPicks = $staffPicksQuery->with('artist')
            ->take(2)
            ->get()
            ->map(function ($artwork) {
                return [
                    'id' => $artwork->id,
                    'title' => $artwork->title,
                    'artist' => $artwork->artist ? $artwork->artist->first_name : 'Musea Artist',
                    'image' => $artwork->image_url,
                    'price' => number_format((float) $artwork->price, 0),
                    'stock' => $artwork->stock,
                ];
            });

        // Fetch Categories (Shop by Medium)
        $categories = \App\Models\Artwork::select('category', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->where('status', 'active')
            ->groupBy('category')
            ->get()
            ->map(function ($item) {
                $bgImages = [
                    'Painting' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?auto=format&fit=crop&q=80&w=400',
                    'Sculpture' => 'https://images.unsplash.com/photo-1554188248-986adbb73be4?auto=format&fit=crop&q=80&w=400',
                    'Digital' => 'https://images.unsplash.com/photo-1547891654-e66ed7ebb968?auto=format&fit=crop&q=80&w=400',
                    'Photography' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&q=80&w=400',
                ];
                $categoryName = $item->category instanceof \BackedEnum ? $item->category->value : $item->category;
                return [
                    'name' => $categoryName,
                    'count' => $item->count,
                    'image' => $bgImages[$categoryName] ?? 'https://placehold.co/400x300/333/FFF?text=' . $categoryName,
                ];
            });

        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'featuredArtwork' => $featuredArtworkData,
            'recommendedArtworks' => $recommendedArtworks,
            'featuredArtists' => $featuredArtists,
            'staffPicks' => $staffPicks,
            'categories' => $categories,
        ]);
    })->name('home');

    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/shop/suggestions', [ShopController::class, 'suggestions'])->name('shop.suggestions');
    Route::get('/shop/{artwork}', [ShopController::class, 'show'])->name('shop.show');
    Route::get('/artists', [ArtistController::class, 'index'])->name('artists.index');
    Route::get('/artists/{artist}', [ArtistController::class, 'show'])->name('artists.show');
    Route::get('/about', [PageController::class, 'about'])->name('pages.about');
    Route::get('/contact', [PageController::class, 'contact'])->name('pages.contact');
    Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
    Route::get('/cart', function () {
        return Inertia::render('Cart');
    })->name('cart.index');

    Route::get('/journal', [\App\Http\Controllers\JournalController::class, 'index'])->name('journal.index');
    Route::get('/journal/{post}', [\App\Http\Controllers\JournalController::class, 'show'])->name('journal.show');


    // Dashboard route removed as per request
// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::get('/favorites', [ProfileController::class, 'favorites'])->name('profile.favorites');
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
        Route::post('/notifications/{id}/read', function (\Illuminate\Http\Request $request, $id) {
            auth()->user()->notifications()->findOrFail($id)->markAsRead();

            if ($url = $request->input('redirect_to')) {
                return redirect($url);
            }

            return back();
        })->name('notifications.read');

        // Artist Wallet
        Route::get('/dashboard/wallet', [\App\Http\Controllers\WalletController::class, 'index'])->name('dashboard.wallet');
        Route::post('/dashboard/wallet/payout-details', [\App\Http\Controllers\WalletController::class, 'updatePayoutDetails'])->name('dashboard.wallet.payout-details');
        Route::post('/dashboard/wallet/withdraw', [\App\Http\Controllers\WalletController::class, 'withdraw'])->name('dashboard.wallet.withdraw');

        // In-App Messaging
        Route::get('/messages', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{conversation}', [\App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{conversation}', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
        Route::get('/messages/start/{artwork}', [\App\Http\Controllers\MessageController::class, 'start'])->name('messages.start');

        // Verification
        Route::post('/verification', [\App\Http\Controllers\VerificationController::class, 'store'])->name('verification.store');
    });

}
if (config('app.type') !== 'public') {
    // Admin Authentication Routes
    Route::middleware('guest:admin')->prefix('admin')->group(function () {
        Route::get('login', [\App\Http\Controllers\Admin\Auth\AdminAuthController::class, 'create'])->name('admin.login');
        Route::post('login', [\App\Http\Controllers\Admin\Auth\AdminAuthController::class, 'store']);
    });

    Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::post('logout', [\App\Http\Controllers\Admin\Auth\AdminAuthController::class, 'destroy'])->name('logout');
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');

        // User Management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');
        Route::post('/users/{user}/featured', [UserController::class, 'toggleFeatured'])->name('users.featured');

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

        // Withdrawals
        Route::get('/withdrawals', [\App\Http\Controllers\Admin\WithdrawalController::class, 'index'])->name('withdrawals.index');
        Route::post('/withdrawals/{withdrawalRequest}/approve', [\App\Http\Controllers\Admin\WithdrawalController::class, 'approve'])->name('withdrawals.approve');
        Route::post('/withdrawals/{withdrawalRequest}/reject', [\App\Http\Controllers\Admin\WithdrawalController::class, 'reject'])->name('withdrawals.reject');

        // Verifications
        Route::get('/verifications', [\App\Http\Controllers\Admin\VerificationController::class, 'index'])->name('verifications.index');
        Route::post('/verifications/{user}/approve', [\App\Http\Controllers\Admin\VerificationController::class, 'approve'])->name('verifications.approve');
        Route::post('/verifications/{user}/reject', [\App\Http\Controllers\Admin\VerificationController::class, 'reject'])->name('verifications.reject');
    });
} elseif (config('app.type') === 'admin') {
    // Redirect root to admin login if this IS the admin app but user tried to hit root
    Route::get('/', function () {
        return redirect()->route('admin.login');
    });
}

// Checkout Coupon
Route::post('/checkout/validate-coupon', [\App\Http\Controllers\CouponController::class, 'validateCoupon'])->middleware('auth')->name('checkout.validate-coupon');


Route::get('/force-create-admin', function () {
    try {
        $admin = Admin::updateOrCreate(
            ['name' => 'Grupong Wiss'],
            [
                'email' => 'admin@musea.com',
                'password' => Hash::make('Balatngchookstogo#123'),
            ]
        );

        return "Admin 'Grupong Wiss' Setup Successfully!<br>Password has been reset to: Balatngchookstogo#123<br><br><a href='/admin/login'>Go to Login</a>";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/debug-admin-login', function () {
    $name = 'Grupong Wiss';
    $password = 'Balatngchookstogo#123';

    $admin = Admin::where('name', $name)->first();

    echo "<h1>Admin Login Debugger</h1>";
    echo "Target Name: <strong>{$name}</strong><br>";
    echo "Target Password: <strong>{$password}</strong><br><br>";

    if (!$admin) {
        echo "<span style='color:red'>FAIL: Admin user NOT FOUND in database.</span><br>";
        echo "Existing Admins: " . Admin::pluck('name')->join(', ');
        return;
    }

    echo "<span style='color:green'>PASS: Admin user found in database.</span><br>";
    echo "ID: " . $admin->id . "<br>";
    echo "Email: " . $admin->email . "<br>";
    echo "Stored Hash: " . $admin->password . "<br><br>";

    $check = Hash::check($password, $admin->password);
    if ($check) {
        echo "<span style='color:green'>PASS: Password Hash Check verified.</span><br>";
    } else {
        echo "<span style='color:red'>FAIL: Password Hash Check failed. The stored hash does not match the password.</span><br>";
    }

    $attempt = Auth::guard('admin')->attempt(['name' => $name, 'password' => $password]);
    if ($attempt) {
        echo "<span style='color:green'>PASS: Auth::guard('admin')->attempt() succeeded.</span><br>";
    } else {
        echo "<span style='color:red'>FAIL: Auth::guard('admin')->attempt() failed.</span><br>";
        echo "Debug Info: " . json_encode(config('auth.guards.admin')) . "<br>";
    }
});

require __DIR__ . '/auth.php';

