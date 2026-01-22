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

    'cloud_url' => env('CLOUDINARY_URL', 'cloudinary://112719694583157:yGB2snsePNfMtODwrtjesYI9Jnw@du6bc1wjb'),

    /**
     * Legacy Keys (Top-Level)
     */
    'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'du6bc1wjb'),
    'api_key' => env('CLOUDINARY_API_KEY', '112719694583157'),
    'api_secret' => env('CLOUDINARY_API_SECRET', 'yGB2snsePNfMtODwrtjesYI9Jnw'),
    'secure' => true,

    /**
     * V2/V3 SDK Compatible Structure
     */
    'cloud' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'du6bc1wjb'),
        'api_key' => env('CLOUDINARY_API_KEY', '112719694583157'),
        'api_secret' => env('CLOUDINARY_API_SECRET', 'yGB2snsePNfMtODwrtjesYI9Jnw'),
    ],
    'url' => [
        'secure' => true,
    ],

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