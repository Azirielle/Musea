<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Artwork;

// 1. Activate all artworks
$count = Artwork::where('status', 'pending')->update(['status' => 'active']);
echo "Activated $count artworks.\n";

// 2. Add a test featured artist
$user = User::create([
    'first_name' => 'Antigravity',
    'last_name' => '(AI Assistant)',
    'email' => 'ai-' . time() . '@example.com',
    'password' => bcrypt('password'),
    'address' => 'Cloud City',
    'is_featured' => true,
    'is_onboarded' => true,
]);

echo "Created featured artist: Antigravity\n";

// 3. Set some staff picks if not already enough
Artwork::where('is_staff_pick', false)->take(2)->update(['is_staff_pick' => true]);
echo "Ensured at least 2 staff picks.\n";
