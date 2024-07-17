<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
    
        $product = Product::find($productId);
    
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
    
        $cartItem = Cart::where('product_id', $productId)
                        ->where('customer_id', Auth::id())
                        ->first();
    
        if ($cartItem) {
            if ($quantity >= 1) {
                $cartItem->quantity += $quantity;
            } else {
                $cartItem->quantity = $quantity;
            }
            $cartItem->save();
        } else {
            $cartItem = Cart::create([
                'product_id' => $productId,
                'customer_id' => Auth::id(),
                'quantity' => $quantity,
            ]);
        }
    
        return response()->json([
            "success" => "Product added to cart successfully.",
            "cart" => $cartItem,
            "status" => 200
        ]);
    }    

    public function getCart()
    {
        $cartItems = Cart::where('customer_id', Auth::id())->with('product')->get();

        return response()->json($cartItems, 200);
    }

    public function updateQuantity(Request $request)
    {
        $productId = $request->input('product_id');
        $change = $request->input('change');
        $cartItem = Cart::where('customer_id', auth()->id())
                        ->where('product_id', $productId)
                        ->first();
        
        if ($cartItem) {
            $cartItem->quantity += $change;
            if ($cartItem->quantity <= 0) {
                $cartItem->delete();
            } else {
                $cartItem->save();
            }
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Cart item not found'], 404);
    }

    public function delete($productId)
    {
        $cartItem = Cart::where('customer_id', auth()->id())
                        ->where('product_id', $productId)
                        ->first();
        
        if ($cartItem) {
            $cartItem->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Cart item not found'], 404);
    }

    public function deleteSelected(Request $request)
    {
        $productIds = $request->input('productIds', []);
        if (!is_array($productIds) || empty($productIds)) {
            return response()->json(['error' => 'Invalid or empty product IDs provided.'], 400);
        }
    
        $deleted = Cart::where('customer_id', auth()->id())
                       ->whereIn('product_id', $productIds)
                       ->delete();
    
        if ($deleted) {
            return response()->json(['success' => 'Selected items deleted successfully.']);
        } else {
            return response()->json(['error' => 'Cart items not found or not deletable.'], 404);
        }
    }    
}