@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

            {!! Form::open(['route' => 'product.store', 'enctype' => 'multipart/form-data', 'method' => 'POST']) !!}
            
            <div class="form-group">
                {!! Form::label('name', 'Product Name') !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
            </div>
            
            <div class="form-group">
                {!! Form::label('description', 'Product Description') !!}
                {!! Form::text('description', null, ['class' => 'form-control']) !!}
            </div>
            
            <div class="form-group">
                {!! Form::label('price', 'Price') !!}
                {!! Form::text('price', null, ['class' => 'form-control', 'required']) !!}
            </div>
            
            <div class="form-group">
                {!! Form::label('stock_quantity', 'Stock Quantity') !!}
                {!! Form::text('stock_quantity', null, ['class' => 'form-control', 'required']) !!}
            </div>
            
            <div class="form-group">
                {!! Form::label('images', 'Upload Images') !!}
                {!! Form::file('images[]', ['class' => 'form-control', 'multiple' => true]) !!}
            </div>            
            
            {!! Form::submit('Create Product', ['class' => 'btn btn-primary']) !!}
            {!! Form::close() !!}
        </div>
@endsection
