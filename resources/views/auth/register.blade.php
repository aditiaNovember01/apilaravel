@extends('adminlte::auth.register')

@section('auth_header', 'Register')

@section('auth_body')
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="input-group mb-3">
            <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" required
                value="{{ old('name') }}">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email" required
                value="{{ old('email') }}">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password"
                required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>
        <div class="input-group mb-3">
            <input type="text" name="phone" class="form-control" placeholder="No HP" value="{{ old('phone') }}">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-phone"></span>
                </div>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <button type="submit" class="btn btn-primary btn-block">Register</button>
    </form>
@endsection
