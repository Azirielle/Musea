<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class LegacyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        // Users
        DB::table('users')->truncate();
        DB::table('users')->insert([
            ['id' => 1, 'first_name' => 'Ino', 'last_name' => 'Yasha', 'email' => 'inuyasha@gmail.com', 'password' => '$2y$10$ejkMZFrkF8QBmm7iooQheeKNux8PKlUwpTwW6jDY5U5V8u8ePe4V6', 'address' => '123123123 kapampangan street', 'contact_number' => '09856834737', 'avatar_url' => 'uploads/avatars/user_1_1762365067_557120152_2065522080944599_4936648027586025941_n.jpg', 'created_at' => '2025-10-26 05:17:28'],
            ['id' => 2, 'first_name' => 'Christine Joy', 'last_name' => 'Almajar', 'email' => 'cj@gmail.com', 'password' => '$2y$10$bs.BZgnDiDrnq441beuSYeggvkOT5TXZU9JruHluKIRCPgXKvlvXC', 'address' => 'taga san roque ako123 street', 'contact_number' => '09125358473', 'avatar_url' => NULL, 'created_at' => '2025-10-27 14:07:27'],
            ['id' => 5, 'first_name' => 'Mark', 'last_name' => 'Lloyd', 'email' => 'mark.lloyd@musea.art', 'password' => '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'address' => 'San antonio Bayanihan street 143', 'contact_number' => '09163873774', 'avatar_url' => 'uploads/avatars/mark_lloyd.png', 'created_at' => '2025-11-03 10:10:58'],
            ['id' => 6, 'first_name' => 'Ken', 'last_name' => 'Tan', 'email' => 'ken.tan@musea.art', 'password' => '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'address' => 'san roque', 'contact_number' => '097317483874', 'avatar_url' => 'uploads/avatars/ken_tan.png', 'created_at' => '2025-11-03 10:10:58'],
            ['id' => 7, 'first_name' => 'Maya', 'last_name' => 'Ortiz', 'email' => 'maya.ortiz@musea.art', 'password' => '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'address' => 'Quezon', 'contact_number' => '09382756174', 'avatar_url' => 'uploads/avatars/maya_ortiz.png', 'created_at' => '2025-11-03 10:10:58'],
            ['id' => 8, 'first_name' => 'Liam', 'last_name' => 'Becker', 'email' => 'liam.becker@musea.art', 'password' => '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'address' => 'Makati', 'contact_number' => '09237858749', 'avatar_url' => 'uploads/avatars/liam_becker.png', 'created_at' => '2025-11-03 10:10:58'],
            ['id' => 9, 'first_name' => 'Naomi', 'last_name' => 'Fields', 'email' => 'naomi.fields@musea.art', 'password' => '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'address' => 'calamba', 'contact_number' => '09232084702', 'avatar_url' => 'uploads/avatars/naomi_fields.png', 'created_at' => '2025-11-03 10:10:58'],
            ['id' => 10, 'first_name' => 'Aria', 'last_name' => 'Chen', 'email' => 'aria.chen@musea.art', 'password' => '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'address' => 'Batangas', 'contact_number' => '09271298375', 'avatar_url' => 'uploads/avatars/aria_chen.png', 'created_at' => '2025-11-03 10:10:58'],
            ['id' => 11, 'first_name' => 'Riley', 'last_name' => 'Park', 'email' => 'riley.park@musea.art', 'password' => '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'address' => 'tondo', 'contact_number' => '09284639470', 'avatar_url' => 'uploads/avatars/riley_park.png', 'created_at' => '2025-11-03 10:10:58'],
            ['id' => 12, 'first_name' => 'Avery', 'last_name' => 'Lane', 'email' => 'avery.lane@musea.art', 'password' => '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'address' => 'manila', 'contact_number' => '09786431234', 'avatar_url' => 'uploads/avatars/avery_lane.png', 'created_at' => '2025-11-03 10:10:58'],
            ['id' => 13, 'first_name' => 'Quinn', 'last_name' => 'Harper', 'email' => 'quinn.harper@musea.art', 'password' => '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'address' => 'cabuyao', 'contact_number' => '09451238765', 'avatar_url' => 'uploads/avatars/quinn_harper.png', 'created_at' => '2025-11-03 10:10:58'],
            ['id' => 14, 'first_name' => 'Noah', 'last_name' => 'Voss', 'email' => 'noah.voss@musea.art', 'password' => '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'address' => 'makati', 'contact_number' => '09752387549', 'avatar_url' => 'uploads/avatars/noah_voss.png', 'created_at' => '2025-11-03 10:10:58'],
            ['id' => 15, 'first_name' => 'Iris', 'last_name' => 'Bennett', 'email' => 'iris.bennett@musea.art', 'password' => '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'address' => 'Baguio', 'contact_number' => '09284761937', 'avatar_url' => 'uploads/avatars/iris_bennett.png', 'created_at' => '2025-11-03 10:10:58'],
            ['id' => 16, 'first_name' => 'Clara', 'last_name' => 'Benton', 'email' => 'clara.benton@musea.art', 'password' => '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'address' => '123 street', 'contact_number' => '09385724', 'avatar_url' => 'uploads/avatars/clara_benton.png', 'created_at' => '2025-11-03 10:10:58'],
            ['id' => 17, 'first_name' => 'Sophie', 'last_name' => 'Hart', 'email' => 'sophie.hart@musea.art', 'password' => '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'address' => 'Sta. Rosa', 'contact_number' => '0953855427', 'avatar_url' => 'uploads/avatars/sophie_hart.png', 'created_at' => '2025-11-03 10:10:58'],
            ['id' => 18, 'first_name' => 'Jonas', 'last_name' => 'Reed', 'email' => 'jonas.reed@musea.art', 'password' => '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'address' => NULL, 'contact_number' => NULL, 'avatar_url' => 'uploads/avatars/jonas_reed.png', 'created_at' => '2025-11-03 10:10:58'],
            ['id' => 19, 'first_name' => 'Lucia', 'last_name' => 'Moretti', 'email' => 'lucia.moretti@musea.art', 'password' => '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'address' => 'Dyan sa kanto', 'contact_number' => '09871236543', 'avatar_url' => 'uploads/avatars/lucia_moretti.png', 'created_at' => '2025-11-03 10:10:58'],
            ['id' => 20, 'first_name' => 'Graham', 'last_name' => 'Wells', 'email' => 'graham.wells@musea.art', 'password' => '$2a$12$0gO0qerUOdAiqrAivG0xcexxwKuA4fldChzaQOXjwwdsuNYqTvyda', 'address' => 'philip street', 'contact_number' => '09874561376', 'avatar_url' => 'uploads/avatars/graham_wells.png', 'created_at' => '2025-11-03 10:10:58'],
        ]);

        // Artworks
        DB::table('artworks')->truncate();
        DB::table('artworks')->insert([
            ['id' => 1, 'artist_id' => 1, 'title' => 'The test of things', 'description' => 'non binary gender fluidization condensation/evaporation', 'category' => 'Canvas', 'price' => 999.00, 'stock' => 3, 'status' => 'active', 'image_url' => 'uploads/artworks/1762175493_RobloxScreenShot20250917_222349522.png', 'created_at' => '2025-11-02 07:21:30'],
            ['id' => 2, 'artist_id' => 5, 'title' => "Canvas Print — 'Sunrise'", 'description' => 'A study of morning light and texture — created to explore the subtle transition of color at dawn and captured on archival canvas to retain vibrancy.', 'category' => 'Canvas', 'price' => 10499.00, 'stock' => 1, 'status' => 'active', 'image_url' => 'uploads/artworks/1762168954_marklloyd.png.jpg', 'created_at' => '2025-11-03 11:22:34'],
            ['id' => 3, 'artist_id' => 16, 'title' => 'Woodland Path with Trees', 'description' => 'woodland path with trees and everything nice', 'category' => 'Painting', 'price' => 1250.00, 'stock' => 1, 'status' => 'active', 'image_url' => 'uploads/artworks/1762252586_birmingham-museums-trust-zWE5pOLWkio-unsplash.jpg', 'created_at' => '2025-11-04 10:36:26'],
            ['id' => 4, 'artist_id' => 17, 'title' => 'Girl in White Picking Flowers', 'description' => 'girl in the flowers', 'category' => 'Painting', 'price' => 3500.00, 'stock' => 1, 'status' => 'active', 'image_url' => 'uploads/artworks/1762252871_europeana-VsnDYMWollM-unsplash.jpg', 'created_at' => '2025-11-04 10:41:11'],
            ['id' => 5, 'artist_id' => 18, 'title' => 'Still Life with Flowers and Blue Ribbon', 'description' => 'ang sarap tignan', 'category' => 'Painting', 'price' => 2500.00, 'stock' => 1, 'status' => 'active', 'image_url' => 'uploads/artworks/1762253079_europeana-YIfFVwDcgu8-unsplash.jpg', 'created_at' => '2025-11-04 10:44:39'],
            ['id' => 6, 'artist_id' => 19, 'title' => 'Baroque Ceiling with Angels', 'description' => 'andaming person omg!', 'category' => 'Painting', 'price' => 1500.00, 'stock' => 1, 'status' => 'active', 'image_url' => 'uploads/artworks/1762253356_adrianna-geo-1rBg5YSi00c-unsplash.jpg', 'created_at' => '2025-11-04 10:49:16'],
            ['id' => 7, 'artist_id' => 20, 'title' => 'Castle on Rocky', 'description' => 'fantasy rocky castle', 'category' => 'Painting', 'price' => 2250.00, 'stock' => 1, 'status' => 'active', 'image_url' => 'uploads/artworks/1762253528_birmingham-museums-trust-sJr8LDyEf7k-unsplash.jpg', 'created_at' => '2025-11-04 10:52:08'],
            ['id' => 8, 'artist_id' => 12, 'title' => 'Bird Studies on Kraft Paper', 'description' => 'birds of the same father', 'category' => 'Drawing', 'price' => 2000.00, 'stock' => 1, 'status' => 'active', 'image_url' => 'uploads/artworks/1762254863_averylane.jpg', 'created_at' => '2025-11-04 11:14:23'],
            ['id' => 9, 'artist_id' => 13, 'title' => 'Woman Resting on Bed, Ink', 'description' => 'beauty rest', 'category' => 'Drawing', 'price' => 3000.00, 'stock' => 1, 'status' => 'active', 'image_url' => 'uploads/artworks/1762255168_woman resting.png', 'created_at' => '2025-11-04 11:19:28'],
            ['id' => 10, 'artist_id' => 14, 'title' => 'Vase of Mixed Flowers on Black', 'description' => 'super vase', 'category' => 'Drawing', 'price' => 3750.00, 'stock' => 1, 'status' => 'active', 'image_url' => 'uploads/artworks/1762255326_vaseofmixed.png', 'created_at' => '2025-11-04 11:22:06'],
            ['id' => 11, 'artist_id' => 15, 'title' => 'Botanical Bouquet with Lilies and Roses', 'description' => 'bouquet of lilies and roses', 'category' => 'Drawing', 'price' => 2750.00, 'stock' => 1, 'status' => 'active', 'image_url' => 'uploads/artworks/1762255518_botanicalbouquet.png', 'created_at' => '2025-11-04 11:25:18'],
            ['id' => 12, 'artist_id' => 11, 'title' => 'Crowd of Cartoon Faces on Pink', 'description' => 'cartoon', 'category' => 'Drawing', 'price' => 4000.00, 'stock' => 1, 'status' => 'active', 'image_url' => 'uploads/artworks/1762255806_crowdofcartoon.png', 'created_at' => '2025-11-04 11:30:06'],
            ['id' => 13, 'artist_id' => 9, 'title' => 'Blue Brushstroke Texture', 'description' => 'brushstroke blue', 'category' => 'Canvas', 'price' => 7000.00, 'stock' => 1, 'status' => 'active', 'image_url' => 'uploads/artworks/1762256526_bluebrushstroke.png', 'created_at' => '2025-11-04 11:42:06'],
            ['id' => 14, 'artist_id' => 10, 'title' => 'Tree Branches with Colorful Leaves', 'description' => 'colorful nature', 'category' => 'Canvas', 'price' => 4750.00, 'stock' => 1, 'status' => 'active', 'image_url' => 'uploads/artworks/1762256665_treebranches.png', 'created_at' => '2025-11-04 11:44:25'],
            ['id' => 15, 'artist_id' => 6, 'title' => 'Red Buds on Blue Floral', 'description' => 'red buds on blue floral', 'category' => 'Canvas', 'price' => 4200.00, 'stock' => 1, 'status' => 'active', 'image_url' => 'uploads/artworks/1762256803_redbuds.png', 'created_at' => '2025-11-04 11:46:43'],
            ['id' => 16, 'artist_id' => 7, 'title' => 'Blue Diagonals with Black Drips', 'description' => 'diagonal blue', 'category' => 'Canvas', 'price' => 6000.00, 'stock' => 1, 'status' => 'active', 'image_url' => 'uploads/artworks/1762256975_bluediagonal.png', 'created_at' => '2025-11-04 11:49:35'],
            ['id' => 17, 'artist_id' => 8, 'title' => 'Dark Rainbow Swirl Abstract', 'description' => 'Rainbow after the rain', 'category' => 'Canvas', 'price' => 3000.00, 'stock' => 1, 'status' => 'active', 'image_url' => 'uploads/artworks/1762257117_darkrainbow.png', 'created_at' => '2025-11-04 11:51:57'],
            ['id' => 18, 'artist_id' => 17, 'title' => 'Painted Vase', 'description' => 'Blue Panited Vase', 'category' => 'Vase', 'price' => 4200.00, 'stock' => 1, 'status' => 'active', 'image_url' => 'uploads/artworks/1762257403_painted-vase.png', 'created_at' => '2025-11-04 11:56:43'],
            ['id' => 19, 'artist_id' => 5, 'title' => 'Wooden Sculpture', 'description' => 'Sculpture', 'category' => 'Sculpture', 'price' => 7000.00, 'stock' => 1, 'status' => 'active', 'image_url' => 'uploads/artworks/1762257602_wooden-sculpture.png', 'created_at' => '2025-11-04 12:00:02'],
            ['id' => 20, 'artist_id' => 11, 'title' => 'Handwoven Basket', 'description' => 'Basket', 'category' => 'Basket', 'price' => 4750.00, 'stock' => 1, 'status' => 'active', 'image_url' => 'uploads/artworks/1762257800_hand-woven-basket.png', 'created_at' => '2025-11-04 12:03:20'],
        ]);

        // Cart
        DB::table('carts')->truncate();
        DB::table('carts')->insert([
            ['id' => 1, 'user_id' => 1, 'created_at' => '2025-11-05 18:12:45']
        ]);

        // Cart Items
        DB::table('cart_items')->truncate();
        DB::table('cart_items')->insert([
            ['id' => 14, 'cart_id' => 1, 'artwork_id' => 18, 'quantity' => 2, 'price_each' => 4200.00],
            ['id' => 15, 'cart_id' => 1, 'artwork_id' => 17, 'quantity' => 2, 'price_each' => 3000.00]
        ]);

        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
    }
}
