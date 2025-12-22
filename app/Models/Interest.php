<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Interest extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'image_url'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
