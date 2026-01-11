<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class JournalPostSeeder extends Seeder
{
    public function run()
    {
        // Ensure we have a user to attribute posts to
        $author = User::first() ?? User::factory()->create([
            'first_name' => 'Musea',
            'last_name' => 'Editor',
            'email' => 'editor@musea.com',
        ]);

        $posts = [
            [
                'title' => 'The Art of Minimalism',
                'slug' => 'the-art-of-minimalism',
                'excerpt' => 'Exploring the beauty of less. How negative space creates positive impact in modern aesthetics.',
                'content' => '<p>Minimalism is not just about having less; it is about making room for more of what matters. In the visual arts, minimalism strips away the unnecessary, leaving only the essential elements of shape, color, and form.</p><p>At Musea, we celebrate artists who dare to say more with less.</p>',
                'image_url' => 'https://images.unsplash.com/photo-1547891654-e66ed7ebb968?auto=format&fit=crop&q=80&w=800', // Abstract minimal
                'author_id' => $author->id,
                'published_at' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Studio Visit: Elena Cruz',
                'slug' => 'studio-visit-elena-cruz',
                'excerpt' => 'A look inside the creative process of our featured abstract artist.',
                'content' => '<p>We caught up with Elena Cruz in her sun-drenched studio in Barcelona. "Light is my primary medium," she explains, gesturing to the floor-to-ceiling windows.</p><p>Elena\'s work has been featured in galleries across Europe, and her latest collection "Solaris" is exclusively available on Musea.</p>',
                'image_url' => 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?auto=format&fit=crop&q=80&w=800', // Art studio
                'author_id' => $author->id,
                'published_at' => Carbon::now()->subDays(5),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Collecting Digital Art 101',
                'slug' => 'collecting-digital-art-101',
                'excerpt' => 'Everything you need to know about starting your digital collection.',
                'content' => '<p>Digital art is redefining ownership in the 21st century. But where do you start? This guide breaks down the basics of provenance, display, and curation for the modern collector.</p>',
                'image_url' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&q=80&w=800', // Digital abstract
                'author_id' => $author->id,
                'published_at' => Carbon::now()->subDays(10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        DB::table('journal_posts')->insert($posts);
    }
}
