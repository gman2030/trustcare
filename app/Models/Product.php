<?php

namespace App\Models;

use App\Support\PublicImageUrl;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'serial_number', 'price', 'quantity', 'image'];

    public function getImageUrlAttribute(): string
    {
        return PublicImageUrl::fromPath($this->image);
    }

    public function spareParts()
    {
        return $this->hasMany(SparePart::class, 'product_id');
    }
}
