@extends('layouts.app')

@section('content')
    <section class="main-log">
        <div class="container">
            <h2>LOGIN</h2>
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
            <p>Don't have an account? <a href="{{ route('register') }}">Register</a> here</p>
        </div>
    </section>
@endsection
