$(document).ready(function() {
    var currentPage = 1;
    var lastPage = null;
    var isFetching = false;

    function fetchData(page = 1) {
        if (lastPage !== null && page > lastPage) return;

        isFetching = true;

        $('#loading').show();

        $.ajax({
            url: "/api/services/fetch?page=" + page,
            dataType: 'json',
            success: function(response) {
                lastPage = response.last_page;
                var services = response.data;
                var html = '';
                services.forEach(function(service) {
                    var averageRating = service.reviews.reduce((acc, review) => acc + review.rating, 0) / (service.reviews.length || 1);
                    var filledStar = service.reviews.length > 0 ? '★' : '☆';
                    html += `
                        <div class="card w-75 post mb-3 service-card">
                            <div class="card-body">
                                <h5 class="card-title-name">${service.service_name}</h5>
                                <p class="card-text-price">₱${service.price}</p>
                                <p class="card-text-description">${service.description}</p>
                                <img src="/images/Services/${service.service_image}" class="card-img-bottom" alt="${service.service_name}">
                                <div class="rating-and-comments">
                                    <span class="star-rating-service" data-service-id="${service.id}">
                                        ${filledStar}
                                        <span class="number-rating">${averageRating.toFixed(1)}</span>
                                    </span>
                                    <span class="comment-icon" data-service-id="${service.id}">
                                        <i class="fa-regular fa-comment"></i>
                                        <span class="comment-number">${service.reviews.length}</span>
                                    </span>
                                </div>
                                <div class="comments-container" id="comments-container-${service.id}"></div>
                            </div>
                        </div>
                    `;
                });
                $('#services-container').append(html);

                if ($('.service-card').length > 0) {
                    var lastServiceCard = $('.service-card').last()[0];
                    observer.observe(lastServiceCard);
                }

                currentPage = page;

                if (currentPage >= lastPage) {
                    $('#load-more').hide();
                }
            },
            error: function() {
                console.log('AJAX load did not work');
                alert("Error fetching services.");
            },
            complete: function() {
                isFetching = false;
                $('#loading').hide();
            }
        });
    }

    var observer = new IntersectionObserver(function(entries) {
        if (entries[0].isIntersecting && !isFetching) {
            var nextPage = currentPage + 1;
            if (nextPage <= lastPage) {
                fetchData(nextPage);
            }
        }
    }, {
        root: null,
        rootMargin: '0px',
        threshold: 1.0
    });

    fetchData();

    $(document).on('click', '.comment-icon', function() {
        var serviceId = $(this).data('service-id');
        var commentsContainer = $('#comments-container-' + serviceId);

        if (commentsContainer.is(':visible')) {
            commentsContainer.hide();
            return;
        }

        $.ajax({
            url: `/api/comments/${serviceId}`,
            dataType: 'json',
            success: function(data) {
                if (data.length) {
                    var commentsHtml = '';
                    $.each(data, function(index, review) {
                        var ratingStars = generateStars(review.rating);
                        var customerImage = review.customer.image ? `/images/customers/${review.customer.image}` : 'default-customer.jpg';

                        var createdAt = new Date(review.date);
                        var formattedDate = createdAt.toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });

                        var reviewHtml = `
                            <div class="comment-item">
                                <img src="${customerImage}" alt="${review.customer.name}">
                                <div class="comment-details">
                                    <div class="comment-rating">
                                        ${ratingStars}
                                    </div>
                                    <p><strong>${review.customer.name}:</strong> ${review.review}</p>
                                    <p class="comment-date">${formattedDate}</p>
                                </div>
                            </div>
                        `;
                        commentsHtml += reviewHtml;
                    });
                    commentsContainer.html(commentsHtml).show();
                } else {
                    commentsContainer.html('<p>No reviews available for this service.</p>').show();
                }
            },
            error: function() {
                console.log('AJAX load did not work');
                alert("Error fetching comments.");
            }
        });
    });

    function generateStars(rating) {
        return '★'.repeat(rating) + '☆'.repeat(5 - rating);
    }
});
