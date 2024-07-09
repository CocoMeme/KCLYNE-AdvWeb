@extends('layouts.master')

@section('content')
    <div class="container">
        {!! Form::open(['route' => ['product.update', $product->id], 'enctype' => 'multipart/form-data', 'method' => 'PUT']) !!}

        @if ($product->product_image)
            <img src="{{ url('images/Products/' . $product->product_image) }}" alt="Product Image" width="100">
        @else
            <p>No image uploaded</p>
        @endif

        {!! Form::file('product_image', ['class' => 'form-control']) !!}

        {!! Form::label('name', 'Product Name') !!}
        {!! Form::text('name', $product->name, ['class' => 'form-control']) !!}
        
        {!! Form::label('description', 'Description') !!}
        {!! Form::textarea('description', $product->description, ['class' => 'form-control']) !!}
        
        {!! Form::label('price', 'Price') !!}
        {!! Form::number('price', $product->price, ['class' => 'form-control', 'step' => '0.01']) !!}
        
        {!! Form::label('stock_quantity', 'Stock Quantity') !!}
        {!! Form::number('stock_quantity', $product->stock_quantity, ['class' => 'form-control']) !!}

        {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
        <a class="btn btn-secondary" href="{{ route('product.index') }}" role="button">Cancel</a>
        
        {!! Form::close() !!}
    </div>
@endsection
