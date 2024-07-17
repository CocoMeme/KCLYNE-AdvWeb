<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.total_price' => 'required|numeric|min:0',
        ]);
    
        try {
            \DB::beginTransaction();
    
            $order = Order::create([
                'customer_id' => Auth::id(),
                'payment_method' => $request->payment_method,
            ]);
    
            foreach ($request->items as $item) {
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
            }
    
            \DB::commit();
    
            return response()->json(['message' => 'Order placed successfully'], 201);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['message' => 'Failed to place order', 'error' => $e->getMessage()], 500);
        }
    }       
}

