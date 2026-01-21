<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Artwork;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // Ensure we have users and artworks
        $users = User::all();
        $artworks = Artwork::where('status', 'active')->get();

        if ($users->isEmpty() || $artworks->isEmpty()) {
            $this->command->info('No users or artworks found. Please run LegacyDataSeeder first.');
            return;
        }

        Order::truncate();
        DB::table('order_items')->truncate();

        // Create orders for the last 6 months
        for ($i = 0; $i < 50; $i++) {
            $date = now()->subDays(rand(0, 180));
            $user = $users->random();
            $artwork = $artworks->random();

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'status' => 'completed',
                'total_amount' => $artwork->price,
                'shipping_address' => $user->address ?? 'Manila',
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            DB::table('order_items')->insert([
                'order_id' => $order->id,
                'artwork_id' => $artwork->id,
                'price_each' => $artwork->price,
                'quantity' => 1,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
    }
}
