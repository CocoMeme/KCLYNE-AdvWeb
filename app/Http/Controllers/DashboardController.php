<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Models\ServiceReview;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index()
    {
        $productAverageRating = ProductReview::avg('rating');
    
        $serviceAverageRating = ServiceReview::avg('rating');
    
        $orderCount = Order::count();
    
        $customerCount = Customer::count();
    
        $productsRatings = ProductReview::join('products', 'product_reviews.product_id', '=', 'products.id')
            ->select('products.name as product_name', DB::raw('AVG(product_reviews.rating) as avg_rating'))
            ->groupBy('product_name')
            ->get();
    
        $servicesRatings = ServiceReview::join('services', 'service_reviews.service_id', '=', 'services.id')
            ->select('services.service_name as service_name', DB::raw('AVG(service_reviews.rating) as avg_rating'))
            ->groupBy('service_name')
            ->get();
    
        $ordersPerDay = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get()
            ->keyBy('date')
            ->map(function ($item) {
                return $item->count;
            });
    
        $customersPerDay = Customer::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get()
            ->keyBy('date')
            ->map(function ($item) {
                return $item->count;
            });
    
        return view('dashboard.index', compact(
            'productAverageRating', 'serviceAverageRating', 'orderCount', 'customerCount',
            'productsRatings', 'servicesRatings', 'ordersPerDay', 'customersPerDay'
        ));
    }        
}
