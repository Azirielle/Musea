<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    const ROLE_MEMBER = 'member';
    const ROLE_VERIFIED_MEMBER = 'verified_member';
    const ROLE_ARTIST = 'artist';

    const VERIFICATION_NONE = 'none';
    const VERIFICATION_PENDING = 'pending';
    const VERIFICATION_APPROVED = 'approved';
    const VERIFICATION_REJECTED = 'rejected';


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
        'is_featured',
        'bio',
        'gcash_number',
        'bank_details',
        'role',
        'is_verified',
        'verification_status',
        'portfolio_url',
        'requested_role',
        'last_seen_at',
    ];


    protected $appends = ['avatar', 'is_online'];

    public function getIsOnlineAttribute()
    {
        return $this->last_seen_at && $this->last_seen_at->gt(now()->subMinutes(5));
    }

    public function getAvatarAttribute()
    {
        return $this->imageUrl();
    }

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
            'is_featured' => 'boolean',
            'is_verified' => 'boolean',
            'last_seen_at' => 'datetime',
            'balance' => 'decimal:2',
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
        $path = $this->avatar_path ?: $this->avatar_url;

        \Log::info('imageUrl() called', [
            'user_id' => $this->id,
            'avatar_path' => $this->avatar_path,
            'avatar_url_column' => $this->avatar_url ?? 'null',
            'path_variable' => $path,
        ]);

        // No path - use UI Avatars as fallback
        if (!$path) {
            $fallback = 'https://ui-avatars.com/api/?name=' . urlencode($this->first_name . ' ' . $this->last_name) . '&color=7F9CF5&background=EBF4FF';
            \Log::info('imageUrl() returning fallback (no path)', ['url' => $fallback]);
            return $fallback;
        }

        // Already a full URL (Cloudinary or external)
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            \Log::info('imageUrl() returning full URL', ['url' => $path]);
            return $path;
        }

        // Local path - check if file exists
        if (Storage::disk('public')->exists($path)) {
            $localUrl = asset('storage/' . $path);
            \Log::info('imageUrl() returning local storage URL', ['url' => $localUrl]);
            return $localUrl;
        }

        // Path exists in DB but file doesn't exist on disk
        // This can happen on ephemeral filesystems like Render
        // Fall back to UI Avatars
        $fallback = 'https://ui-avatars.com/api/?name=' . urlencode($this->first_name . ' ' . $this->last_name) . '&color=7F9CF5&background=EBF4FF';
        \Log::info('imageUrl() returning fallback (file not found)', ['path' => $path, 'url' => $fallback]);
        return $fallback;
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

    public function withdrawalRequests()
    {
        return $this->hasMany(WithdrawalRequest::class);
    }

    public function buyerConversations()
    {
        return $this->hasMany(Conversation::class, 'buyer_id');
    }

    public function artistConversations()
    {
        return $this->hasMany(Conversation::class, 'artist_id');
    }

    public function conversations()
    {
        return Conversation::where('buyer_id', $this->id)->orWhere('artist_id', $this->id);
    }

    public function unreadMessagesCount()
    {
        return ChatMessage::whereHas('conversation', function ($query) {
            $query->where('buyer_id', $this->id)->orWhere('artist_id', $this->id);
        })
            ->where('sender_id', '!=', $this->id)
            ->where('is_read', false)
            ->count();
    }
}
