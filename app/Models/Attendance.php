<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'attendance_date',
        'check_in_time',
        'check_out_time',
        'status',
        'qr_token',
        'notes',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getHoursWorkedAttribute(): float
    {
        if (!$this->check_in_time) {
            return 0;
        }

        // Combine attendance_date with check_in_time to create full datetime
        $checkInDateTime = \Carbon\Carbon::parse($this->attendance_date->format('Y-m-d') . ' ' . $this->check_in_time);

        // If user is currently checked in (no check-out time), calculate from check-in to now
        if (!$this->check_out_time) {
            $now = \Carbon\Carbon::now();
            $minutes = abs($now->diffInMinutes($checkInDateTime));
            return round($minutes / 60, 2);
        }

        // Combine attendance_date with check_out_time to create full datetime
        $checkOutDateTime = \Carbon\Carbon::parse($this->attendance_date->format('Y-m-d') . ' ' . $this->check_out_time);

        // Calculate difference in minutes and convert to hours (use abs to ensure positive)
        $minutes = abs($checkOutDateTime->diffInMinutes($checkInDateTime));
        
        return round($minutes / 60, 2);
    }
}
