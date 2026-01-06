<?php

use Illuminate\Support\Facades\Schema;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = ['users', 'artworks', 'journal_posts'];

foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        echo "Table '$table' exists.\n";
        $count = DB::table($table)->count();
        echo "Total rows: $count\n";

        if ($table === 'users') {
            $featured = DB::table('users')->where('is_featured', true)->get();
            echo "Featured users details:\n";
            foreach ($featured as $u) {
                echo "- Name: {$u->first_name} {$u->last_name}, Location: {$u->address} (ID: {$u->id})\n";
            }
        }
    } else {
        echo "Table '$table' does NOT exist.\n";
    }
}
