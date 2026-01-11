<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Adding ready_to_hang and framing columns...\n";

    // Attempt ready_to_hang
    try {
        DB::statement("ALTER TABLE artworks ADD COLUMN ready_to_hang TINYINT(1) DEFAULT 0");
        echo "ready_to_hang added.\n";
    } catch (\Exception $e) {
        echo "ready_to_hang error (maybe exists): " . $e->getMessage() . "\n";
    }

    // Attempt framing
    try {
        DB::statement("ALTER TABLE artworks ADD COLUMN framing VARCHAR(255) NULL");
        echo "framing added.\n";
    } catch (\Exception $e) {
        echo "framing error (maybe exists): " . $e->getMessage() . "\n";
    }

} catch (\Exception $e) {
    echo "General Error: " . $e->getMessage() . "\n";
}
