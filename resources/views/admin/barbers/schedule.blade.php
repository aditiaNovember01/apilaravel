@php
    use Carbon\Carbon;
@endphp

<div class="schedule-container">
    <div class="row">
        <div class="col-12">
            <h4>Jadwal {{ $barber->name }} ({{ $barber->specialty }})</h4>
            <p class="text-muted">Jadwal untuk {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</p>
        </div>
    </div>

    <div class="row">
        @php
            $currentDate = $startDate->copy();
        @endphp

        @while ($currentDate <= $endDate)
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            {{ $currentDate->format('l, d/m/Y') }}
                            @if ($currentDate->isToday())
                                <span class="badge badge-primary">Hari Ini</span>
                            @endif
                        </h6>
                    </div>
                    <div class="card-body">
                        @php
                            $dayBookings = $bookings->get($currentDate->format('Y-m-d'), collect());
                            $busyTimeSlots = $dayBookings->pluck('booking_time')->toArray();
                        @endphp

                        @if ($dayBookings->count() > 0)
                            <div class="bookings-list">
                                @foreach ($dayBookings as $booking)
                                    <div class="booking-item mb-2 p-2 border rounded">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $booking->booking_time }}</strong>
                                                <br>
                                                <small
                                                    class="text-muted">{{ $booking->user->name ?? 'Unknown' }}</small>
                                            </div>
                                            <div class="text-right">
                                                <span
                                                    class="badge badge-{{ $booking->status == 'confirmed' ? 'warning' : 'success' }}">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                                <br>
                                                <small class="text-muted">Rp
                                                    {{ number_format($booking->amount, 0, ',', '.') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                <p class="mb-0">Tidak ada booking</p>
                            </div>
                        @endif

                        <!-- Availability Summary -->
                        <div class="mt-3 pt-3 border-top">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="text-success">
                                        @php
                                            $confirmedBookings = $dayBookings->where('status', 'confirmed')->count();
                                            $availableSlots = 8 - $confirmedBookings;
                                        @endphp
                                        <strong>{{ $availableSlots }}</strong>
                                        <br>
                                        <small>Tersedia</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-danger">
                                        <strong>{{ $confirmedBookings }}</strong>
                                        <br>
                                        <small>Terbooking</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @php
                $currentDate->addDay();
            @endphp
        @endwhile
    </div>

    @if ($bookings->count() == 0)
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                    <h5>Tidak ada booking dalam 7 hari ke depan</h5>
                    <p class="mb-0">Barber ini belum memiliki jadwal booking yang dikonfirmasi.</p>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .schedule-container {
        max-height: 70vh;
        overflow-y: auto;
    }

    .booking-item {
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }

    .booking-item:hover {
        background-color: #e9ecef;
        transform: translateY(-1px);
    }

    .card {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
</style>
