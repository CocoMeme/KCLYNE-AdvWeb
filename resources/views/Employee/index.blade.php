@extends('layouts.app')

<head>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

@section('content')

<div class="admin-container">

    <div class="left-panel">
        <h3 align="center">Employee Details</h3>
        <img id="employeeImage" src="Images\Layouts\Logo White.png" alt="Employee Image" class="employee-image">
        <br>
        <br>
        <p id="employeeName" align="center">Name</p>
        <p id="employeePosition" align="center">Position</p>
    </div>

    <div class="right-panel">
        <div id="employees">

            <div class="table-methods">

                <a class="btn btn-primary" href="{{ route('employee.create') }}" role="button"><i class='bx bx-user-plus'></i>Add Employee</a>
        
                <form method="POST" enctype="multipart/form-data" action="{{ route('employee.import') }}">
                @csrf
                <button type="button" id="upload-button" class="btn btn-info btn-primary">
                    <i class='bx bxs-file-import'></i> Import Excel
                </button>
                <input type="file" id="uploadName" name="employee_upload" required style="display: none;">
                <button type="submit" id="submit-button" style="display: none;"></button>
            </form>

                <form method="POST" action="{{ route('employee.export') }}">
                    @csrf
                    <button type="submit" class="btn btn-info btn-primary"><i class='bx bxs-file-export'></i>Export Excel</button>
                </form>

            </div>
        
            <div class="table-responsive">
                <table id="etable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Status</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Birth Date</th>
                            <th>Sex</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Payrate Per Hour</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <tr data-employee-image="{{ $employee->employee_image }}" data-employee-position="{{ $employee->position }}">
                                <td>{{ $employee->id }}</td>
                                <td class="{{ $employee->status == 'Verified' ? 'status-verified' : 'status-pending' }}">{{ $employee->status }}</td>
                                <td>{{ $employee->first_name }}</td>
                                <td>{{ $employee->last_name }}</td>
                                <td>{{ $employee->birth_date }}</td>
                                <td>{{ $employee->sex }}</td>
                                <td>{{ $employee->phone }}</td>
                                <td>{{ $employee->house_no }} {{ $employee->street }} {{ $employee->baranggay }} {{ $employee->city }} {{ $employee->province }}</td>
                                <td>{{ $employee->payrate_per_hour }}</td>
                                <td>
                                <div class="dropdown">
                                        <button class="btn btn-secondary btn-sm action-button" data-toggle="dropdown">
                                            ...
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item edit-button" href="{{ route('employee.edit', $employee->id) }}"><i class="fas fa-edit"></i> Edit</a>
                                            <button class="dropdown-item delete-button" data-id="{{ $employee->id }}"><i class="fa-solid fa-trash"></i> Delete</button>
                                            <button class="dropdown-item status-button" data-id="{{ $employee->id }}" data-status="{{ $employee->status }}">
                                            @if ($employee->status == 'Verified')
                                            <i class="fas fa-clock"></i> Pend
                                            @else
                                            <i class="fas fa-check-circle"></i> Verify
                                            @endif
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete the selected employee?
                    </div>
                    <div class="modal-footer">
                        <form id="deleteForm" method="POST">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-primary">Yes</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<div id="loader" style="display: none;">Loading...</div>
<script src="{{ asset('js/EmployeeScripts.js') }}"></script>

@endsection
