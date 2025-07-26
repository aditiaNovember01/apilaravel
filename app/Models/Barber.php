<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Barber extends Model
{
    use HasFactory;

    protected $table = 'barbers';

    protected $fillable = [
        'name',
        'photo',
        'specialty',
        'status',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Check if barber is available at specific date and time
     */
    public function isAvailableAt($date, $time)
    {
        // Check if barber has any confirmed bookings at the same date and time
        // Booking dengan status 'done' tidak menghabiskan slot
        $conflictingBooking = $this->bookings()
            ->where('booking_date', $date)
            ->where('booking_time', $time)
            ->where('status', 'confirmed')
            ->first();

        return $conflictingBooking === null;
    }

    /**
     * Get available barbers for specific date and time
     */
    public static function getAvailableBarbers($date, $time)
    {
        return self::whereDoesntHave('bookings', function ($query) use ($date, $time) {
            $query->where('booking_date', $date)
                ->where('booking_time', $time)
                ->where('status', 'confirmed'); // Hanya booking confirmed yang menghabiskan slot
        })->where('status', 'active')->get();
    }

    /**
     * Get barber's schedule for a specific date
     */
    public function getScheduleForDate($date)
    {
        return $this->bookings()
            ->where('booking_date', $date)
            ->whereIn('status', ['confirmed', 'done']) // Tampilkan confirmed dan done untuk jadwal
            ->orderBy('booking_time')
            ->get();
    }

    /**
     * Get barber's busy time slots for a specific date
     */
    public function getBusyTimeSlots($date)
    {
        return $this->bookings()
            ->where('booking_date', $date)
            ->where('status', 'confirmed') // Hanya confirmed yang dianggap busy
            ->pluck('booking_time')
            ->toArray();
    }
}
