@extends('layouts.app')

@section('title', 'Login')

@section('content')
<section class="main-login-failed">
    <div class="main-text">
        <h5 style="font-size: 20px">Warning:</h5>
        <h1 style="color: #EE6F57">Your Account<br></h1>
        <h1>Is Deactivated</h1>
        <p>For more information please contact of e-mail at <strong style="font-style: normal">kclyne@gmail.com</strong></p>
        <a href="/register" class="main-btn">Register Now! <i class='bx bxs-chevron-right'></i></a>
    </div>
</section>
@endsection
