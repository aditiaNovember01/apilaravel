@extends('adminlte::page')

@section('title', 'Profile')

@section('content_header')
    <h1>Profile</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h4>Nama: {{ $user->name }}</h4>
            <h5>Email: {{ $user->email }}</h5>
            <h5>No HP: {{ $user->phone }}</h5>
        </div>
    </div>
@endsection
