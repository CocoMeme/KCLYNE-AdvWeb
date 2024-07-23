@extends('layouts.app')

@section('content')
<div class="order-history-container">
    <div class="tabs">
        <button class="tab-button" data-tab="products">Products</button>
        <button class="tab-button" data-tab="services">Services</button>
    </div>
    <div class="tab-content" id="products-tab"></div>
    <div class="tab-content" id="services-tab" style="display: none;"></div>
</div>
<script src="{{ asset('js/OrderHistoryScript.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
