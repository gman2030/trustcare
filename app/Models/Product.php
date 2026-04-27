<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'serial_number', 'price', 'quantity', 'image'];
    public function spareParts()
    {
        return $this->hasMany(SparePart::class, 'product_id');
    }
}
