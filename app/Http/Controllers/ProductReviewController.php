<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'review' => 'nullable|string',
        ]);

        ProductReview::create([
            'product_id' => $product->id,
            'customer_id' => Auth::id(),
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return redirect()->back()->with('message', 'Review submitted successfully.');
    }
}

