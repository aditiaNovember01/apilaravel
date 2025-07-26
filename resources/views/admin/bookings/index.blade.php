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
                        <th>Jumlah</th>
                        <th>Status Bayar</th>
                        <th>Bukti Bayar</th>
                        <th>Status</th>
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
                            <td>Rp {{ number_format($booking->amount, 0, ',', '.') }}</td>
                            <td>
                                <select class="form-control payment-status-select" data-booking-id="{{ $booking->id }}"
                                    style="width: auto;">
                                    <option value="unpaid" {{ $booking->payment_status == 'unpaid' ? 'selected' : '' }}>
                                        Unpaid</option>
                                    <option value="paid" {{ $booking->payment_status == 'paid' ? 'selected' : '' }}>Paid
                                    </option>
                                </select>
                                <div class="payment-status-message mt-1" id="payment-status-message-{{ $booking->id }}">
                                </div>
                            </td>
                            <td>
                                @if ($booking->proof_of_payment)
                                    <a href="{{ route('admin.bookings.proof-of-payment', $booking->id) }}" target="_blank"
                                        class="btn btn-info btn-sm">Lihat Bukti</a>
                                @else
                                    <span class="text-muted">Tidak ada bukti</span>
                                @endif
                            </td>
                            <td>
                                <select class="form-control status-select" data-booking-id="{{ $booking->id }}"
                                    style="width: auto;">
                                    <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>
                                        Confirmed</option>
                                    <option value="done" {{ $booking->status == 'done' ? 'selected' : '' }}>Done</option>
                                    <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                </select>
                                <div class="status-message mt-1" id="status-message-{{ $booking->id }}"></div>
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

@push('js')
    <script>
        $(document).ready(function() {
            // Handle status change
            $('.status-select').change(function() {
                const bookingId = $(this).data('booking-id');
                const newStatus = $(this).val();
                const messageDiv = $('#status-message-' + bookingId);

                // Show loading
                messageDiv.html('<small class="text-info">Mengubah status...</small>');

                $.ajax({
                    url: '{{ route('admin.bookings.update-status', ':id') }}'.replace(':id',
                        bookingId),
                    type: 'PATCH',
                    data: {
                        status: newStatus,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        messageDiv.html('<small class="text-success">✓ ' + response.message +
                            '</small>');

                        // Show success notification using SweetAlert2
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Jika status berubah menjadi 'done', berikan notifikasi khusus
                        if (newStatus === 'done') {
                            setTimeout(function() {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Slot Tersedia',
                                    text: 'Slot barber telah dikembalikan menjadi tersedia!',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            }, 2500);
                        }

                        // Remove message after 3 seconds
                        setTimeout(function() {
                            messageDiv.html('');
                        }, 3000);
                    },
                    error: function(xhr) {
                        messageDiv.html(
                            '<small class="text-danger">✗ Gagal mengubah status</small>');

                        // Show error notification
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Gagal mengubah status booking',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Remove message after 3 seconds
                        setTimeout(function() {
                            messageDiv.html('');
                        }, 3000);
                    }
                });
            });

            // Handle payment status change
            $('.payment-status-select').change(function() {
                const bookingId = $(this).data('booking-id');
                const newPaymentStatus = $(this).val();
                const messageDiv = $('#payment-status-message-' + bookingId);

                // Show loading
                messageDiv.html('<small class="text-info">Mengubah status pembayaran...</small>');

                $.ajax({
                    url: '{{ route('admin.bookings.update-payment-status', ':id') }}'.replace(
                        ':id', bookingId),
                    type: 'PATCH',
                    data: {
                        payment_status: newPaymentStatus,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        messageDiv.html('<small class="text-success">✓ ' + response.message +
                            '</small>');

                        // Show success notification using SweetAlert2
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Remove message after 3 seconds
                        setTimeout(function() {
                            messageDiv.html('');
                        }, 3000);
                    },
                    error: function(xhr) {
                        messageDiv.html(
                            '<small class="text-danger">✗ Gagal mengubah status pembayaran</small>'
                        );

                        // Show error notification
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Gagal mengubah status pembayaran',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // Remove message after 3 seconds
                        setTimeout(function() {
                            messageDiv.html('');
                        }, 3000);
                    }
                });
            });
        });
    </script>
@endpush
