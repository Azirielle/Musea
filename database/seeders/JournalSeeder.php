<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = \App\Models\User::pluck('id');

        if ($authors->isEmpty()) {
            return;
        }

        $posts = [
            [
                'title' => 'Studio Visit: Elena Cruz',
                'slug' => 'studio-visit-elena-cruz',
                'excerpt' => 'A look inside the creative process of our featured abstract artist.',
                'content' => '<p>Elena welcomes us into her chaos...</p>',
                'author_id' => $authors->random(),
                'published_at' => now()->subDays(2),
                'image_url' => null
            ],
            [
                'title' => 'Collecting Digital Art 101',
                'slug' => 'collecting-digital-art-101',
                'excerpt' => 'Everything you need to know about starting your digital collection.',
                'content' => '<p>Digital art is more than just pixels...</p>',
                'author_id' => $authors->random(),
                'published_at' => now()->subDays(5),
                'image_url' => null
            ],
            [
                'title' => 'The Future of Canvas',
                'slug' => 'the-future-of-canvas',
                'excerpt' => 'How traditional mediums are evolving in the modern age.',
                'content' => '<p>Canvas has been around for centuries...</p>',
                'author_id' => $authors->random(),
                'published_at' => now()->subDays(10),
                'image_url' => null
            ]
        ];

        foreach ($posts as $post) {
            \App\Models\JournalPost::firstOrCreate(['slug' => $post['slug']], $post);
        }
    }
}
