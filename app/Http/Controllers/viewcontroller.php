<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // موديل المنتج
use App\Models\SparePart; // موديل قطع الغيار (تأكد من اسمه عندك)

class viewController extends Controller
{
    public function show($id)
    {
        $product = Product::findOrFail($id);

        $spareParts = SparePart::where('product_id', $id)->get();
        return view('worker.View-Parts', [
            'product' => $product,
            'spareParts' => $spareParts
        ]);
    }
}
