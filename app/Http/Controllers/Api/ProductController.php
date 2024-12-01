<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'id_barang' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'base_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'disc' => 'nullable|numeric|min:0|max:100',
            'stock' => 'nullable|integer|min:0',
            'unit' => 'nullable|string|max:50',
        ]);

        $product = Product::create($request->all());

        return response()->json([
            'data' => $product,
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'store_id' => 'sometimes|required|exists:stores,id',
            'id_barang' => 'nullable|string|max:255',
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:products,slug,' . $product->id,
            'base_price' => 'sometimes|required|numeric|min:0',
            'sell_price' => 'sometimes|required|numeric|min:0',
            'disc' => 'nullable|numeric|min:0|max:100',
            'stock' => 'nullable|integer|min:0',
            'unit' => 'nullable|string|max:50',
        ]);

        $product->update($request->only([
            'store_id', 'id_barang', 'name', 'slug', 'base_price', 'sell_price', 'disc', 'stock', 'unit'
        ]));

        return response()->json($product);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
