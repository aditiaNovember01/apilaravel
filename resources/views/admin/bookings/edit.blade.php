@extends('adminlte::page')

@section('title', 'Edit Booking')

@section('content_header')
    <h1>Edit Booking</h1>
@endsection

@section('content')
    <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>User</label>
            <select name="user_id" class="form-control" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $booking->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Barber</label>
            <select name="barber_id" class="form-control" required>
                @foreach ($barbers as $barber)
                    <option value="{{ $barber->id }}" {{ $booking->barber_id == $barber->id ? 'selected' : '' }}>
                        {{ $barber->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Tanggal Booking</label>
            <input type="date" name="booking_date" class="form-control" required value="{{ $booking->booking_date }}">
        </div>
        <div class="form-group">
            <label>Waktu Booking</label>
            <input type="time" name="booking_time" class="form-control" required value="{{ $booking->booking_time }}">
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="done" {{ $booking->status == 'done' ? 'selected' : '' }}>Done</option>
                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div class="form-group">
            <label>Jumlah Pembayaran</label>
            <input type="number" step="0.01" name="amount" class="form-control" required
                value="{{ $booking->amount }}">
        </div>
        <div class="form-group">
            <label>Status Pembayaran</label>
            <select name="payment_status" class="form-control" required>
                <option value="unpaid" {{ $booking->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                <option value="paid" {{ $booking->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
            </select>
        </div>
        <div class="form-group">
            <label>Bukti Pembayaran</label>
            <input type="file" name="proof_of_payment" class="form-control" accept="image/*">
            @if ($booking->proof_of_payment)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $booking->proof_of_payment) }}" alt="bukti" width="100">
                </div>
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection
