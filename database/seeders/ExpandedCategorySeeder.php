<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpandedCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create the Artist
        $artist = \App\Models\User::firstOrCreate(
            ['email' => 'expanded_artist@musea.com'],
            [
                'first_name' => 'Leonardo',
                'last_name' => 'Da Vinci II',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'is_onboarded' => true,
                // Add other necessary fields if required by User model adjustments
            ]
        );

        // 2. Define Categories and Subcategories
        $mapping = [
            'Painting' => ['Oil', 'Acrylic', 'Watercolor', 'Abstract', 'Portrait'],
            'Digital' => ['3D Render', 'Vector', 'AI Art', 'Pixel Art'],
            'Sculpture' => ['Metal', 'Wood', 'Resin', 'Ceramic'],
            'Drawing' => ['Graphite', 'Charcoal'],
            'Photography' => [null], // No subcategories defined in prompt list relative to Enum structure or treat as main
            'Mixed Media' => [null],
        ];

        // 3. Seed Artworks
        foreach ($mapping as $category => $subcategories) {
            foreach ($subcategories as $subcategory) {
                // If subcategory is null, we just create 10 for the main category
                $count = 10;

                for ($i = 1; $i <= $count; $i++) {
                    $subName = $subcategory ? $subcategory : 'General';
                    $orientation = ['landscape', 'portrait', 'square'][rand(0, 2)];

                    \App\Models\Artwork::create([
                        'artist_id' => $artist->id,
                        'title' => "{$category} - {$subName} #{$i}",
                        'description' => "A beautiful {$category} piece in {$subName} style. Created for testing purposes.",
                        'category' => $category,
                        'subcategory' => $subcategory, // Can be null
                        'price' => rand(1000, 50000),
                        'stock' => 1,
                        'status' => 'active',
                        'ready_to_hang' => rand(0, 1) == 1,
                        'framing' => ['Framed', 'Unframed', 'Gallery Wrap'][rand(0, 2)],
                        'image_url' => $this->getImageForCategory($category, $orientation),
                        'orientation' => $orientation,
                    ]);
                }
            }
        }
    }

    private function getImageForCategory($category, $orientation = 'landscape')
    {
        $dimensions = match ($orientation) {
            'landscape' => 'w=800&h=600',
            'portrait' => 'w=600&h=800',
            'square' => 'w=800&h=800',
            default => 'w=800&h=600',
        };

        $baseUrl = match ($category) {
            'Painting' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5',
            'Digital' => 'https://images.unsplash.com/photo-1547891654-e66ed7ebb968',
            'Sculpture' => 'https://images.unsplash.com/photo-1554188248-986adbb73be4',
            'Drawing' => 'https://images.unsplash.com/photo-1615184697985-c9bde1b07da7',
            'Photography' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32',
            'Mixed Media' => 'https://images.unsplash.com/photo-1515405295579-ba7b454989ab',
            default => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5',
        };

        return "{$baseUrl}?auto=format&fit=crop&q=80&{$dimensions}";
    }
}
