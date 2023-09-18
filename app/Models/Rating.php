<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $table    = 'ratings';
    protected $fillable = [
        'users_id',
        'ebooks_id',
        'rating',
        'review',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function ebook()
    {
        return $this->belongsTo(Ebook::class, 'ebooks_id')->withTrashed();
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
