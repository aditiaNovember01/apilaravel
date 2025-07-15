@extends('layouts.app')

@section('content_header')
    <h1>Data Bookings</h1>
    <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary">Tambah Booking</a>
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Barber</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Status</th>
                        <th>Jumlah</th>
                        <th>Status Bayar</th>
                        <th>Bukti Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ $booking->user->name ?? '-' }}</td>
                            <td>{{ $booking->barber->name ?? '-' }}</td>
                            <td>{{ $booking->booking_date }}</td>
                            <td>{{ $booking->booking_time }}</td>
                            <td>{{ $booking->status }}</td>
                            <td>{{ $booking->amount }}</td>
                            <td>{{ $booking->payment_status }}</td>
                            <td>
                                @if ($booking->proof_of_payment)
                                    <a href="{{ $booking->proof_of_payment }}" target="_blank">Lihat</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.bookings.edit', $booking) }}"
                                    class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST"
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
