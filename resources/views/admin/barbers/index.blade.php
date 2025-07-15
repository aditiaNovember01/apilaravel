@extends('layouts.app')

@section('content_header')
    <h1>Data Barbers</h1>
    <a href="{{ route('admin.barbers.create') }}" class="btn btn-primary">Tambah Barber</a>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Foto</th>
                        <th>Keahlian</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barbers as $barber)
                        <tr>
                            <td>{{ $barber->id }}</td>
                            <td>{{ $barber->name }}</td>
                            <td><img src="{{ $barber->photo }}" alt="foto" width="50"></td>
                            <td>{{ $barber->specialty }}</td>
                            <td>{{ $barber->status }}</td>
                            <td>
                                <a href="{{ route('admin.barbers.edit', $barber) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.barbers.destroy', $barber) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin hapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
