<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Models\ServiceReview;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function getReviewDetails($type, $id)
    {
        $customerId = Auth::id();
        Log::info("Fetching review details for type: $type, id: $id, customer_id: $customerId");
    
        if ($type === 'product') {
            $review = ProductReview::where('product_id', $id)
                                   ->where('customer_id', $customerId)
                                   ->first();
        } elseif ($type === 'service') {
            $review = ServiceReview::where('service_id', $id)
                                   ->where('customer_id', $customerId)
                                   ->first();
        } else {
            return response()->json(['error' => 'Invalid type'], 400);
        }
    
        Log::info("Review fetched: ", ['review' => $review]);
    
        return response()->json($review);
    }
    
    public function submitReview(Request $request, $type)
    {
        $customerId = Auth::id();
        $rating = $request->input('rating');
        $reviewText = $request->input('review');
        $productId = $request->input('id');
    
        Log::info('Submit Review Request:', [
            'customer_id' => $customerId,
            'type' => $type,
            'rating' => $rating,
            'review' => $reviewText,
            'product_id' => $productId
        ]);
    
        try {
            if ($type === 'product') {
                $review = ProductReview::updateOrCreate(
                    ['product_id' => $productId, 'customer_id' => $customerId],
                    ['rating' => $rating, 'review' => $reviewText]
                );
            } elseif ($type === 'service') {
                $review = ServiceReview::updateOrCreate(
                    ['service_id' => $productId, 'customer_id' => $customerId],
                    ['rating' => $rating, 'review' => $reviewText]
                );
            } else {
                Log::error('Invalid type received:', ['type' => $type]);
                return response()->json(['error' => 'Invalid type'], 400);
            }
    
            Log::info('Review successfully created/updated:', ['review' => $review]);
            return response()->json(['success' => 'Review submitted successfully']);
        } catch (\Exception $e) {
            Log::error('Error submitting review:', ['exception' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    public function fetchComments($serviceId)
    {
        $reviews = ServiceReview::with(['customer'])->where('service_id', $serviceId)->get();

        $formattedReviews = $reviews->map(function ($review) {
            return [
                'review' => $review->review,
                'rating' => $review->rating,
                'customer' => [
                    'name' => $review->customer->name,
                    'image' => $review->customer->image,
                ],
                'date' => $review->created_at,
            ];
        });

        return response()->json($formattedReviews);
    }
}
