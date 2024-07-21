<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
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
        $products = Product::with('reviews')->get();

        $products = $products->map(function ($product) {
            $averageRating = $product->reviews->avg('rating');
            $ratingsCount = $product->reviews->count();
            $product->average_rating = $averageRating;
            $product->ratings_count = $ratingsCount;
            return $product;
        });

        return response()->json($products);
        return view('Shop.show', compact('product'));
    }

    public function getProductReviews($productId)
    {
        $reviews = ProductReview::where('product_id', $productId)->with('customer.user')->get();

        // Debugging: Log the fetched reviews
        \Log::info('Fetched Reviews:', $reviews->toArray());

        if ($reviews->isEmpty()) {
            \Log::warning("No reviews found for product ID: $productId");
        }

        return response()->json($reviews);
    }

    public function getServices()
    {
        $services = Service::all();
        return response()->json($services);
    }
}
