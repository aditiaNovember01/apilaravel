@extends('adminlte::page')

@section('title', 'Tambah Booking')

@section('content_header')
    <h1>Tambah Booking</h1>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.bookings.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>User</label>
            <select name="user_id" class="form-control" required>
                <option value="">Pilih User</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Tanggal Booking</label>
            <input type="date" name="booking_date" class="form-control" required value="{{ old('booking_date') }}"
                min="{{ date('Y-m-d') }}">
        </div>
        <div class="form-group">
            <label>Waktu Booking</label>
            <input type="time" name="booking_time" class="form-control" required value="{{ old('booking_time') }}">
        </div>
        <div class="form-group">
            <label>Barber</label>
            <select name="barber_id" id="barber-select" class="form-control" required>
                <option value="">Pilih Barber</option>
                @foreach ($barbers as $barber)
                    <option value="{{ $barber->id }}" {{ old('barber_id') == $barber->id ? 'selected' : '' }}>
                        {{ $barber->name }} - {{ $barber->specialty }}</option>
                @endforeach
            </select>
            <small class="form-text text-muted">Barber akan difilter berdasarkan ketersediaan setelah memilih tanggal dan
                waktu</small>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="done" {{ old('status') == 'done' ? 'selected' : '' }}>Done</option>
                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div class="form-group">
            <label>Jumlah Pembayaran</label>
            <input type="number" step="0.01" name="amount" class="form-control" required value="{{ old('amount') }}">
        </div>
        <div class="form-group">
            <label>Status Pembayaran</label>
            <select name="payment_status" class="form-control" required>
                <option value="unpaid" {{ old('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
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

@push('js')
    <script>
        $(document).ready(function() {
            let originalBarbers = [];

            // Store original barbers list
            $('#barber-select option').each(function() {
                if ($(this).val()) {
                    originalBarbers.push({
                        value: $(this).val(),
                        text: $(this).text()
                    });
                }
            });

            // Function to check barber availability
            function checkBarberAvailability() {
                const date = $('input[name="booking_date"]').val();
                const time = $('input[name="booking_time"]').val();

                if (date && time) {
                    $.ajax({
                        url: '{{ route('admin.bookings.available-barbers') }}',
                        type: 'GET',
                        data: {
                            date: date,
                            time: time
                        },
                        success: function(response) {
                            // Clear current options
                            $('#barber-select').empty();
                            $('#barber-select').append('<option value="">Pilih Barber</option>');

                            if (response.barbers.length > 0) {
                                response.barbers.forEach(function(barber) {
                                    $('#barber-select').append(
                                        '<option value="' + barber.id + '">' +
                                        barber.name + ' - ' + barber.specialty +
                                        '</option>'
                                    );
                                });
                            } else {
                                $('#barber-select').append(
                                    '<option value="" disabled>Tidak ada barber yang tersedia</option>'
                                    );
                            }
                        },
                        error: function() {
                            // If error, show all barbers
                            $('#barber-select').empty();
                            $('#barber-select').append('<option value="">Pilih Barber</option>');
                            originalBarbers.forEach(function(barber) {
                                $('#barber-select').append(
                                    '<option value="' + barber.value + '">' + barber.text +
                                    '</option>'
                                );
                            });
                        }
                    });
                }
            }

            // Check availability when date or time changes
            $('input[name="booking_date"], input[name="booking_time"]').change(function() {
                checkBarberAvailability();
            });

            // Set minimum date to today
            $('input[name="booking_date"]').attr('min', new Date().toISOString().split('T')[0]);
        });
    </script>
@endpush
