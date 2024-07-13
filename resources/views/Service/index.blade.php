@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Service Management</title>
  <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' />
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css' />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
  <script src="{{ asset('js/ServiceScript.js') }}" defer></script>
</head>

<body>
  <div class="container">
    <div class="row my-5">
      <div class="col-lg-12">
        <h2>Service Management</h2>
        <div class="card shadow">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="text-light">Service Management</h3>
            <div>
              
              <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#importExcelModal"><i class="bi-file-earmark-spreadsheet me-2"></i>Import Excel</button>
              <button class="btn btn-light" id="exportExcel"><i class="bi-file-earmark-spreadsheet me-2"></i>Export Excel</button>
              <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addServiceModal"><i class="bi-plus-circle me-2"></i>Add New Services</button>
            </div>
          </div>
          <div class="card-body" id="show_all_service">
            <h1 class="text-center text-secondary my-5">Loading...</h1>
          </div>
        </div>
      </div>
    </div>
  </div>

 <!-- Import Excel modal -->
<div class="modal fade" id="importExcelModal" tabindex="-1" aria-labelledby="importExcelLabel" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importExcelLabel">Import Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <form id="import_excel_form" enctype="multipart/form-data">
                    @csrf
                    <div class="my-2">
                        <label for="excel_file">Excel File</label>
                        <input type="file" name="excel_file" class="form-control" accept=".xlsx, .xls" required>
                        <div class="invalid-feedback" id="excel_file_error"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="import_excel_btn" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

  {{-- new service modal --}}
  <div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add New Services</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="#" method="POST" id="add_service_form" enctype="multipart/form-data">
          @csrf
          <div class="modal-body p-4 bg-light">
            <div class="row">
              <div class="col-lg">
                <label for="service_name">Service Name</label>
                <input type="text" name="service_name" class="form-control" placeholder="Service Name" required>
                <div class="invalid-feedback" id="service_name_error"></div>
              </div>
              <div class="col-lg">
                <label for="description">Description</label>
                <input type="text" name="description" class="form-control" placeholder="Description" required>
                <div class="invalid-feedback" id="description_error"></div>
              </div>
            </div>
            <div class="my-2">
              <label for="price">Price</label>
              <input type="text" name="price" class="form-control" placeholder="Price" required>
              <div class="invalid-feedback" id="price_error"></div>
            </div>
            <div class="my-2">
              <label for="service_image">Service Image </label>
              <input type="file" name="service_image" class="form-control" required>
              <div class="invalid-feedback" id="service_image_error"></div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" id="add_service_btn" class="btn btn-primary">Add Service</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- edit service modal --}}
  <div class="modal fade" id="editServiceModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Service</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="#" method="POST" id="edit_service_form" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="emp_id" id="emp_id">
          <input type="hidden" name="emp_service_image" id="emp_service_image">
          <div class="modal-body p-4 bg-light">
            <div class="row">
              <div class="col-lg">
                <label for="service_name">Service Name</label>
                <input type="text" name="service_name" id="service_name" class="form-control" placeholder="Service Name" required>
                <div class="invalid-feedback" id="service_name_error"></div>
              </div>
              <div class="col-lg">
                <label for="description">Description</label>
                <input type="text" name="description" id="description" class="form-control" placeholder="Description" required>
                <div class="invalid-feedback" id="description_error"></div>
              </div>
            </div>
            <div class="my-2">
              <label for="price">Price</label>
              <input type="text" name="price" id="price" class="form-control" placeholder="Price" required>
              <div class="invalid-feedback" id="price_error"></div>
            </div>
            <div class="my-2">
              <label for="service_image">Service Image</label>
              <input type="file" name="service_image" class="form-control">
              <div class="invalid-feedback" id="service_image_error"></div>
            </div>
            <div class="mt-2" id="service_image"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" id="edit_service_btn" class="btn btn-success">Update Service</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src='https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js'></script>
  <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js'></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
        var routeStore = "{{ route('store') }}";
        var routeEdit = "{{ route('edit') }}";
        var routeUpdate = "{{ route('update') }}";
        var routeDelete = "{{ route('delete') }}";
        var routeFetchAll = "{{ route('fetchAll') }}";
        var routeImport = "{{ route('import') }}";
        var routeExport = "{{ route('export') }}";
        var csrfToken = "{{ csrf_token() }}";
    </script>
  
  <!-- <script>
  $(function() {
  // Add new service ajax request
  $("#add_service_form").submit(function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    $("#add_service_btn").text('Adding...');
    
    // Clear previous error messages
    $(".invalid-feedback").hide();

    $.ajax({
      url: '{{ route('store') }}',
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
          url: '{{ route('edit') }}',
          method: 'get',
          data: {
            id: id,
            _token: '{{ csrf_token() }}'
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
          url: '{{ route('update') }}',
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
        let csrf = '{{ csrf_token() }}';
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
              url: '{{ route('delete') }}',
              method: 'delete',
              data: {
                id: id,
                _token: csrf
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

     // Import Excel file
    $("#importExcel").on('click', function() {
        // Create file input element
        const input = $('<input type="file" accept=".xlsx, .xls"/>');
        
        // Handle file change event
        input.on('change', function(event) {
            const file = event.target.files[0];
            const fd = new FormData();
            fd.append('excel_file', file); // Ensure 'excel_file' matches your form field name
            fd.append('_token', '{{ csrf_token() }}');

            // Perform AJAX request to import data
            $.ajax({
                url: '{{ route('import') }}',
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
            url: '{{ route('import') }}',
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

      // Existing fetchAllServices function
      fetchAllServices();

      function fetchAllServices() {
        $.ajax({
            url: '{{ route('fetchAll') }}',
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
});
  </script> -->
  
</body>
</html>
@endsection

