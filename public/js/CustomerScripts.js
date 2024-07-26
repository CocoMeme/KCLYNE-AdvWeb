$(document).ready(function() {
    // Initialize DataTable
    $('#ctable').DataTable();

    // Function to handle status change
    $('.status-select').on('change', function() {
        var customerId = $(this).data('id');
        var newStatus = $(this).val();
        $('#confirmStatusChangeModal').data('customer-id', customerId);
        $('#confirmStatusChangeModal').data('new-status', newStatus);
        $('#confirmStatusChangeModal').modal('show');
    });

    // Function to handle status update confirmation
    $('#confirmStatusChangeButton').on('click', function() {
        var customerId = $('#confirmStatusChangeModal').data('customer-id');
        var newStatus = $('#confirmStatusChangeModal').data('new-status');
        
        $.ajax({
            url: `/api/customer/status/${customerId}`,
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify({
                status: newStatus,
                _token: $('meta[name="csrf-token"]').attr('content')
            }),
            success: function(response) {
                alert('Customer status updated successfully');
                location.reload();
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });

    // Close modal without making any changes
    $('#cancelStatusChangeButton').on('click', function() {
        $('#confirmStatusChangeModal').modal('hide');
        location.reload();
    });

    // Open Import Modal
    $('#openImportCustomerModal').on('click', function() {
        $('#importCustomerModal').modal('show');
    });

    // Open Export Form
    $('#exportCustomerForm').on('submit', function(e) {
        e.preventDefault();
        $(this).submit();
    });

    // Close Import Modal
    $('#cancelImportCustomerModal').on('click', function() {
        $('#importCustomerModal').modal('hide');
    });
});
