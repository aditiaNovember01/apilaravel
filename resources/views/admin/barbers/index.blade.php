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
                        <th>Ketersediaan Hari Ini</th>
                        <th>Jadwal Booking</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($barbers as $barber)
                        <tr>
                            <td>{{ $barber->id }}</td>
                            <td>{{ $barber->name }}</td>
                            <td>
                                @if ($barber->photo)
                                    <img src="{{ asset('storage/' . $barber->photo) }}" alt="foto" width="50">
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $barber->specialty }}</td>
                            <td>
                                <span class="badge badge-{{ $barber->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($barber->status) }}
                                </span>
                            </td>
                            <td>
                                @if ($barber->status == 'active')
                                    @php
                                        $today = date('Y-m-d');
                                        // Hanya booking dengan status 'confirmed' yang menghabiskan slot
                                        $confirmedBookings = $barber
                                            ->bookings()
                                            ->where('booking_date', $today)
                                            ->where('status', 'confirmed')
                                            ->count();
                                        $totalSlots = 8; // Assuming 8 working hours
                                        $availableSlots = $totalSlots - $confirmedBookings;
                                    @endphp

                                    @if ($availableSlots > 0)
                                        <span class="badge badge-success">{{ $availableSlots }} slot tersedia</span>
                                    @else
                                        <span class="badge badge-danger">Penuh</span>
                                    @endif
                                    <small class="d-block text-muted">{{ $confirmedBookings }} booking aktif hari
                                        ini</small>
                                @else
                                    <span class="badge badge-secondary">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                @if ($barber->status == 'active')
                                    @php
                                        // Tampilkan hanya booking yang masih aktif (confirmed) dan yang sudah selesai (done)
                                        $todayBookings = $barber
                                            ->bookings()
                                            ->where('booking_date', $today)
                                            ->whereIn('status', ['confirmed', 'done'])
                                            ->orderBy('booking_time')
                                            ->get();
                                    @endphp

                                    @if ($todayBookings->count() > 0)
                                        <div class="small">
                                            @foreach ($todayBookings as $booking)
                                                <div class="mb-1">
                                                    <span
                                                        class="badge badge-{{ $booking->status == 'confirmed' ? 'warning' : 'success' }}">
                                                        {{ $booking->booking_time }}
                                                    </span>
                                                    <small
                                                        class="text-muted">{{ $booking->user->name ?? 'Unknown' }}</small>
                                                    <br>
                                                    <small
                                                        class="text-{{ $booking->status == 'confirmed' ? 'warning' : 'success' }}">
                                                        {{ ucfirst($booking->status) }}
                                                    </small>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <small class="text-muted">Tidak ada booking hari ini</small>
                                    @endif
                                @else
                                    <small class="text-muted">-</small>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.barbers.edit', $barber) }}"
                                    class="btn btn-warning btn-sm">Edit</a>
                                <button type="button" class="btn btn-info btn-sm"
                                    onclick="showBarberSchedule({{ $barber->id }}, '{{ $barber->name }}')">
                                    Jadwal
                                </button>
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

    <!-- Modal for Barber Schedule -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleModalLabel">Jadwal Barber</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="scheduleModalBody">
                    <!-- Schedule content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        function showBarberSchedule(barberId, barberName) {
            // Show loading in modal
            $('#scheduleModalLabel').text('Jadwal ' + barberName);
            $('#scheduleModalBody').html(
            '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
            $('#scheduleModal').modal('show');

            // Load schedule data
            $.ajax({
                url: '{{ route('admin.barbers.schedule', ':id') }}'.replace(':id', barberId),
                type: 'GET',
                success: function(response) {
                    $('#scheduleModalBody').html(response);
                },
                error: function() {
                    $('#scheduleModalBody').html('<div class="alert alert-danger">Gagal memuat jadwal</div>');
                }
            });
        }
    </script>
@endpush
