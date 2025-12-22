<?php

namespace Database\Seeders;

use App\Models\Interest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $interests = [
            'Painting',
            'Canvas',
            'Drawing',
            'Sculpture',
            'Vase',
            'Basket',
            'Other',
        ];

        foreach ($interests as $name) {
            Interest::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    // 'image_url' => '/images/interests/' . Str::slug($name) . '.jpg', // Placeholder for now
                ]
            );
        }
    }
}
