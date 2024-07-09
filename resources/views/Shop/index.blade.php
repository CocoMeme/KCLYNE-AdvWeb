@extends('layouts.app')

@section('content')
    <section class="product-shop">
        {{-- PRODUCT DISPLAY --}}
        <div class="display-product">
            <div class="products">
                @foreach($products as $product)
                    <a href="{{ route('shopInfo', ['id' => $product->id]) }}">
                        <div class="row">
                            @php
                                $imagesExist = false;
                                $images = explode(',', $product->image_path);
                            @endphp
                            
                            @if(count($images) > 0)
                                @foreach ($images as $image)
                                    @if(file_exists(public_path('images/Products/' . $image)))
                                        <img src="{{ asset('images/Products/' . $image) }}" alt="Product Image" width="100px">
                                        @php $imagesExist = true; @endphp
                                        @break
                                    @endif
                                @endforeach
                            @endif
                            
                            @if(!$imagesExist)
                                <img src="{{ asset('images/Products/defaultproduct.jpg') }}" alt="No Product Image" width="100px">
                            @endif

                            <h3>{{ $product->name }}</h3>

                            <div class="product-info">
                                <div class="form-group">
                                    <label name="Stat" for="">SRP:</label><p>₱ {{ number_format($product->price, 2) }}</p>
                                </div>

                                <div class="form-group">
                                    <label name="Stat" for="">Stocks:</label><p>{{ $product->stock_quantity }}</p>
                                </div>

                                <p name="description">{{ $product->description }}</p>
                            </div>

                            <div class="shop-button">

                            </div>

                        </div>
                    </a>    
                @endforeach
            </div>
        </div>
    </section>
@endsection
