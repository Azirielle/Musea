<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Adding orientation column...\n";
    // Force add without checking method that might be cached
    DB::statement("ALTER TABLE artworks ADD COLUMN orientation VARCHAR(255) NULL");
    echo "Column added.\n";
} catch (\Exception $e) {
    echo "Error adding column (maybe exists?): " . $e->getMessage() . "\n";
}
