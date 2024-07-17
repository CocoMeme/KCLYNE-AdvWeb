$(document).ready(function() {
    $('#ptable').DataTable();

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

    // Open create product modal
    $('#openCreateProductModal').on('click', function (e) {
        e.preventDefault();
        $('#createProductModal').show();
        setTimeout(() => {
            $('#createProductModal').css('opacity', '1');
        }, 10);
    });

    // Close create product modal
    $('#closeCreateProductModal, #closeCreateProductModalFooter').on('click', function () {
        $('#createProductModal').css('opacity', '0');
        setTimeout(() => {
            $('#createProductModal').hide();
        }, 300);
    });

    // Open edit product modal
    $('.edit-button').on('click', function () {
        var productId = $(this).data('id');
        var productName = $(this).data('name');
        var productDescription = $(this).data('description');
        var productPrice = $(this).data('price');
        var productStock = $(this).data('stock-quantity');

        $('#editProductId').val(productId);
        $('#editName').val(productName);
        $('#editDescription').val(productDescription);
        $('#editPrice').val(productPrice);
        $('#editStockQuantity').val(productStock);

        // Set the form action URL dynamically for PUT request
        $('#editProductForm').attr('action', `/product/${productId}`);

        $('#editProductModal').show();
        setTimeout(() => {
            $('#editProductModal').css('opacity', '1');
        }, 10);
    });


    // Close edit product modal
    $('#closeEditProductModal, #closeEditProductModalFooter').on('click', function () {
        $('#editProductModal').css('opacity', '0');
        setTimeout(() => {
            $('#editProductModal').hide();
        }, 300);
    });

    // Open delete product modal
    $('.delete-button').on('click', function () {
        var productId = $(this).data('id');
        var deleteUrl = `/api/product/delete/${productId}`;
        $('#deleteForm').attr('action', deleteUrl);
        $('#deleteModal').show();
        setTimeout(() => {
            $('#deleteModal').css('opacity', '1');
        }, 10);
    });

    // Close delete product modal
    $('#closeDeleteModal, #cancelDeleteModal').on('click', function () {
        $('#deleteModal').css('opacity', '0');
        setTimeout(() => {
            $('#deleteModal').hide();
        }, 300);
    });

    // Handle delete form submission
    $('#deleteForm').on('submit', function (e) {
        e.preventDefault();
        var actionUrl = $(this).attr('action');
        deleteProduct(actionUrl);
    });

    function deleteProduct(url) {
        fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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

    // Import product modal logic
    $('#openImportProductModal').on('click', function (e) {
        e.preventDefault();
        $('#importProductModal').show();
        setTimeout(() => {
            $('#importProductModal').css('opacity', '1');
        }, 10);
    });

    $('#closeImportProductModal, #cancelImportProductModal').on('click', function () {
        $('#importProductModal').css('opacity', '0');
        setTimeout(() => {
            $('#importProductModal').hide();
        }, 300);
    });

    // Ensure modal content is cleared on hide
    $('#createProductModal').on('hidden.bs.modal', function () {
        $('#createProductModal .modal-content').html('');
    });

    $('#editProductModal').on('hidden.bs.modal', function () {
        $('#editProductModal .modal-content').html('');
    });
});
