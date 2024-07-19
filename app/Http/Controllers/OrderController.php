<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderService;
use App\Models\Product;
use App\Models\Service;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'items' => 'required|array',
            'items.*.type' => 'required|string|in:product,service', // Add type validation for products and services
            'items.*.product_id' => 'required_if:items.*.type,product|exists:products,id',
            'items.*.service_id' => 'required_if:items.*.type,service|exists:services,id',
            'items.*.quantity' => 'required_if:items.*.type,product|integer|min:1',
            'items.*.total_price' => 'required_if:items.*.type,product|numeric|min:0',
        ]);

        Log::info('Request Data: ', $request->all());
    
        try {
            DB::beginTransaction();
    
            $order = Order::create([
                'customer_id' => Auth::id(),
                'payment_method' => $request->payment_method,
            ]);
    
            foreach ($request->items as $item) {
                if ($item['type'] === 'product') {
                    $product = Product::find($item['product_id']);
    
                    if ($product->stock_quantity < $item['quantity']) {
                        throw new \Exception('Product is out of stock');
                    }
    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'total_price' => $item['total_price'],
                    ]);
    
                    $product->decrement('stock_quantity', $item['quantity']);

                    Cart::where('customer_id', Auth::id())
                    ->where('product_id', $item['product_id'])
                    ->delete();
    
                } elseif ($item['type'] === 'service') {
                    OrderService::create([
                        'order_id' => $order->id,
                        'service_id' => $item['service_id'],
                    ]);
                }
            }
    
            DB::commit();
    
            return response()->json(['message' => 'Order placed successfully'], 201);
    
        } catch (\Exception $e) {
            Log::error('Error storing order: ' . $e->getMessage());
            DB::rollback();
            return response()->json(['message' => 'Failed to place order', 'error' => $e->getMessage()], 500);
        }
    }         
}

