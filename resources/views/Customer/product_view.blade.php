@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Item View</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.ico') }}" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="{{ asset('css/product_view.css') }}" rel="stylesheet" />
</head>
<body>
    <!-- Product section-->
    <section class="py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="row gx-4 gx-lg-5 align-items-center">
                <div class="col-md-6">
                    <img class="card-img-top mb-5 mb-md-0" src="{{ asset('images/' . ($isService ? 'services/' . $item->service_image : 'products/' . $firstImagePath)) }}" alt="{{ $isService ? $item->service_name : $item->name }}" />
                </div>
                <div class="col-md-6">
                    <div class="small mb-1">{{ $isService ? 'Service' : 'Product' }}:</div>
                    <h1 class="display-5 fw-bolder">{{ $isService ? $item->service_name : $item->name }}</h1>
                    <div class="fs-5 mb-5">
                        <span>₱{{ $isService ? $item->price : $item->price }}</span>
                    </div>
                    <p class="lead">{{ $isService ? $item->description : $item->description }}</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Related items section-->
    <section class="py-5 bg-light">
        <div class="container px-4 px-lg-5 mt-5">
            <h2 class="fw-bolder mb-4">Related {{ $isService ? 'Services' : 'Products' }}</h2>
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                @foreach($relatedItems as $relatedItem)
                    <div class="col mb-5">
                        <div class="card h-100">
                            <!-- Product image-->
                            <img class="card-img-recommend" src="{{ asset('images/' . ($isService ? 'services/' . $relatedItem->service_image : 'products/' . explode(',', $relatedItem->image_path)[0])) }}" alt="{{ $isService ? $relatedItem->service_name : $relatedItem->name }}" />
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- Product name-->
                                    <h5 class="fw-bolder">{{ $isService ? $relatedItem->service_name : $relatedItem->name }}</h5>
                                    <!-- Product price-->
                                    ₱{{ $isService ? $relatedItem->price : $relatedItem->price }}
                                </div>
                            </div>
                            <!-- Product actions-->
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center">
                                <a class="btn btn-outline-dark mt-auto" href="{{ $isService ? route('service.show', $relatedItem->id) : url('/product_view/' . $relatedItem->id) }}">View {{ $isService ? 'Service' : 'Product' }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
@endsection
