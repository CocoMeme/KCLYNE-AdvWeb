@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="ty-message text-center">
            <h1>Thank you for purchasing KCLYNE items!</h1>
            <p>Your order has been successfully placed.</p>
            
            <div class="mt-4">
                <a href="{{ route('shop') }}" class="btn continue-shopping">Continue Shopping >></a>
            </div>
        </div>
    </div>
@endsection
