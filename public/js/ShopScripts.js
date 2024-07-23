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
            var imagePaths = value.image_path ? value.image_path.split(',') : ['defaultproduct.jpg'];
            var ratingStars = '';
            for (var i = 1; i <= 5; i++) {
                ratingStars += `<span class="starRating">${i <= value.average_rating ? '★' : '☆'}</span>`;
            }

            var item = `
                <div class='row'>
                    <div class='itemDetails'>
                        <div class='itemImage'>
                            <div class='productImageContainer'>
                                ${imagePaths.map((path, index) => `
                                    <img src="/images/Products/${path.trim()}" class='productImage ${index === 0 ? 'active' : 'hidden'}' />
                                `).join('')}
                                ${value.stock_quantity <= 0 ? '<img src="/Images/Shop/out-of-stock.png" class="outOfStockImage"/>' : ''}
                            </div>
                        </div>
                        <div class='itemText'>
                            <h3>${value.name}</h3>
                            <div class='product-info'>
                                <p class='price-container'>₱ ${value.price}</p>
                                <div class='rating-container'>
                                    ${ratingStars} <p class="rating-count">(${value.ratings_count})</p>
                                </div>
                            </div>
                        </div>
                        <div class='hoverDetails'>
                            <p>${value.description}</p>
                            <div class='hoverButtons'>
                                ${value.stock_quantity > 0 ? `<button class='addToCart' id="addToCartHover" data-product-id="${value.id}" data-product-name="${value.name}" data-product-price="${value.price}"><i class='fa fa-cart-plus'></i> Add to Cart</button>` : ''}
                                <button class='btn btn-secondary viewReview' data-product-id="${value.id}"><i class="fa-regular fa-comments"></i></button>
                            </div>
                        </div>
                    </div>
                </div>`;
            $("#items").append(item);
        });

        // Initialize image flipping
        setInterval(function() {
            $('.productImageContainer').each(function() {
                var images = $(this).find('.productImage');
                var activeImage = images.filter('.active');
                var nextImage = activeImage.next('.productImage').length ? activeImage.next('.productImage') : images.first();

                activeImage.removeClass('active').addClass('hidden');
                nextImage.removeClass('hidden').addClass('active');
            });
        }, 3000); // Change image every 3 seconds

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

    $(document).on('click', '.viewReview', function () {
        var productId = $(this).closest('.itemDetails').find('.viewReview').data('product-id');
        var productName = $(this).closest('.itemDetails').find('h3').text();
        fetchReviews(productId, productName);
    });
    
    function fetchReviews(productId, productName) {
        $.ajax({
            type: "GET",
            url: `/api/products/${productId}/reviews`,
            dataType: 'json',
            success: function (data) {
                console.log('Fetched Reviews:', data);
                renderReviews(data, productName);
                $('#reviewsModal').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('AJAX load did not work:', textStatus, errorThrown);
                alert("Failed to load reviews");
            }
        });
    }
    
    function renderReviews(reviews, productName) {
        var reviewsContainer = $('#reviewsContainer');
        reviewsContainer.empty();
    
        // Calculate summary statistics
        var totalReviews = reviews.length;
        var ratingCounts = { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0 };
        var totalRating = 0;
    
        reviews.forEach(function(review) {
            totalRating += review.rating;
            ratingCounts[review.rating]++;
        });
    
        var averageRating = (totalRating / totalReviews).toFixed(1);

        if (isNaN(averageRating)){
            averageRating = 0.00;
        };
    
        // Generate rating summary HTML
        var summaryHtml = `
            <div class="review-summary">
                <h5 class="modal-title-review">${productName} Reviews & Rating</h5>
                <div class="average-rating">
                    <span class="average-rating-value">${averageRating}</span>
                    <div class="rating-stars">
                        ${generateStars(averageRating)}
                    </div>
                    <span class="total-reviews">(${totalReviews})</span>
                </div>
                <div class="rating-distribution">
                    ${generateRatingDistribution(ratingCounts, totalReviews)}
                </div>
            </div>
        <hr>
        `;
    
        reviewsContainer.append(summaryHtml);
    
        if (reviews.length === 0) {
            reviewsContainer.append('<p>No reviews available for this product.</p>');
        } else {
            reviews.forEach(function(review) {
                var ratingStars = generateStars(review.rating);
    
                var customerImage = review.customer.image ? `/images/customers/${review.customer.image}` : 'default-customer.jpg';
    
                // Parse and format the created_at timestamp
                var createdAt = new Date(review.created_at);
                var formattedDate = createdAt.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
    
                var reviewHtml = `
                    <div class="review">
                        <div class="review-header">
                            <div class="rating-container-modal">${ratingStars}</div>
                            <div class="customer-info">
                                <img src="${customerImage}" alt="${review.customer.name}" class="customer-image"/>
                                <strong>${review.customer.name}</strong>
                            </div>
                        </div>
                        <div class="reviewComment">
                            <p class="reviewParagraph">${review.review}</p>
                        </div>
                        <div class="review-date">${formattedDate}</div>
                    </div>
                    <hr>
                `;
                reviewsContainer.append(reviewHtml);
            });
        }
    }
    
    function generateStars(rating) {
        var stars = '';
        for (var i = 1; i <= 5; i++) {
            stars += `<span class="starRating">${i <= rating ? '★' : '☆'}</span>`;
        }
        return stars;
    }
    
    function generateRatingDistribution(ratingCounts, totalReviews) {
        var distributionHtml = '';
        for (var i = 5; i >= 1; i--) {
            var percentage = ((ratingCounts[i] / totalReviews) * 100).toFixed(1);
            distributionHtml += `
                <div class="rating-bar">
                    <span class="starRating">★</span>
                    <span>${i}</span>
                    <div class="rating-bar-bg">
                        <div class="rating-bar-fill" style="width: ${percentage}%;"></div>
                    </div>
                    <span class="rating-count">${ratingCounts[i]}</span>
                </div>
            `;
        }
        return distributionHtml;
    }      

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
    
    $(document).ready(function () {
        
        $('#checkout').click(function () {
            var selectedItems = [];
            var totalQuantity = 0;
            var totalPrice = 0.00;
    
            $('.cart-item-checkbox:checked').each(function () {
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
    
            var checkoutDetailsHtml = '<h5 class="info-header">Order Information</h5><ul class="list-group">';
            selectedItems.forEach(function (item) {
                checkoutDetailsHtml += `
                    <li class="list-group-item">
                        <p class="checkout-details">${item.quantity} x ₱${item.price.toFixed(2)} | ${item.name} | Total: ₱${(item.price * item.quantity).toFixed(2)}</p>
                    </li>`;
            });
            
            if (selectedItems.length > 0) {
                checkoutDetailsHtml += `<div id="itemTotal">
                    <li class="checkout-item-info">
                        <strong><p>Total Item Quantity: ${totalQuantity}</p></strong>
                        <strong><p>Total Price: ₱${totalPrice.toFixed(2)}</p></strong>
                    </li>
                </div>`;
            }

            checkoutDetailsHtml += '</ul>';
    
            // Fetch customer info
            $.ajax({
                type: "GET",
                url: "/api/customer",
                success: function (data) {
                    let customerInfoHtml = `
                    <br>
                    <h5 class="info-header">Customer Information</h5>
                    <div class="customerInfo">
                        <p><strong>Name:</strong> ${data.name}</p>
                        <p><strong>Email:</strong> ${data.email}</p>
                        <p><strong>Phone:</strong> ${data.phone}</p>
                        <p><strong>Address:</strong> ${data.address}</p>
                    </div>`;
                    $('#checkoutDetails').html(checkoutDetailsHtml + getAvailServicesSectionHtml() + customerInfoHtml);
                    populateServices();
                },
                error: function (error) {
                    console.error("Error fetching customer info:", error);
                    $('#checkoutDetails').html(checkoutDetailsHtml + getAvailServicesSectionHtml() + '<p>Unable to fetch customer info. Please try again later.</p>');
                    populateServices();
                }
            });
    
            $('#checkoutModal').modal('show');
        });
    });
    
    // Function to return the HTML structure for the availed services section
    function getAvailServicesSectionHtml() {
        return `
            <div id="servicesSection">
                <div id="serviceSelection">
                    <select id="serviceSelect" class="form-control">
                        <option value="" selected disabled>Select a service</option>
                        <!-- Options will be populated dynamically -->
                    </select>
                    <div id="serviceDetails">
                    </div>
                </div>
                <div id="availedServicesList">
                    <!-- Selected services will be appended here -->
                </div>
                <div id="grandTotal" style="display: none">
                    GRAND TOTAL: ₱0.00
                </div>
            </div>`;
    }
    
    // Function to populate services in the dropdown and manage selected services
    function populateServices() {
        $.ajax({
            type: "GET",
            url: "/api/get_all_service",
            dataType: 'json',
            success: function (data) {
                console.log(data);
                $('#serviceSelect').empty();
    
                $('#serviceSelect').append(`<option value="" selected disabled>Select a service</option>`);
    
                data.forEach(function (service) {
                    var imagePath = `Images/Services/${service.service_image}`;
                    var serviceItem = `
                        <option value="${service.id}" data-service_name="${service.service_name}" data-price="${service.price}" data-description="${service.description}" data-image_path="${imagePath}">
                            ${service.service_name} - ₱${service.price}
                        </option>`;
                    $('#serviceSelect').append(serviceItem);
                });
    
                $('#serviceSelect').change(function () {
                    var selectedService = $(this).find('option:selected');
                    if (selectedService.val() !== "") {
                        var serviceId = selectedService.val();
                        if ($('#availedServicesList').find(`[data-service-id="${serviceId}"]`).length > 0) {
                            alert('This service is already selected.');
                            $(this).val('').change();
                        } else {
                        var serviceDetails = `
                            <div class="service-item" data-service-id="${serviceId}" data-service-price="${selectedService.data('price')}">
                                <img src="${selectedService.data('image_path')}" alt="${selectedService.data('service_name')}">
                                <strong>${selectedService.data('service_name')}</strong> - ₱${selectedService.data('price')}
                                <button type="button" class="removeServiceButton" data-service-id="${serviceId}">X</button>
                            </div>`;
                        $('#availedServicesList').append(serviceDetails);
    
                            $(this).val('').change();
    
                            updateGrandTotal();
    
                            $('.removeServiceButton').off('click').on('click', function() {
                                $(this).closest('.service-item').remove();
                                updateGrandTotal();
                            });
                        }
                    }
                });
            },
            error: function () {
                alert('Failed to load services');
            }
        });
    }
    
// Function to update the grand total
function updateGrandTotal() {
    var totalPrice = 0.00;

    $('.cart-item-checkbox:checked').each(function () {
        var productPrice = parseFloat($(this).data('product-price'));
        var productQuantity = parseInt($(this).data('product-quantity'));
        totalPrice += (productPrice * productQuantity);
    });

    $('#availedServicesList .service-item').each(function () {
        var servicePrice = parseFloat($(this).data('service-price'));
        totalPrice += servicePrice;
    });

    $('#grandTotal').text(`GRAND TOTAL: ₱${totalPrice.toFixed(2)}`);
    updateGrandTotalDisplay();
} 

// Function to update the display of the grand total
function updateGrandTotalDisplay() {
    if ($('#availedServicesList .service-item').length > 0) {
        $('#grandTotal').css('display', 'block');
    } else {
        $('#grandTotal').css('display', 'none');
    }
}

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
            type: 'product',
            product_id: productId,
            quantity: productQuantity,
            total_price: productPrice * productQuantity
        });

        totalQuantity += productQuantity;
        totalPrice += (productPrice * productQuantity);
    });

    $('#availedServicesList .service-item').each(function() {
        var serviceId = $(this).data('service-id');
        selectedItems.push({
            type: 'service',
            service_id: serviceId
        });
    });

    var orderData = {
        payment_method: 'Cash',
        items: selectedItems
    };

    console.log('Order Data to be sent:', orderData);

    $.ajax({
        type: "POST",
        url: "/api/orders",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: JSON.stringify(orderData),
        contentType: 'application/json',
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

$(document).on('click', '#addToCartHover', function () {
    var loggedIn = $('meta[name="logged-in"]').attr('content');

    if (loggedIn === 'false') {
        alert('Please log in to proceed with checkout.');
        window.location.href = '/login';
        return;
    }
});

$('#cart-sidebar').on('click', function () {

    var loggedIn = $('meta[name="logged-in"]').attr('content');

    if (loggedIn === 'false') {
        alert('Please log in to view your cart.');
        window.location.href = '/login';
    }
    $('#cartSidebar').modal('show');
});

});

