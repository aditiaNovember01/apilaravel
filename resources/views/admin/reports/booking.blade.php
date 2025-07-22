@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Laporan Booking ({{ ucfirst($type) }})</h2>
        <form method="GET" class="mb-3">
            <div class="row g-2 align-items-end">
                <div class="col-auto">
                    <label for="type" class="form-label">Tipe Laporan</label>
                    <select name="type" id="type" class="form-select">
                        <option value="daily" {{ $type == 'daily' ? 'selected' : '' }}>Harian</option>
                        <option value="monthly" {{ $type == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                        <option value="yearly" {{ $type == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                    </select>
                </div>
                <div class="col-auto">
                    <label for="date" class="form-label">Tanggal</label>
                    <input type="date" name="date" id="date" class="form-control" value="{{ $date }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                </div>
            </div>
        </form>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Customer</th>
                    <th>Barber</th>
                    <th>Tanggal Booking</th>
                    <th>Jam</th>
                    <th>Status</th>
                    <th>Nominal</th>
                    <th>Status Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $booking->user->name ?? '-' }}</td>
                        <td>{{ $booking->barber->name ?? '-' }}</td>
                        <td>{{ $booking->booking_date }}</td>
                        <td>{{ $booking->booking_time }}</td>
                        <td>{{ $booking->status }}</td>
                        <td>Rp{{ number_format($booking->amount, 0, ',', '.') }}</td>
                        <td>{{ $booking->payment_status }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
