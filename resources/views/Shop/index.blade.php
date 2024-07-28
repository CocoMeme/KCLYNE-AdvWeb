@extends('layouts.app')

<head>
    <meta name="logged-in" content="{{ Auth::check() ? 'true' : 'false' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

@section('content')
<div class="cart-container" id="cart-container">
    <div class="cart-header">
        <a href=""><img src="/Images/Layouts/Logo White.png" alt="Logo">CART</a>
        
        <button class="close-cart"><i class='bx bx-x-circle' ></i></button>
    </div>
    <div class="cart-controls">
            <input type="checkbox" id="select-all-items"> Select All
            <button class="delete-selected" id="delete-selected-items" style="display: none;"><i class="fa-regular fa-trash-can"></i> Delete</button>
    </div>
    <div class="cart-items" id="cartItems">
    </div>
    <div class="checkout-footer">
        <span id="cartTotal">TOTAL: ₱ <span id="cart-total-amount">0.00</span></span>
        <button class="btn btn-primary" id="checkout">CHECKOUT</button>
    </div>
</div>
<div class="cart-sidebar" id="cart-sidebar">
    <i class="fa fa-shopping-cart cart-icon"></i>
    <span id="cart-item-count" class="cart-item-count">0</span>
</div>


<div class="product-shop">
    <div class="products" id="items">
        <!-- Products will be dynamically loaded here -->
    </div>
</div>

<!-- Modal for Add to Cart -->
<div class="modal fade" id="addToCartModal" tabindex="-1" role="dialog" aria-labelledby="addToCartModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addToCartModalLabel">Add to Cart</h5>
                <button type="button" class="close-cart-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body-cart">
                <input type="hidden" id="productId" value="">
                <div class="row-cart">
                    <div class="col-md-4-cart">
                        <img id="productImageModal" src="" alt="Product Image">
                    </div>
                    <div class="col-md-8-cart">
                        <h4 id="productNameModal"></h4>
                        <p id="productPriceModal"></p>
                        <div class="form-group-cart">
                            <label for="productQuantity">Quantity:</label>
                            <input type="number" id="productQuantity" class="form-control-cart" value="1" min="1">
                        </div>
                        <div class="add-to-cart-price">
                            <p>Total Price: ₱<span id="totalPrice">0.00</span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer-cart">
                <button type="button" class="btn btn-primary-cart" id="addToCartButtonModal">Add to Cart</button>
                <button type="button" class="btn btn-secondary-cart" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Checkout Details Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header-checkout">
                <h5 class="modal-title-checkout" id="checkoutModalLabel">Checkout Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body-checkout" id="checkoutDetails">
                <div id="selectedItems"></div>
                <!-- Customer Information will be displayed here -->
                <div id="customerInfo"></div>
            </div>
            <div class="modal-footer-checkout">
                <button type="button" class="btn btn-secondary checkout-close" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary checkout-confirm" id="confirmCheckout">Confirm Checkout</button>
            </div>
        </div>
    </div>
</div>

<!-- Reviews Modal -->
<div class="modal fade" id="reviewsModal" tabindex="-1" role="dialog" aria-labelledby="reviewsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="reviewsModalLabel">Customer Reviews</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body modal-body-scrollable">
                <div id="reviewsContainer"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-close" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/ShopScripts.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
<script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.min.js"></script>
<script src="{{ asset('js/AlgoliaScripts.js') }}"></script>

@endsection
