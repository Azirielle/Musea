<?php

/*
 * This file allows you to configure your Cloudinary credentials.
 * It reads the CLOUDINARY_URL from your .env file.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    |
    | Cloudinary URL contains all credentials in the format:
    | cloudinary://API_KEY:API_SECRET@CLOUD_NAME
    |
    */

    'cloud_url' => env('CLOUDINARY_URL'),

    /**
     * Essential for Cloudinary SDK v2/v3
     * We replicate the keys to ensure compatibility regardless of how the SDK tries to read them.
     */
    'cloud' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'du6bc1wjb'),
        'api_key' => env('CLOUDINARY_API_KEY', '112719694583157'),
        'api_secret' => env('CLOUDINARY_API_SECRET', 'yGB2snsePNfMtODwrtjesYI9Jnw'),
    ],

    'url' => [
        'secure' => true,
    ],

    // Keep top-level keys just in case some other part of the app reads them
    'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'du6bc1wjb'),
    'api_key' => env('CLOUDINARY_API_KEY', '112719694583157'),
    'api_secret' => env('CLOUDINARY_API_SECRET', 'yGB2snsePNfMtODwrtjesYI9Jnw'),
    'secure' => true,

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Upload Preset
    |--------------------------------------------------------------------------
    */
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Route Configuration
    |--------------------------------------------------------------------------
    */
    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),
];