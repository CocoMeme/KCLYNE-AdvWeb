@extends('layouts.app')
@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' />
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css' />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
  <script src="{{ asset('js/ServiceScript.js') }}" defer></script>
</head>

<style>
        div:where(.swal2-container) button:where(.swal2-styled):where(.swal2-confirm) {
        border: 0;
        border-radius: .25em;
        background: initial;
        background-color: #00334e;
        color: #fff;
        font-size: 1em;
    }
</style>

<body>
  <div class="service-container">
    <div class="row service-row">
    <h2 style="text-align: left">Service Management</h2>
        <div class="card shadow">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="text-light">Service Management</h3>
            <div>
              <button class="btn btn-light custom-btn" data-bs-toggle="modal" data-bs-target="#importExcelModal">
                <i class="bi-file-earmark-spreadsheet me-2"></i>Import Excel
              </button>
              <button class="btn btn-light custom-btn" id="exportExcel">
                <i class="bi-file-earmark-spreadsheet me-2"></i>Export Excel
              </button>
              <button class="btn btn-light custom-btn" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                <i class="bi-plus-circle me-2"></i>Add New Services
              </button>
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
  <div class="modal fade custom-modal" id="importExcelModal" tabindex="-1" aria-labelledby="importExcelLabel" data-bs-backdrop="static" aria-hidden="true">
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

  <!-- Add New Service modal -->
  <div class="modal fade custom-modal" id="addServiceModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" aria-hidden="true">
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
              <label for="service_image">Service Image</label>
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

  <!-- Edit Service modal -->
  <div class="modal fade custom-modal" id="editServiceModal" tabindex="-1" aria-labelledby="exampleModalLabel" data-bs-backdrop="static" aria-hidden="true">
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
  
</body>
</html>
@endsection
