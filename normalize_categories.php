<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Artwork;
use Illuminate\Support\Facades\DB;

echo "--- Normalizing Categories ---\n";

// Map Canvas -> Painting
$canvasCount = Artwork::where('category', 'Canvas')->count();
Artwork::where('category', 'Canvas')->update(['category' => 'Painting']);
echo "Updated $canvasCount 'Canvas' artworks to 'Painting'.\n";

// Map Vase -> Sculpture
$vaseCount = Artwork::where('category', 'Vase')->count();
Artwork::where('category', 'Vase')->update(['category' => 'Sculpture']);
echo "Updated $vaseCount 'Vase' artworks to 'Sculpture'.\n";

// Map Basket -> Sculpture (if any)
$basketCount = Artwork::where('category', 'Basket')->count();
Artwork::where('category', 'Basket')->update(['category' => 'Sculpture']);
echo "Updated $basketCount 'Basket' artworks to 'Sculpture'.\n";

echo "--- Normalization Complete ---\n";
