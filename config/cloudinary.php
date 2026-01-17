<?php

/*
 * This file allows you to configure your Cloudinary credentials.
 * It reads the CLOUDINARY_URL, CLOUDINARY_CLOUD_NAME, etc. from your .env file.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Cloudinary settings. Cloudinary is a cloud
    | service that offers a solution to a web application's entire image
    | management pipeline.
    |
    */

    'cloud_url' => env('CLOUDINARY_URL'),

    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),

    'api_key' => env('CLOUDINARY_API_KEY'),

    'api_secret' => env('CLOUDINARY_API_SECRET'),

    'secure' => true, // Force HTTPS

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Upload Preset
    |--------------------------------------------------------------------------
    |
    | Upload presets allow you to define the default behavior for all your
    | uploads. You can configure this in your Cloudinary Dashboard.
    |
    */
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Route Configuration
    |--------------------------------------------------------------------------
    |
    | These settings control the routing for the Cloudinary controller.
    |
    */
    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),
];