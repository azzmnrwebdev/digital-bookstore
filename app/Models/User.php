<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'username',
        'email',
        'gender',
        'tgl_lahir',
        'umur',
        'phone_number',
        'role',
        'password',
        'is_active',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->orderByDesc('created_at');
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'users_id');
    }

    public function author()
    {
        return $this->hasOne(Author::class, 'users_id');
    }

    public function rating()
    {
        return $this->hasMany(Rating::class, 'users_id');
    }

    public function testimonial()
    {
        return $this->hasOne(Testimonial::class, 'users_id');
    }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'users_id');
    }

    public function order()
    {
        return $this->hasMany(Order::class, 'users_id');
    }

    public function getTimeAgo($dateTime)
    {
        $timeDiff = time() - strtotime($dateTime);

        if ($timeDiff < 60) {
            return 'Just now';
        } elseif ($timeDiff < 3600) {
            return floor($timeDiff / 60) . ' minutes ago';
        } elseif ($timeDiff < 86400) {
            return floor($timeDiff / 3600) . ' hours ago';
        } elseif ($timeDiff < 2592000) {
            return floor($timeDiff / 86400) . ' days ago';
        } elseif ($timeDiff < 31536000) {
            return floor($timeDiff / 2592000) . ' months ago';
        } else {
            return floor($timeDiff / 31536000) . ' years ago';
        }
    }
}
