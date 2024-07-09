$(document).ready(function() {
    $('#ptable').DataTable();

    $('.action-button').on('click', function() {
        var dropdown = $(this).next('.dropdown-menu');
        $('.dropdown-menu').not(dropdown).hide();
        dropdown.toggle();
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.action-button, .dropdown-menu').length) {
            $('.dropdown-menu').hide();
        }
    });
    
    $('#ptable tbody').on('click', 'tr', function () {
        $('#ptable tbody tr').css('background-color', '');
        $(this).css('background-color', '#EE6F57');
        
        var imageSrcs = $(this).data('product-images').split(',');
        var firstImageSrc = imageSrcs.length > 0 ? imageSrcs[0] : 'defaultproduct.jpg';
        var imageUrl = firstImageSrc ? '/images/Products/' + firstImageSrc : '/images/Products/defaultproduct.jpg';

        var name = $(this).find('td').eq(1).text();
        var description = $(this).data('product-description');

        $('#productImage').attr('src', imageUrl)
            .on('error', function() {
                $(this).attr('src', '/images/Products/defaultproduct.jpg');
            });
        $('#productName').text(name);
        $('#productDescription').text(description);
    });

    

    $('.status-button').on('click', function() {
        var productId = $(this).data('id');
        var currentStatus = $(this).data('status');
        var newStatus = currentStatus === 'Verified' ? 'Pending' : 'Verified';

        $.ajax({
            url: `http://127.0.0.1:8000/api/product/status/${productId}`,
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: JSON.stringify({ status: newStatus }),
            success: function(response) {
                alert('Product status updated successfully');
                location.reload();
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });

    $('.delete-button').on('click', function() {
        var productId = $(this).data('id');
        var deleteUrl = `/api/product/delete/${productId}`;
        $('#deleteForm').attr('action', deleteUrl);
        $('#deleteModal').modal('show');
    });

    $('#deleteForm').on('submit', function(e) {
        e.preventDefault();
        var actionUrl = $(this).attr('action');
        deleteProduct(actionUrl);
    });

    function deleteProduct(url) {
        fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Product deleted successfully');
                location.reload();
            } else {
                alert('Error deleting product: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error: Could not delete product');
        });
    }
    
});
