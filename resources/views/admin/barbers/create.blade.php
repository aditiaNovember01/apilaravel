@extends('adminlte::page')

@section('title', 'Tambah Barber')

@section('content_header')
    <h1>Tambah Barber</h1>
@endsection

@section('content')
    <form action="{{ route('admin.barbers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
        </div>
        <div class="form-group">
            <label>Foto</label>
            <input type="file" name="photo" class="form-control" accept="image/*">
        </div>
        <div class="form-group">
            <label>Keahlian</label>
            <input type="text" name="specialty" class="form-control" required value="{{ old('specialty') }}">
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.barbers.index') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection
