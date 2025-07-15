@extends('adminlte::page')

@section('content_header')
    @yield('content_header')
@endsection

@section('content')
    @yield('content')
@endsection

@section('sidebar')
    @include('layouts.partials.sidebar')
@endsection

@section('navbar')
    @include('layouts.partials.navbar')
@endsection
