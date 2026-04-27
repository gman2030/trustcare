<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    // الحقول المسموح بتعبئتها بشكل جماعي
    protected $fillable = [
        'user_id',
        'subject',
        'content',
        'warranty_image',
        'status',
        'worker_name',
        'admin_reply'
    ];


    // علاقة الرسالة بالمستخدم (صاحب الرسالة)
    public function user()
    {
         return $this->belongsTo(User::class, 'user_id');

    }
}
