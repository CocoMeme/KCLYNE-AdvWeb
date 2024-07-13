@extends('layouts.app')

@section('content')

    <div class="shop-container">
        <h1>Your Shopping Cart</h1>
        <div id="cart-container">
            <div id="cart">
                <i class="fa fa-shopping-cart fa-2x openCloseCart" aria-hidden="true"></i>
                <button id="emptyCart" class="btn btn-warning">Empty Cart</button>
            </div>
            <span id="itemCount" style="display: none;"></span>
        </div>
        
        <div id="shoppingCart" style="display: none;">
            <div id="cartItemsContainer">
                <h2>Items in your cart</h2>
                <i class="fas fa-times-circle"></i>
                <div id="cartItems"></div>
                <button class="btn btn-primary" id="checkout">Checkout</button>
                <button class="btn btn-secondary" id="close">Close</button>
                <span id="cartTotal">Total: ₱ 0.00</span>
            </div>
        </div>

        <nav>
            <ul>
                <li><a href="{{ route('shop.index') }}">Shopping Cart</a></li>
            </ul>
        </nav>

        <div class="product-shop">
            <div class="products" id="items">

            </div>
        </div>
    </div>

    <script src="{{ asset('js/ShopScripts.js') }}"></script>
@endsection
