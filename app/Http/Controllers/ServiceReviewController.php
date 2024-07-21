<?php

namespace App\Http\Controllers;

use App\Models\ServiceReview;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceReviewController extends Controller
{
    public function store(Request $request, Service $service)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'review' => 'nullable|string',
        ]);

        ServiceReview::create([
            'service_id' => $service->id,
            'customer_id' => Auth::id(),
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return redirect()->back()->with('message', 'Review submitted successfully.');
    }
}

