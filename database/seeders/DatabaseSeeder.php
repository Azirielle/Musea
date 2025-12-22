<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(LegacyDataSeeder::class);
        $this->call(InterestSeeder::class);

        // Dummy data commented out to preserve strict legacy import
        /*
        // Create a regular user
        User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'user@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'address' => '123 Main St, New York, NY',
            'contact_number' => '+1234567890'
        ]);

        // Create some Artists
        $artists = [
            ['first_name' => 'Alice', 'last_name' => 'Vance', 'email' => 'alice@musea.com'],
            ['first_name' => 'Robert', 'last_name' => 'Chen', 'email' => 'robert@musea.com'],
            ['first_name' => 'Elena', 'last_name' => 'Gomez', 'email' => 'elena@musea.com'],
        ];

        foreach ($artists as $data) {
            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'address' => 'Artist Studio, 404 Art Lane',
                'contact_number' => '+1987654321'
            ]);

            // Create some Artworks for each artist
            for ($i = 1; $i <= 5; $i++) {
                \App\Models\Artwork::create([
                    'artist_id' => $user->id,
                    'title' => $data['first_name'] . "'s Masterpiece #" . $i,
                    'description' => "This is a unique handcrafted piece by " . $data['first_name'] . ". Created with passion and dedication to the craft.",
                    'category' => fake()->randomElement(['Painting', 'Canvas', 'Drawing', 'Sculpture', 'Vase', 'Basket']),
                    'price' => fake()->randomFloat(2, 50, 2000),
                    'stock' => fake()->numberBetween(0, 5),
                    'image_url' => '/images/placeholder-art.jpg' // Use placeholder for now or legacy path if we knew mapping
                ]);
            }
        }
        */
    }
}
