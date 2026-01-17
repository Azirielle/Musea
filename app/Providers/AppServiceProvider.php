<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Force set Cloudinary config at runtime
        config([
            'cloudinary.cloud_url' => env('CLOUDINARY_URL'),
            'cloudinary.upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),
            'cloudinary.notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),
            'cloudinary.secure' => true,
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS always (Aggressive Fix)
        URL::forceScheme('https');
    }
}
