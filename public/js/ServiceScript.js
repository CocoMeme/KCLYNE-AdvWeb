$(function() {
    // Add new service ajax request
    $("#add_service_form").submit(function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        $("#add_service_btn").text('Adding...');
        
        // Clear previous error messages
        $(".invalid-feedback").hide();

        $.ajax({
            url: routeStore,
            method: 'post',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.status === 200) {
                    Swal.fire('Added!', response.message, 'success');
                    fetchAllServices();
                    $("#add_service_form")[0].reset();
                    $("#addServiceModal").modal('hide');
                }
                $("#add_service_btn").text('Add Service');
            },
            error: function(response) {
                $("#add_service_btn").text('Add Service');
                if (response.responseJSON && response.responseJSON.errors) {
                    $.each(response.responseJSON.errors, function(key, value) {
                        $(`#${key}_error`).text(value[0]).show();
                    });
                }
            }
        });
    });

    // edit service ajax request
    $(document).on('click', '.editIcon', function(e) {
        e.preventDefault();
        let id = $(this).attr('id');
        $.ajax({
            url: routeEdit,
            method: 'get',
            data: {
                id: id,
                _token: csrfToken
            },
            success: function(response) {
                $("#service_name").val(response.service_name);
                $("#description").val(response.description);
                $("#price").val(response.price);
                $("#service_image").html(`<img src="/images/Services/${response.service_image}" width="100" class="img-fluid img-thumbnail">`);
                $("#emp_id").val(response.id);
                $("#emp_service_image").val(response.service_image);
            }
        });
    });

    // update service ajax request
    $("#edit_service_form").submit(function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        $("#edit_service_btn").text('Updating...');
        $.ajax({
            url: routeUpdate,
            method: 'post',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.status == 200) {
                    Swal.fire('Updated!', 'Service Updated Successfully!', 'success');
                    fetchAllServices();
                    $("#edit_service_form")[0].reset();
                    $("#editServiceModal").modal('hide');
                }
                $("#edit_service_btn").text('Update Service');
            },
            error: function(response) {
                $("#edit_service_btn").text('Update Service');
                $.each(response.responseJSON.errors, function(key, value) {
                    $(`#${key}_error`).text(value[0]).show();
                });
            }
        });
    });

    // delete service ajax request
    $(document).on('click', '.deleteIcon', function(e) {
        e.preventDefault();
        let id = $(this).attr('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: routeDelete,
                    method: 'delete',
                    data: {
                        id: id,
                        _token: csrfToken
                    },
                    success: function(response) {
                        console.log(response);
                        Swal.fire('Deleted!', 'Your file has been deleted.', 'success');
                        fetchAllServices();
                    }
                });
            }
        });
    });

    // fetch all services ajax request
    fetchAllServices();

    function fetchAllServices() {
        $.ajax({
            url: routeFetchAll,
            method: 'get',
            success: function(response) {
                $("#show_all_service").html(response);
                $("table").DataTable({
                    order: [0, 'desc']
                });
            },
            error: function(xhr, status, error) {
                console.error('Failed to fetch services:', error);
            }
        });
    }

    // Import Excel file
    $("#importExcel").on('click', function() {
        // Create file input element
        const input = $('<input type="file" accept=".xlsx, .xls"/>');
        
        // Handle file change event
        input.on('change', function(event) {
            const file = event.target.files[0];
            const fd = new FormData();
            fd.append('excel_file', file); // Ensure 'excel_file' matches your form field name
            fd.append('_token', csrfToken);

            // Perform AJAX request to import data
            $.ajax({
                url: routeImport,
                method: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status === 200) {
                        Swal.fire('Imported!', 'Services Imported Successfully!', 'success');
                        fetchAllServices(); // Refresh service data
                        $("#importExcelModal").modal('hide'); // Hide modal if necessary
                    } else {
                        Swal.fire('Error!', 'Failed to import services.', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error!', 'Failed to import services. Please try again.', 'error');
                    console.error(xhr.responseText);
                }
            });
        });

        // Trigger click event on the file input
        input.trigger('click');
    });

    // Handle form submission for importing Excel
    $("#import_excel_form").submit(function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        $("#import_excel_btn").text('Importing...');

        $.ajax({
            url: routeImport,
            method: 'post',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status === 200) {
                    Swal.fire('Imported!', 'Services Imported Successfully!', 'success');
                    fetchAllServices(); // Refresh service data
                    $("#import_excel_form")[0].reset(); // Clear form inputs
                    $("#importExcelModal").modal('hide'); // Hide modal
                } else {
                    Swal.fire('Error!', 'Failed to import services. Please try again.', 'error');
                }
                $("#import_excel_btn").text('Import');
            },
            error: function(xhr, status, error) {
                Swal.fire('Error!', 'Failed to import services. Please try again.', 'error');
                console.error('Import error:', error);
                $("#import_excel_btn").text('Import');
            }
        });
    });

    // Export Excel file
    $("#exportExcel").on('click', function() {
        $.ajax({
            url: routeExport,
            method: 'get',
            xhrFields: {
                responseType: 'blob' // Important for binary data
            },
            success: function(response) {
                const blob = new Blob([response], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'ServiceExport.xlsx';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            },
            error: function(xhr, status, error) {
                console.error('Export failed:', error);
            }
        });
    });
});
