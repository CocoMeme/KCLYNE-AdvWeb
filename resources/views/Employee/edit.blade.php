@extends('layouts.app')

<head>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

@section('content')
    <div class="edit-employee-container">
        {!! Form::open(['route' => ['employee.update', $employee->id], 'enctype' => 'multipart/form-data', 'method' => 'PUT', 'class' => 'employee-form']) !!}

        @if ($employee->employee_image)
            <img src="{{ url('images/Employees/' . $employee->employee_image) }}" alt="Employee Image" width="100" class="employee-form-image">
        @else
            <p>No image uploaded</p>
        @endif

        {!! Form::file('employee_image', ['class' => 'form-control']) !!}

        {!! Form::label('first_name', 'First Name') !!}
        {!! Form::text('first_name', $employee->first_name, ['class' => 'form-control']) !!}
        
        {!! Form::label('last_name', 'Last Name') !!}
        {!! Form::text('last_name', $employee->last_name, ['class' => 'form-control']) !!}
        
        {!! Form::label('birth_date', 'Birth Date') !!}
        {!! Form::date('birth_date', $employee->birth_date, ['class' => 'form-control']) !!}
        
        {!! Form::label('sex', 'Sex') !!}
        {!! Form::select('sex', ['Male' => 'Male', 'Female' => 'Female'], $employee->sex, ['class' => 'form-control']) !!}
        
        {!! Form::label('phone', 'Phone') !!}
        {!! Form::text('phone', $employee->phone, ['class' => 'form-control']) !!}
        
        {!! Form::label('house_no', 'House No') !!}
        {!! Form::text('house_no', $employee->house_no, ['class' => 'form-control']) !!}
        
        {!! Form::label('street', 'Street') !!}
        {!! Form::text('street', $employee->street, ['class' => 'form-control']) !!}
        
        {!! Form::label('baranggay', 'Baranggay') !!}
        {!! Form::text('baranggay', $employee->baranggay, ['class' => 'form-control']) !!}
        
        {!! Form::label('city', 'City') !!}
        {!! Form::text('city', $employee->city, ['class' => 'form-control']) !!}
        
        {!! Form::label('province', 'Province') !!}
        {!! Form::text('province', $employee->province, ['class' => 'form-control']) !!}
        
        {!! Form::label('position', 'Position') !!}
        {!! Form::text('position', $employee->position, ['class' => 'form-control']) !!}
        
        {!! Form::label('payrate_per_hour', 'Pay Rate Per Hour') !!}
        {!! Form::text('payrate_per_hour', $employee->payrate_per_hour, ['class' => 'form-control']) !!}

        {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
        <a class="btn btn-secondary" href="{{ route('employee.index') }}" role="button">Cancel</a>
        
        {!! Form::close() !!}
    </div>
@endsection
