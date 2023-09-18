<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ebook extends Model
{
    use HasFactory, SoftDeletes;

    protected $table    = 'ebooks';
    protected $fillable = [
        'isbn',
        'title',
        'slug',
        'status',
        'price',
        'description',
        'pdf',
        'password',
        'thumbnail'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'ebook_categories', 'ebooks_id', 'categories_id');
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'ebook_authors', 'ebooks_id', 'authors_id')->orderByPivot('uploaded_by', 'desc');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'ebooks_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'ebooks_id');
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
