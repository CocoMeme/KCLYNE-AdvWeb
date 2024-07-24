$(document).ready(function() {
    function fetchData() {
        var start = Number($('#start').val());
        var totalRecords = Number($('#totalrecords').val());
        var rowPerPage = Number($('#rowperpage').val());

        if (start < totalRecords) {
            $.ajax({
                url: "{{ route('services.getcustomer_service_index') }}",
                data: { start: start },
                dataType: 'json',
                success: function(response) {
                    $('#services-container').append(response.html);
                    $('#start').val(start + rowPerPage);
                    checkWindowSize();
                },
                error: function() {
                    console.log('AJAX load did not work');
                    alert("Error fetching services.");
                }
            });
        }
    }

    function checkWindowSize() {
        if ($(window).height() >= $(document).height()) {
            fetchData();
        }
    }

    checkWindowSize();

    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            fetchData();
        }
    });

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
