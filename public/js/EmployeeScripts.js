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
        var newStatus = currentStatus === 'Verified' ? 'Pending' : 'Verified';
    
        $("#loader").show();
    
        $.ajax({
            url: `/api/employee/status/${employeeId}`,
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
                    title: 'Employee Status Updated!',
                    text: response.message,
                    icon: 'success'
                }).then(() => {
                    location.reload();
                });
            },
            error: function(error) {
                $("#loader").hide();
    
                console.error('Error:', error);
    
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to update employee status. Please try again.',
                    icon: 'error'
                });
            }
        });
    });    

    $('.delete-button').on('click', function() {
        var employeeId = $(this).data('id');
        var deleteUrl = `/api/delete_employee/${employeeId}`;
        $('#deleteForm').attr('action', deleteUrl);
        $('#deleteModal').modal('show');
    });

    $('#deleteForm').on('submit', function(e) {
        e.preventDefault();
        var actionUrl = $(this).attr('action');
        deleteEmployee(actionUrl);
    });

    function deleteEmployee(url) {
        $('#deleteModal').modal('hide');
        $("#loader").show();
    
        fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                _token: $('meta[name="csrf-token"]').attr('content')
            })
        })
        .then(response => {
            $("#loader").hide();
    
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Deleted!',
                    text: 'Employee deleted successfully.',
                    icon: 'success'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error deleting employee: ' + data.error,
                    icon: 'error'
                });
            }
        })
        .catch(error => {
            $("#loader").hide();
            console.error('Error:', error);
            Swal.fire({
                title: 'Network Error!',
                text: 'Could not delete employee.',
                icon: 'error'
            });
        });
    }    
});

document.addEventListener('DOMContentLoaded', function() {
    var uploadButton = document.getElementById('upload-button');
    var fileInput = document.getElementById('uploadName');
    var submitButton = document.getElementById('submit-button');
    
    uploadButton.addEventListener('click', function() {
        fileInput.click();
    });

    fileInput.addEventListener('change', function() {
        if (fileInput.files.length > 0) {
            submitButton.click();
        }
    });
});

