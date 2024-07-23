@extends('layouts.app')
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
@section('content')
<head>
    <title>Services</title>
    <meta charset="utf-8">
</head>
<body>
<div class="container">
    <h2>Services</h2>
    <div id="services-container">
        @foreach($services as $service)
            <div class="card w-75 post mb-3">
                <div class="card-body">
                    <h5 class="card-title-name">{{ $service->service_name }}</h5>
                    <p class="card-text-price">₱{{ $service->price }}</p>
                    <p class="card-text-description">{{ $service->description }}</p>
                    <img src="{{ asset('images/Services/' . $service->service_image) }}" class="card-img-bottom" alt="{{ $service->service_name }}">
                    <div class="rating-and-comments">
                        <span class="star-rating-service" data-service-id="{{ $service->id }}">
                            @php
                                $averageRating = $service->reviews->avg('rating') ?: 0;
                                $filledStar = $service->reviews->count() > 0 ? '★' : '☆';
                            @endphp
                            {!! $filledStar !!}
                            <span class="number-rating">{{ number_format($averageRating, 1) }}</span>
                        </span>
                        <span class="comment-icon" data-service-id="{{ $service->id }}">
                            <i class="fa-regular fa-comment"></i>
                            <span class="comment-number">{{ $service->reviews->count() }}</span>
                        </span>
                    </div>
                    <div class="comments-container" id="comments-container-{{ $service->id }}"></div>
                </div>
            </div>
        @endforeach
    </div>
    <input type="hidden" id="start" value="{{ count($services) }}">
    <input type="hidden" id="rowperpage" value="{{ $rowperpage }}">
    <input type="hidden" id="totalrecords" value="{{ $totalrecords }}">
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{ asset('js/ServiceIndexScript.js') }}"></script>

@endsection
