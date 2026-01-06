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
        'price',
        'stock',
        'status',
        'image_url',
        'is_staff_pick',
    ];

    protected $casts = [
        'is_staff_pick' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function artist()
    {
        return $this->belongsTo(User::class, 'artist_id');
    }

    public function getImageUrlAttribute($value)
    {
        if (str_starts_with($value, 'http')) {
            return $value;
        }

        // Clean up leading slashes
        $value = ltrim($value, '/');

        // If it already starts with storage/, just return it
        if (str_starts_with($value, 'storage/')) {
            return asset($value);
        }

        // Otherwise prepend storage/
        return asset('storage/' . $value);
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
