<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'address',
        'contact_number',
        'avatar_path',
        'is_onboarded',
        'status',
        'balance',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_onboarded' => 'boolean',
        ];
    }

    /**
     * The interests that belong to the user.
     */
    public function interests(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Interest::class);
    }

    /**
     * Get the artworks for the user.
     */
    public function artworks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Artwork::class, 'artist_id');
    }

    /**
     * Get the user's avatar URL.
     */
    public function imageUrl(): string
    {
        if ($this->avatar_path && (str_starts_with($this->avatar_path, 'http') || str_starts_with($this->avatar_path, 'https'))) {
            return $this->avatar_path;
        }

        return $this->avatar_path
            ? asset('storage/' . $this->avatar_path)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->first_name . ' ' . $this->last_name) . '&color=7F9CF5&background=EBF4FF';
    }

    // Social Relationships

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')->withTimestamps();
    }

    public function likes()
    {
        return $this->belongsToMany(Artwork::class, 'likes', 'user_id', 'artwork_id')->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
