<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Author extends Model
{
    use HasFactory, Notifiable;

    protected $table    = 'authors';
    protected $fillable = [
        'users_id',
        'bio',
        'avatar',
        'background',
        'cv'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function ebooks()
    {
        return $this->belongsToMany(Ebook::class, 'ebook_authors', 'authors_id', 'ebooks_id');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->orderByDesc('created_at');
    }
}
