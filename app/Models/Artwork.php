<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artwork extends Model
{
    protected $fillable = [
        'artist_id',
        'title',
        'description',
        'category',
        'subcategory',
        'ready_to_hang',
        'framing',
        'price',
        'stock',
        'status',
        'image_url',
        'original_image_url',
        'is_staff_pick',
        'orientation',
        'width',
        'height',
        'depth',
        'unit',
    ];

    protected $casts = [
        'is_staff_pick' => 'boolean',
        'ready_to_hang' => 'boolean',
        'price' => 'decimal:2',
        'category' => \App\Enums\ArtworkCategory::class,
        'subcategory' => \App\Enums\ArtworkSubcategory::class,
    ];

    public function artist()
    {
        return $this->belongsTo(User::class, 'artist_id');
    }

    public function getImageUrlAttribute($value)
    {
        if (!$value) {
            return 'https://placehold.co/600x400/png?text=No+Image';
        }

        if (str_starts_with($value, 'http')) {
            return $value;
        }

        // Clean up leading slashes
        $value = ltrim($value, '/');

        // If it starts with storage/, just return with a leading slash
        if (str_starts_with($value, 'storage/')) {
            return '/' . $value;
        }

        // Otherwise prepend /storage/
        return '/storage/' . $value;
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes', 'artwork_id', 'user_id')->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
