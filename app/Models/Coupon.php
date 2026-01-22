<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'expires_at',
        'usage_limit',
        'used_count',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_usages')
            ->withPivot('order_id')
            ->withTimestamps();
    }

    public function isValidForUser($userId)
    {
        return !$this->users()->where('user_id', $userId)->exists();
    }
}
