$(function() {
    $("#add_service_form").submit(function(e) {
        e.preventDefault();
        const fd = new FormData(this);
        $("#add_service_btn").text('Adding...');
        
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

    $("#importExcel").on('click', function() {
        const input = $('<input type="file" accept=".xlsx, .xls"/>');
        
        input.on('change', function(event) {
            const file = event.target.files[0];
            const fd = new FormData();
            fd.append('excel_file', file);
            fd.append('_token', csrfToken);

            $.ajax({
                url: routeImport,
                method: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status === 200) {
                        Swal.fire('Imported!', 'Services Imported Successfully!', 'success');
                        fetchAllServices();
                        $("#importExcelModal").modal('hide');
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

        input.trigger('click');
    });

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
                    fetchAllServices();
                    $("#import_excel_form")[0].reset();
                    $("#importExcelModal").modal('hide');
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

    $("#exportExcel").on('click', function() {
        $.ajax({
            url: routeExport,
            method: 'get',
            xhrFields: {
                responseType: 'blob'
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
