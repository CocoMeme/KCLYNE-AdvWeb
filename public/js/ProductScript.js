$(document).ready(function() {
    $('#ptable').DataTable();

    $('#createProductForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#createProductModal').hide();
                if (response.success) {
                    Swal.fire('Product Created!', response.message, 'success')
                        .then(() => {
                            location.reload();
                        });
                } else {
                    $('#createProductErrorMessage').text(response.error).show();
                }
            },
            error: function(response) {
                var errors = response.responseJSON.errors;
                var errorMessage = '';
                $.each(errors, function(key, value) {
                    errorMessage += value[0] + '<br>';
                });
                $('#createProductErrorMessage').html(errorMessage).show();
            }
        });
    });

    $('#openCreateProductModal').on('click', function (e) {
        e.preventDefault();
        $('#createProductModal').show();
        $('#createProductErrorMessage').hide();
        setTimeout(() => {
            $('#createProductModal').css('opacity', '1');
        }, 10);
    });

    $('#closeCreateProductModal, #closeCreateProductModalFooter').on('click', function () {
        $('#createProductModal').css('opacity', '0');
        setTimeout(() => {
            $('#createProductModal').hide();
            $('#createProductErrorMessage').hide();
        }, 300);
    });

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

        $('#editProductForm').attr('action', `/product/${productId}`);

        $('#editProductModal').show();
        setTimeout(() => {
            $('#editProductModal').css('opacity', '1');
        }, 10);
    });


    $('#closeEditProductModal, #closeEditProductModalFooter').on('click', function () {
        $('#editProductModal').css('opacity', '0');
        setTimeout(() => {
            $('#editProductModal').hide();
        }, 300);
    });

    $('.delete-button').on('click', function () {
        var productId = $(this).data('id');
        var deleteUrl = `/api/product/delete/${productId}`;
        $('#deleteForm').attr('action', deleteUrl);
        $('#deleteModal').show();
        setTimeout(() => {
            $('#deleteModal').css('opacity', '1');
        }, 10);
    });

    $('#closeDeleteModal, #cancelDeleteModal').on('click', function () {
        $('#deleteModal').css('opacity', '0');
        setTimeout(() => {
            $('#deleteModal').hide();
        }, 300);
    });

    $('#deleteForm').on('submit', function (e) {
        e.preventDefault();
        var actionUrl = $(this).attr('action');
        deleteProduct(actionUrl);
    });

    function deleteProduct(url) {
        $('#deleteModal').hide();
        $("#loader").show();
    
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
            $("#loader").hide();
    
            if (data.success) {
                Swal.fire('Product Deleted!', data.message, 'success')
                .then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error!', 'Error deleting product: ' + data.error, 'error');
            }
        })
        .catch(error => {
            $("#loader").hide();
    
            console.error('Error:', error);
            Swal.fire('Network Error!', 'Could not delete product', 'error');
        });
    }    

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

    $('#createProductModal').on('hidden.bs.modal', function () {
        $('#createProductModal .modal-content').html('');
    });

    $('#editProductModal').on('hidden.bs.modal', function () {
        $('#editProductModal .modal-content').html('');
    });
});
