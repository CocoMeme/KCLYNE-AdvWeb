@extends('layouts.app')

@section('content')
<div class="container" style="margin-top: 100px">
    <h2>Login</h2>
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" required value="{{ old('username') }}">
            @error('username')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
</div>
@endsection
