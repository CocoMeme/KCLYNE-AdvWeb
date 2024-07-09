<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('Shop.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('Shop.show', compact('product'));
    }

    // Additional functions for Shop can be added here as needed
}
