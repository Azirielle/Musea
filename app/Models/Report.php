<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'reported_artist_id',
        'reason',
        'details',
        'proof',
        'status',
        'admin_notes',
        'reported_item_id',
        'reported_item_type',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reportedArtist()
    {
        return $this->belongsTo(User::class, 'reported_artist_id');
    }

    public function reported_item()
    {
        return $this->morphTo();
    }
}
