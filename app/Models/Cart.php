<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table    = 'carts';
    protected $fillable = [
        'users_id',
        'ebooks_id',
        'quantity',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function ebook()
    {
        return $this->belongsTo(Ebook::class, 'ebooks_id');
    }
}
