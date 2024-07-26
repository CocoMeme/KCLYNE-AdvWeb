<!-- Customer Management Page -->
@extends('layouts.app')

@section('content')
<div class="admin-container">
    <div class="right-panel">
        <div id="customers">
            <div class="table-methods">
                <a class="btn btn-info btn-primary" href="#" role="button" id="openImportCustomerModal">
                    <i class='bx bxs-file-import'></i> Import Excel File
                </a>
                <form id="exportCustomerForm" method="POST" action="{{ route('customers.export') }}">
                    @csrf
                    <button type="submit" class="btn btn-info btn-primary">
                        <i class='bx bxs-file-export'></i> Export Excel File
                    </button>
                </form>
            </div>
            <div class="table-responsive">
                <table id="ctable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Image</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Address</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>{{ $customer->user_id }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>
                                <img src="{{ url('images/Customers/'.$customer->image) }}" alt="Customer image" width="50" height="50" class="img-thumbnail">
                            </td>
                            <td>{{ $customer->phone }}</td>
                            <td>
                                <select class="form-control status-select" data-id="{{ $customer->id }}">
                                    <option value="Actived" {{ $customer->status == 'Actived' ? 'selected' : '' }}>Actived</option>
                                    <option value="Deactivated" {{ $customer->status == 'Deactivated' ? 'selected' : '' }}>Deactivated</option>
                                </select>
                            </td>
                            <td>{{ $customer->address }}</td>
                            <td>{{ $customer->created_at }}</td>
                            <td>{{ $customer->updated_at }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Status Change Confirmation Modal -->
        <div class="modal fade" id="confirmStatusChangeModal" tabindex="-1" role="dialog" aria-labelledby="confirmStatusChangeModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmStatusChangeModalLabel">Confirm Status Change</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to change the status of this customer?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="cancelStatusChangeButton" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="confirmStatusChangeButton">Confirm</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Import Customer Modal -->
        <div class="modal fade" id="importCustomerModal" tabindex="-1" role="dialog" aria-labelledby="importCustomerModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importCustomerModalLabel">Import Customers</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" enctype="multipart/form-data" action="{{ route('customers.import') }}">
                            @csrf
                            <div class="form-group">
                                <label for="uploadName">Choose Excel File</label>
                                <input type="file" id="uploadName" name="customer_upload" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Import</button>
                            <button type="button" class="btn btn-secondary" id="cancelImportCustomerModal">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/CustomerScripts.js') }}"></script>
@endsection
