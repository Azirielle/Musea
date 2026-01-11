<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artwork;

class ArtworkDimensionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $artworks = Artwork::whereNull('width')->orWhereNull('height')->get();

        foreach ($artworks as $artwork) {
            // Assign random realistic dimensions based on category if possible, or generic
            // Painting/Canvas: usually larger
            // Sculpture: varied
            // Defaulting to a range of 30cm to 150cm for variety

            $width = rand(30, 150);
            $height = rand(30, 150);
            $depth = null;

            if ($artwork->category === 'Sculpture' || $artwork->category === 'Vase' || $artwork->category === 'Basket') {
                $depth = rand(10, 50);
            }

            $artwork->update([
                'width' => $width,
                'height' => $height,
                'depth' => $depth,
                'unit' => 'cm', // Standardizing on CM for now
            ]);
        }
    }
}
