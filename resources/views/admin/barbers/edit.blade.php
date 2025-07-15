@extends('adminlte::page')

@section('title', 'Edit Barber')

@section('content_header')
    <h1>Edit Barber</h1>
@endsection

@section('content')
    <form action="{{ route('admin.barbers.update', $barber) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $barber->name) }}">
        </div>
        <div class="form-group">
            <label>Foto</label>
            <input type="file" name="photo" class="form-control" accept="image/*">
            @if ($barber->photo)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $barber->photo) }}" alt="foto" width="100">
                </div>
            @endif
        </div>
        <div class="form-group">
            <label>Keahlian</label>
            <input type="text" name="specialty" class="form-control" required
                value="{{ old('specialty', $barber->specialty) }}">
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="active" {{ $barber->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $barber->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.barbers.index') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection
