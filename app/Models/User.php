<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
=======
>>>>>>> 34e877b1e5638ebf9ca7b65a555643e4543a2062
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
<<<<<<< HEAD
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';
=======
    use HasFactory, Notifiable;
>>>>>>> 34e877b1e5638ebf9ca7b65a555643e4543a2062

    /**
     * The attributes that are mass assignable.
     *
<<<<<<< HEAD
     * @var list<string>
=======
     * @var array<int, string>
>>>>>>> 34e877b1e5638ebf9ca7b65a555643e4543a2062
     */
    protected $fillable = [
        'name',
        'email',
        'password',
<<<<<<< HEAD
        'role'
=======
>>>>>>> 34e877b1e5638ebf9ca7b65a555643e4543a2062
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
<<<<<<< HEAD
     * @var list<string>
     */
    public function systems(): BelongsToMany
    {
        return $this->belongsToMany(System::class, 'system_users')->withTimestamps();
    }
    public static function validRoles(): array
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_USER,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }
=======
     * @var array<int, string>
     */
>>>>>>> 34e877b1e5638ebf9ca7b65a555643e4543a2062
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
        ];
    }
<<<<<<< HEAD
=======

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class, 'author_id');
    }
>>>>>>> 34e877b1e5638ebf9ca7b65a555643e4543a2062
}
