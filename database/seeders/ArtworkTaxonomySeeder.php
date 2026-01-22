<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArtworkTaxonomySeeder extends Seeder
{
    public function run(): void
    {
        $taxonomy = config('artwork_taxonomy', []);

        DB::table('artwork_taxonomies')->truncate();

        $rows = [];
        $now = now();

        foreach ($taxonomy as $category => $groups) {
            foreach ($groups as $type => $values) {
                foreach ($values as $value) {
                    $rows[] = [
                        'category' => $category,
                        'type' => $type,
                        'value' => $value,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        if (!empty($rows)) {
            DB::table('artwork_taxonomies')->insert($rows);
        }
    }
}

