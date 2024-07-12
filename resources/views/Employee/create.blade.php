@extends('layouts.app')

@section('content')
    <div class="admin-container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {!! Form::open(['route' => 'employee.store', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {!! Form::label('first_name', 'First Name') !!}
            {!! Form::text('first_name', old('first_name'), ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('last_name', 'Last Name') !!}
            {!! Form::text('last_name', old('last_name'), ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('birth_date', 'Birth Date') !!}
            {!! Form::date('birth_date', old('birth_date'), ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('sex', 'Sex') !!}
            {!! Form::select('sex', ['Male' => 'Male', 'Female' => 'Female'], old('sex'), ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('phone', 'Phone') !!}
            {!! Form::text('phone', old('phone'), ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('house_no', 'House No') !!}
            {!! Form::text('house_no', old('house_no'), ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('street', 'Street') !!}
            {!! Form::text('street', old('street'), ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('baranggay', 'Baranggay') !!}
            {!! Form::text('baranggay', old('baranggay'), ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('city', 'City') !!}
            {!! Form::text('city', old('city'), ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('province', 'Province') !!}
            {!! Form::text('province', old('province'), ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('position', 'Position') !!}
            {!! Form::text('position', old('position'), ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('payrate_per_hour', 'Payrate Per Hour') !!}
            {!! Form::number('payrate_per_hour', old('payrate_per_hour'), ['class' => 'form-control', 'step' => '0.01']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('employee_image', 'Upload Image') !!}
            {!! Form::file('employee_image', ['class' => 'form-control']) !!}
        </div>
        @error('employee_image')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}
    </div>
@endsection
