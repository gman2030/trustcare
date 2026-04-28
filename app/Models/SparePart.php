<?php

namespace App\Models;

use App\Support\PublicImageUrl;
use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
    protected $fillable = ['product_id', 'name', 'image', 'quantity','price'];

    public function getImageUrlAttribute(): string
    {
        return PublicImageUrl::fromPath($this->image);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
