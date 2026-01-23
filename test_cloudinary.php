<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

echo "--- Testing Cloudinary Upload ---\n";
echo "Config keys:\n";
echo "Cloud: " . config('cloudinary.cloud_name') . "\n";
echo "Key: " . substr(config('cloudinary.api_key'), 0, 5) . "...\n";

try {
    // Create a dummy file
    $file = 'test_image.txt';
    file_put_contents($file, 'This is a test upload');

    $response = Cloudinary::upload($file, [
        'folder' => 'debug',
        'resource_type' => 'auto'
    ]);

    echo "Upload Success!\n";
    echo "URL: " . $response->getSecurePath() . "\n";

} catch (\Exception $e) {
    echo "Upload FAILED!\n";
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    if (file_exists('test_image.txt'))
        unlink('test_image.txt');
}
