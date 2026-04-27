<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Added this
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SparePart;
use App\Models\SparePartOrder;
use Illuminate\Support\Facades\Hash;

class Supplychain_controller extends Controller
{
    public function index(Request $request)
    {
        // Consistency check: The role is 'supply'
        if (trim(Auth::user()->role) !== 'supply') {
            abort(403, 'Unauthorized access.');
        }

        $query = Product::query();

        if ($request->filled('search_sn')) {
            $query->where('serial_number', 'LIKE', '%' . $request->search_sn . '%');
        }

        $products = $query->latest()->get();

        return view('supplychain.supply_chain', compact('products'));
    }

    public function create()
    {
        return view('supplychain.add_product');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'serial_number' => 'required|unique:products,serial_number',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->serial_number = $request->serial_number;
        $product->price = 0;
        $product->quantity = 0;

        if ($request->hasFile('image')) {
            // Safe Cloud Storage
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->save();

        return redirect()->route('supply.dashboard')->with('success', 'Product registered successfully!');
    }

    public function edit($id)
    {
        $product = Product::with('spareParts')->findOrFail($id);
        return view('supplychain.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'serial_number' => 'required|unique:products,serial_number,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $product->name = $request->name;
        $product->serial_number = $request->serial_number;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->save();

        if ($request->has('existing_parts')) {
            foreach ($request->existing_parts as $partId => $data) {
                SparePart::where('id', $partId)->update([
                    'quantity' => $data['quantity'] ?? 0,
                    'price'    => $data['price'] ?? 0,
                ]);
            }
        }

        return redirect()->route('supply.dashboard')->with('success', 'All changes saved successfully!');
    }

    public function storeSparePart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'part_name'  => 'required|max:255',
            'part_image' => 'required|image|max:2048',
            'quantity'   => 'required|integer|min:0',
            'price'      => 'required|numeric|min:0',
        ]);

        $part = new SparePart();
        $part->product_id = $request->product_id;
        $part->name       = $request->part_name;
        $part->quantity   = $request->quantity;
        $part->price      = $request->price;

        if ($request->hasFile('part_image')) {
            // Safe Cloud Storage
            $part->image = $request->file('part_image')->store('parts', 'public');
        }

        $part->save();
        return back()->with('success', 'Component added successfully!');
    }

    public function destroySparePart($id)
    {
        $part = SparePart::findOrFail($id);

        if ($part->image) {
            Storage::disk('public')->delete($part->image);
        }

        $part->delete();
        return back()->with('success', 'Component removed successfully!');
    }

    // ... keeping the rest of your logic functions ...
    public function updateStock($id, $action) { /* ... */ }
    public function destroy($id) { /* ... */ }
    public function show($id) { /* ... */ }
    public function updateSparePart(Request $request, $id) { /* ... */ }
    public function receivedRequests() { /* ... */ }
    public function markAsPrepared($id) { /* ... */ }
    public function rejectBySupply(Request $request, $id) { /* ... */ }
}
