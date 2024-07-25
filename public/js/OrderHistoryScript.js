$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.tab-button[data-tab="products"]').addClass('active');
    
    $('#products-tab').show();
    
    fetchOrders('products');

    $('.tab-button').on('click', function() {
        $('.tab-button').removeClass('active');
        $(this).addClass('active');
        
        var tabId = $(this).data('tab');
        $('.tab-content').hide();
        $('#' + tabId + '-tab').show();
        
        fetchOrders(tabId);
    });

    function fetchOrders(type) {
        $.ajax({
            type: "GET",
            url: "/api/order-details",
            data: { type: type },
            dataType: 'json',
            success: function(data) {
                var tableContent = '';
    
                if (type === 'products') {
                    tableContent = `<table class="order-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                                <th>Ordered On</th>
                                <th>Review and Rating</th>
                            </tr>
                        </thead>
                        <tbody>`;
    
                    $.each(data, function(index, item) {
                        tableContent += `<tr>
                            <td><img src="/Images/Products/${item.first_image}" alt="${item.product.name}" class="order-image"></td>
                            <td>${item.product.name}</td>
                            <td>${item.quantity}</td>
                            <td>₱${item.total_price}</td>
                            <td>${new Date(item.created_at).toLocaleDateString()}</td>
                            <td>
                                <button class="review-button" data-reviewed="${item.reviewed}" data-id="${item.product_id}" data-type="product">
                                    ${item.reviewed ? "<i class='bx bx-check-circle' ></i> Done" : "<i class='bx bx-list-plus' ></i> Review"}
                                </button>
                                <div class="review-content" id="review-content-${item.product_id}" style="display: none;"></div>
                            </td>
                        </tr>`;
                    });
    
                    tableContent += `</tbody></table>`;
                } else if (type === 'services') {
                    tableContent = `<table class="order-table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Service Name</th>
                                <th>Ordered On</th>
                                <th>Review and Rating</th>
                            </tr>
                        </thead>
                        <tbody>`;
    
                    $.each(data, function(index, item) {
                        tableContent += `<tr>
                            <td><img src="/Images/Services/${item.service.service_image}" alt="${item.service.service_name}" class="order-image"></td>
                            <td>${item.service.service_name}</td>
                            <td>${new Date(item.created_at).toLocaleDateString()}</td>
                            <td>
                                <button class="review-button" data-reviewed="${item.reviewed}" data-id="${item.service_id}" data-type="service">
                                    ${item.reviewed ? 'Done' : 'Review >>'}
                                </button>
                                <div class="review-content" id="review-content-${item.service_id}" style="display: none;"></div>
                            </td>
                        </tr>`;
                    });
    
                    tableContent += `</tbody></table>`;
                }
    
                $('#' + type + '-tab').html(tableContent);
                bindReviewButtons();
            },
            error: function() {
                console.log('AJAX load did not work');
                alert("error");
            }
        });
    }    
    
    function bindReviewButtons() {
        $('.review-button').on('click', function() {
            var reviewed = $(this).data('reviewed');
            var id = $(this).data('id');
            var type = $(this).data('type');
            var reviewContent = $('#review-content-' + id);
    
            if (reviewed) {
                $.ajax({
                    type: "GET",
                    url: `/api/review-details/${type}/${id}`,
                    dataType: 'json',
                    success: function(data) {
                        console.log('Review data fetched:', data);
                        if (data) {
                            var ratingStars = '';
                            for (var i = 1; i <= 5; i++) {
                                ratingStars += `<span class="starRating">${i <= data.rating ? '★' : '☆'}</span>`;
                            }
                            reviewContent.html(`
                                <strong><p>Rating: </strong>${ratingStars}</p>
                                <strong><p>Review: </strong>${data.review}</p>
                            `).toggle();
                        } else {
                            alert('No review found for this product/service.');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('AJAX load did not work');
                        console.error('Status:', jqXHR.status);
                        console.error('Text Status:', textStatus);
                        console.error('Error Thrown:', errorThrown);
                        console.error('Response Text:', jqXHR.responseText);
                        alert("error");
                    }
                });
            } else {
                reviewContent.html(`
                    <div class="review-form">
                        <strong><label>Rating:</label></strong>
                        <div class="star-rating-submit" id="star-rating-${id}">
                            <span class="star-submit" data-value="1">☆</span>
                            <span class="star-submit" data-value="2">☆</span>
                            <span class="star-submit" data-value="3">☆</span>
                            <span class="star-submit" data-value="4">☆</span>
                            <span class="star-submit" data-value="5">☆</span>
                        </div>
                        <input type="hidden" id="rating-${id}" class="rating" value="">
                        <strong><label for="review-${id}">Review:</label></strong>
                        <textarea id="review-${id}" class="review-text"></textarea>
                        <button class="submit-review-button" data-id="${id}" data-type="${type}">Submit Review</button>
                    </div>
                `).toggle();
    
                // Bind star rating click event
                $('#star-rating-' + id + ' .star-submit').on('click', function() {
                    var rating = $(this).data('value');
                    $('#rating-' + id).val(rating);
                    updateStarDisplay(id, rating);
                });
    
                $('.submit-review-button').on('click', function() {
                    var reviewId = $(this).data('id');
                    var reviewType = $(this).data('type');
                    var rating = $('#rating-' + reviewId).val();
                    var reviewText = $('#review-' + reviewId).val();
    
                    console.log('Submitting review:', {
                        id: reviewId,
                        rating: rating,
                        review: reviewText,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    });
    
                    $.ajax({
                        type: "POST",
                        url: `/api/submit-review/${reviewType}`,
                        data: {
                            id: reviewId,
                            rating: rating,
                            review: reviewText,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            $("#loader").show();
                        },
                        success: function(response) {
                            $("#loader").hide();
                    
                            Swal.fire({
                                title: 'Review Submitted!',
                                text: response.message,
                                icon: 'success'
                            }).then(() => {
                                $(`button[data-id="${reviewId}"]`).data('reviewed', true).text('Done');
                                reviewContent.hide();
                            });
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $("#loader").hide();
                    
                            console.log('AJAX load did not work');
                            console.error('Status:', jqXHR.status);
                            console.error('Text Status:', textStatus);
                            console.error('Error Thrown:', errorThrown);
                            console.error('Response Text:', jqXHR.responseText);
                    
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to submit review. Please try again.',
                                icon: 'error'
                            });
                        }
                    });                   
                });
            }
        });
    }      
    
    function updateStarDisplay(id, rating) {
        var stars = $('#star-rating-' + id + ' .star-submit');
        stars.each(function() {
            var starValue = $(this).data('value');
            if (starValue <= rating) {
                $(this).text('★').addClass('filled');
            } else {
                $(this).text('☆').removeClass('filled');
            }
        });
    }        
});    