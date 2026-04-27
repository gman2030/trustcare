<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SparePartOrder extends Model
{
    protected $fillable = [
        'worker_id',
        'user_id',
        'product_id',
        'items',
        'status',
        'is_warranty',
        'subtotal',
        'vat_rate',
        'total_ttc'
    ];

    // ربط الطلب بالعامل
    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    // ربط الطلب بالمنتج (الآلة)
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    protected $casts = [
        'items' => 'array',
    ];
    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
