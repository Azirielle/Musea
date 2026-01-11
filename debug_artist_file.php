<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$outfile = fopen('debug_out.txt', 'w');

$user = App\Models\User::where('first_name', 'like', '%Ino%')->first();
if (!$user) {
    fwrite($outfile, "User not found\n");
    exit;
}

fwrite($outfile, "User: {$user->id}\n");

$raw = \Illuminate\Support\Facades\DB::table('artworks')->where('artist_id', $user->id)->get();
foreach ($raw as $a) {
    fwrite($outfile, "[{$a->id}] Cat: '{$a->category}' Sub: '{$a->subcategory}'\n");
}
fclose($outfile);
