<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class SearchController extends Controller
{
    public function searchBySerialNumber($sn)
    {
        $product = \App\Models\Product::where('serial_number', $sn)->first();
        if ($product) {
            return response()->json([
                'id' => $product->id,
                'name' => $product->name,
                'image' => $product->image, 
                'serial_number' => $product->serial_number,
                'category' => $product->category
            ]);
        }
        return response()->json(['error' => 'Not Found'], 404);
    }
}
