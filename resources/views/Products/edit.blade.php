@extends('layouts.app')

@section('content')
    <div class="admin-container">
        {!! Form::open(['route' => ['product.update', $product->id], 'enctype' => 'multipart/form-data', 'method' => 'PUT']) !!}

        @if ($product->image_path)
            @php
                $images = explode(',', $product->image_path);
            @endphp
            @foreach ($images as $image)
                <img src="{{ url('images/Products/' . $image) }}" alt="Product Image" width="100">
            @endforeach
        @else
            <p>No image uploaded</p>
        @endif

        {!! Form::file('images[]', ['class' => 'form-control', 'multiple' => 'multiple']) !!}

        {!! Form::label('name', 'Product Name') !!}
        {!! Form::text('name', $product->name, ['class' => 'form-control']) !!}
        
        {!! Form::label('description', 'Description') !!}
        {!! Form::textarea('description', $product->description, ['class' => 'form-control']) !!}
        
        {!! Form::label('price', 'Price') !!}
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">₱</span>
            </div>
            {!! Form::number('price', $product->price, ['class' => 'form-control', 'step' => '0.01']) !!}
        </div>

        {!! Form::label('stock_quantity', 'Stock Quantity') !!}
        {!! Form::number('stock_quantity', $product->stock_quantity, ['class' => 'form-control']) !!}

        {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
        <a class="btn btn-secondary" href="{{ route('product.index') }}" role="button">Cancel</a>
        
        {!! Form::close() !!}
    </div>
@endsection
