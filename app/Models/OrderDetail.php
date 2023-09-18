<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table    = 'order_details';
    protected $fillable = [
        'orders_id',
        'ebooks_id',
        'quantity'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'orders_id');
    }

    public function ebook()
    {
        return $this->belongsTo(Ebook::class, 'ebooks_id')->withTrashed();
    }
}
