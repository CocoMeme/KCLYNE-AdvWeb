$(document).ready(function() {
    $('#etable').DataTable();

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

    $('#etable tbody').on('click', 'tr', function () {
        $('#etable tbody tr').css('background-color', '');
        $(this).css('background-color', '#EE6F57');
        var imageSrc = $(this).data('employee-image');
        var name = $(this).find('td').eq(2).text() + " " + $(this).find('td').eq(3).text();
        var position = $(this).data('employee-position');
        var imageUrl = imageSrc ? '/images/Employees/' + imageSrc : '/images/defaultemployee.png';

        $('#employeeImage').attr('src', imageUrl)
            .on('error', function() {
                $(this).attr('src', '/images/defaultemployee.png');
            });
        $('#employeeName').text(name);
        $('#employeePosition').text(position);
    });

    $('.status-button').on('click', function() {
        var employeeId = $(this).data('id');
        var currentStatus = $(this).data('status');
        var newStatus = currentStatus === 'Verified' ? 'Pending' : 'Pending';

        $.ajax({
            url: `http://127.0.0.1:8000/api/employee/status/${employeeId}`,
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: JSON.stringify({ status: newStatus }),
            success: function(response) {
                alert('Employee status updated successfully');
                location.reload();
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });

    $('.delete-button').on('click', function() {
        var employeeId = $(this).data('id');
        var deleteUrl = `/api/employee/delete/${employeeId}`;
        $('#deleteForm').attr('action', deleteUrl);
        $('#deleteModal').modal('show');
    });

    $('#deleteForm').on('submit', function(e) {
        e.preventDefault();
        var actionUrl = $(this).attr('action');
        deleteEmployee(actionUrl);
    });

    function deleteEmployee(url) {
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
                alert('Employee deleted successfully');
                location.reload();
            } else {
                alert('Error deleting employee: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error: Could not delete employee');
        });
    }
    
});
