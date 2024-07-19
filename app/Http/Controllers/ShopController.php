<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        return view('Shop.index');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('Shop.show', compact('product'));
    }

    public function getProducts()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function getServices()
    {
        $services = Service::all();
        return response()->json($services);
    }
}
