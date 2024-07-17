$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Fetch products and render them
    $.ajax({
        type: "GET",
        url: "/api/products",
        dataType: 'json',
        success: function (data) {
            console.log(data);
            $.each(data, function (key, value) {
                var item = `
                    <div class='row'>
                        <div class='itemDetails'>
                            <div class='itemImage'>
                                <img src="/images/Products/${value.image_path.split(',')[0]}" class='productImage'/>
                                ${value.stock_quantity <= 0 ? '<img src="/Images/Shop/out-of-stock.png" class="outOfStockImage"/>' : ''}
                            </div>
                            <div class='itemText'>
                                <h3>${value.name}</h3>
                                <div class='product-info'>
                                    <p class='price-container'>₱ ${value.price}</p>
                                </div>
                            </div>
                            <div class='hoverDetails'>
                                <p>${value.description}</p>
                                <div class='hoverButtons'>
                                    ${value.stock_quantity > 0 ? `<button class='addToCart' data-product-id="${value.id}" data-product-name="${value.name}" data-product-price="${value.price}"><i class='fa fa-cart-plus'></i> Add to Cart</button>` : ''}
                                    <button class='btn btn-secondary viewReview'><i class="fa-regular fa-comments"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>`;
                $("#items").append(item);
            });

            // Hover effect
            $('.itemDetails').hover(function () {
                $(this).find('.hoverDetails').fadeIn(100);
            }, function () {
                $(this).find('.hoverDetails').fadeOut(100);
            });
        },
        error: function () {
            console.log('AJAX load did not work');
            alert("error");
        }
    });

    // Add to Cart button click handler
    $(document).on('click', '.addToCart', function () {
        var productId = $(this).data('product-id');
        var productName = $(this).data('product-name');
        var productPrice = $(this).data('product-price');
        var productImage = $(this).closest('.itemDetails').find('.itemImage img').attr('src');

        $('#productImageModal').attr('src', productImage);
        $('#productNameModal').text(productName);
        $('#productPriceModal').text('₱ ' + productPrice);
        $('#productQuantity').val(1);
        $('#productId').val(productId);

        updateTotalPrice();

        $('#addToCartModal').modal('show');
    });

    // Function to update total price based on quantity input
    function updateTotalPrice() {
        var quantity = parseInt($('#productQuantity').val(), 10) || 1;
        var price = parseFloat($('#productPriceModal').text().replace('₱ ', '').trim());
        var totalPrice = quantity * price;
        $('#totalPrice').text(totalPrice.toFixed(2));
    }

    $('#productQuantity').on('input', function() {
        updateTotalPrice();
    });

    // Function to handle adding to cart
    $('#addToCartButtonModal').click(function () {
        var quantity = $('#productQuantity').val();
        var productId = $('#productId').val();
        $.ajax({
            type: "POST",
            url: "/api/cart/add",
            dataType: 'json',
            data: {
                product_id: productId,
                quantity: quantity,
            },
            success: function (response) {
                alert('Product added to cart successfully');
                updateCartDisplay();
                $('#addToCartModal').modal('hide');
            },
            error: function () {
                alert('Failed to add product to cart');
            }
        });
    });

// Function to update cart display
function updateCartDisplay() {
    $.ajax({
        type: "GET",
        url: "/api/cart",
        dataType: 'json',
        success: function (data) {
            $('#cartItems').empty();
            var totalItems = 0;

            data.forEach(function (item) {
                if (item.product.stock_quantity > 0) {
                    var itemTotalPrice = (item.product.price * item.quantity).toFixed(2);
                    var cartItem = `
                    <div class="cart-item-row" data-product-id="${item.product.id}">
                        <input type="checkbox" class="cart-item-checkbox" data-product-price="${item.product.price}" data-product-quantity="${item.quantity}">
                        <img src="/images/Products/${item.product.image_path.split(',')[0]}" alt="${item.product.name}" class="cart-item-image">
                        <div class="cart-item-details">
                            <h5>${item.product.name}</h5>
                            <p>₱ ${itemTotalPrice}</p>
                            <div class="quantity-control">
                                <button class="quantity-decrease" data-product-id="${item.product.id}">-</button>
                                <span class="quantity">${item.quantity}</span>
                                <button class="quantity-increase" data-product-id="${item.product.id}">+</button>
                            </div>
                        </div>
                        <button class="delete-item" style="display: none;" data-product-id="${item.product.id}">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </div>`;
                    $('#cartItems').append(cartItem);
                    totalItems += item.quantity;
                } else {
                    deleteCartItem(item.product.id);
                }
            });

            $('#cart-item-count').text(totalItems);

            // Add event listener for checkboxes
            $('.cart-item-checkbox').on('change', function () {
                $(this).siblings('.delete-item').toggle(this.checked);
                updateTotalPriceSidebar();
            });

            // Add event listener for select all checkbox
            $('#select-all-items').on('change', function () {
                var isChecked = $(this).is(':checked');
                $('.cart-item-checkbox').prop('checked', isChecked);
                $('.delete-item').toggle(isChecked);
                updateTotalPriceSidebar();
            });

            // Add event listeners for quantity buttons
            $('.quantity-decrease').on('click', function () {
                updateCartItemQuantity($(this).data('product-id'), -1);
            });
            $('.quantity-increase').on('click', function () {
                updateCartItemQuantity($(this).data('product-id'), 1);
            });

            // Add event listener for delete buttons
            $('.delete-item').on('click', function () {
                var productId = $(this).data('product-id');
                deleteCartItem(productId);
            
                var price = $(this).closest('.cart-item-row').find('.cart-item-checkbox').data('product-price');
                var quantity = $(this).closest('.cart-item-row').find('.cart-item-checkbox').data('product-quantity');
                var totalPrice = parseFloat($('#cartTotal').text().replace(/[^\d.-]/g, ''));
            
                totalPrice -= price * quantity;
                if (isNaN(totalPrice)){
                    totalPrice = 0.00;
                    $('#cartTotal').text('Total: ₱ ' + totalPrice.toFixed(2));
                } else {
                    $('#cartTotal').text('Total: ₱ ' + totalPrice.toFixed(2));
                }
            });
        },
        error: function () {
            alert('Failed to fetch cart items, kindly login first.');
            window.location.href = '/login';
        }
    });
}

    // Function to update cart item quantity
    function updateCartItemQuantity(productId, change) {
        $.ajax({
            type: "POST",
            url: "/api/cart/update",
            data: {
                product_id: productId,
                change: change,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function () {
                updateCartDisplay();
            },
            error: function () {
                alert('Failed to update cart item quantity');
            }
        });
    }

    // Function to delete cart item
    function deleteCartItem(productId) {
        $.ajax({
            type: "DELETE",
            url: `/api/cart/${productId}`,
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function () {
                updateCartDisplay();
            },
            error: function () {
                alert('Failed to delete cart item');
            }
        });
    }

            // $('#delete-selected-items').on('click', function () {
            //     var selectedProductIds = [];
            //     $('.cart-item-checkbox:checked').each(function () {
            //         selectedProductIds.push($(this).closest('.cart-item-row').data('product-id'));
            //     });
            
            //     console.log('Selected Product IDs:', selectedProductIds);
            
            //     if (selectedProductIds.length > 0) {
            //         deleteCartItems(selectedProductIds);
            //     } else {
            //         alert('No items selected for deletion.');
            //     }
            
            //     $('#select-all-items').prop('checked', false);
            //     updateTotalPriceSidebar();
            // }); 

    // Function to delete one or more cart items
    function deleteCartItems(productIds) {
        $.ajax({
            type: "DELETE",
            url: `/cart/delete-selected`,
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                productIds: productIds
            },
            traditional: true,
            success: function () {
                updateCartDisplay();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown);
                console.log('Response:', jqXHR.responseText);
                alert('Failed to delete selected cart items');
            }
        });
    }

    // Function to update total price
    function updateTotalPriceSidebar() {
        var totalPrice = 0;
        $('.cart-item-checkbox:checked').each(function () {
            var price = $(this).data('product-price');
            var quantity = $(this).data('product-quantity');
            totalPrice += price * quantity;
        });
        $('#cartTotal').text('Total: ₱ ' + totalPrice.toFixed(2));
    }

    updateCartDisplay();

    $(".cart-sidebar").click(function () {
        $(".cart-container").toggleClass("active");
        updateCartDisplay();
    });

    $(".close-cart").click(function () {
        $(".cart-container").removeClass("active");
    });
    
    $('#checkout').click(function() {
        var selectedItems = [];
        var totalQuantity = 0;
        var totalPrice = 0.00;

        // Collect selected cart items
        $('.cart-item-checkbox:checked').each(function() {
            var productId = $(this).closest('.cart-item-row').data('product-id');
            var productName = $(this).closest('.cart-item-row').find('h5').text();
            var productPrice = parseFloat($(this).data('product-price'));
            var productQuantity = parseInt($(this).data('product-quantity'));
    
            selectedItems.push({
                id: productId,
                name: productName,
                price: productPrice,
                quantity: productQuantity
            });
    
            totalQuantity += productQuantity;
            totalPrice += (productPrice * productQuantity);
        });
    
            var checkoutDetailsHtml = '<h5>Order Information</h5><ul class="list-group">';
            selectedItems.forEach(function(item) {
                checkoutDetailsHtml += `
                    <li class="list-group-item">
                        <p class="checkout-details">${item.quantity} x ₱${item.price.toFixed(2)} | ${item.name} | Total: ₱${(item.price * item.quantity).toFixed(2)}</p>
                    </li>`;
            });
            checkoutDetailsHtml += `
                <li class="checkout-item-info">
                    <strong><p>Total Item Quantity: ${totalQuantity}</p></strong>
                    <strong><p>Total Price: ₱${totalPrice.toFixed(2)}</p></strong>
                </li>
            </ul>`;
    
            // Fetch customer info
            $.ajax({
                type: "GET",
                url: "/api/customer",
                success: function(data) {
                    let customerInfoHtml = `
                    <br>
                    <h5>Customer Information</h5>
                    <div class="customerInfo">
                        <p><strong>Name:</strong> ${data.name}</p>
                        <p><strong>Email:</strong> ${data.email}</p>
                        <p><strong>Phone:</strong> ${data.phone}</p>
                        <p><strong>Address:</strong> ${data.address}</p>
                    </div>`;
                    $('#checkoutDetails').html(checkoutDetailsHtml + customerInfoHtml);
                },
                error: function(error) {
                    console.error("Error fetching customer info:", error);
                    $('#checkoutDetails').html(checkoutDetailsHtml + '<p>Unable to fetch customer info. Please try again later.</p>');
                }
            });
    
            $('#checkoutModal').modal('show');
        });
    
        // Event listener for confirm checkout button
        $('#confirmCheckout').click(function() {
            var selectedItems = [];
            var totalQuantity = 0;
            var totalPrice = 0.00;
    
            $('.cart-item-checkbox:checked').each(function() {
                var productId = $(this).closest('.cart-item-row').data('product-id');
                var productName = $(this).closest('.cart-item-row').find('h5').text();
                var productPrice = parseFloat($(this).data('product-price'));
                var productQuantity = parseInt($(this).data('product-quantity'));
    
                selectedItems.push({
                    product_id: productId,
                    quantity: productQuantity,
                    total_price: productPrice * productQuantity
                });
    
                totalQuantity += productQuantity;
                totalPrice += (productPrice * productQuantity);
            });
    
            var orderData = {
                payment_method: 'Credit Card',
                quantity: totalQuantity,
                total_price: totalPrice,
                items: selectedItems
            };
    
            $.ajax({
                type: "POST",
                url: "/api/orders",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: orderData,
                success: function(response) {
                    console.log("Order successfully created:", response);
                    alert('Order successfully placed!');
                    window.location.href = '/thank-you';
                },
                error: function(error) {
                    console.error("Error creating order:", error);
                    alert('Failed to place order. Please try again.');
                }
            });
        });
});
