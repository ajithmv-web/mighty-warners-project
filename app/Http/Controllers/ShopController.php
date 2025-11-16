<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'color', 'size'])
            ->inStock()
            ->paginate(12);
        
        return view('shop.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'color', 'size']);
        
        return view('shop.show', compact('product'));
    }
}
