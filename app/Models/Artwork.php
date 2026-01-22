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
        'style',
        'subject',
        'medium',
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

    protected $appends = ['image_url'];

    public function getImageUrlAttribute($value)
    {
        $value = $this->attributes['image_url'] ?? null;

        if (!$value) {
            return 'https://placehold.co/600x400/png?text=No+Image';
        }

        if (str_starts_with($value, 'http')) {
            return $value;
        }

        if (str_starts_with($value, 'storage')) {
            return asset($value);
        }

        try {
            // Ensure we have a valid value before asking Cloudinary
            if (empty($value))
                return 'https://placehold.co/600x400/png?text=No+Image';

            return \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::getUrl($value);
        } catch (\Exception $e) {
            // Fallback if Cloudinary fails or ID is invalid
            // check if file exists in storage locally if needed, or just return storage URL
            return asset('storage/' . $value);
        }
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
