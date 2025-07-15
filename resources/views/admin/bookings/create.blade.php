@extends('adminlte::page')

@section('title', 'Tambah Booking')

@section('content_header')
    <h1>Tambah Booking</h1>
@endsection

@section('content')
    <form action="{{ route('admin.bookings.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>User</label>
            <select name="user_id" class="form-control" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Barber</label>
            <select name="barber_id" class="form-control" required>
                @foreach ($barbers as $barber)
                    <option value="{{ $barber->id }}">{{ $barber->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Tanggal Booking</label>
            <input type="date" name="booking_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Waktu Booking</label>
            <input type="time" name="booking_time" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="done">Done</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
        <div class="form-group">
            <label>Jumlah Pembayaran</label>
            <input type="number" step="0.01" name="amount" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Status Pembayaran</label>
            <select name="payment_status" class="form-control" required>
                <option value="unpaid">Unpaid</option>
                <option value="paid">Paid</option>
            </select>
        </div>
        <div class="form-group">
            <label>Bukti Pembayaran</label>
            <input type="file" name="proof_of_payment" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection
