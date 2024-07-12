$(document).ready(function() {
    $('#openCreateProductModal').click(function(e) {
        e.preventDefault();

        $.ajax({
            url: openCreateProductModalUrl,
            type: 'GET',
            success: function(response) {
                $('#createProductModal .modal-body').html(response);
                $('#createProductModal').modal('show');
            },
            error: function(xhr) {
                console.error("Error loading modal content: " + xhr.status + " " + xhr.statusText);
            }
        });
    });
});
