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
        'image_url',
    ];

    public function artist()
    {
        return $this->belongsTo(User::class, 'artist_id');
    }
}
