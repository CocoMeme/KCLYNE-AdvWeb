$(document).ready(function() {
    $('#ctable').DataTable();

    $('.status-select').on('change', function() {
        var customerId = $(this).data('id');
        var newStatus = $(this).val();
        
        $('#confirmStatusChangeModal').data('customer-id', customerId);
        $('#confirmStatusChangeModal').data('new-status', newStatus);
        
        $('#confirmStatusChangeModal').modal('show');
    });

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
                $("#loader").hide();
    
                Swal.fire({
                    title: 'Customer Status Updated!',
                    text: response.message,
                    icon: 'success'
                }).then(() => {
                    location.reload();
                });
            },
            error: function(error) {
                console.error('Error:', error);
                alert('Failed to update customer status: ' + error.responseJSON.message);
            }
        });
    });

    $('#cancelStatusChangeButton').on('click', function() {
        $('#confirmStatusChangeModal').modal('hide');
    });

    $('#openImportCustomerModal').on('click', function() {
        $('#importCustomerModal').modal('show');
    });

    $('#exportCustomerForm').on('submit', function(e) {
        e.preventDefault();
        $(this).submit();
    });

    $('#cancelImportCustomerModal').on('click', function() {
        $('#importCustomerModal').modal('hide');
    });
});
