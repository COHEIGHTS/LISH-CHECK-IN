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
        if (!$this->check_in_time || !$this->check_out_time) {
            return 0;
        }

        // Parse the time strings as Carbon objects
        $checkIn = \Carbon\Carbon::parse($this->check_in_time);
        $checkOut = \Carbon\Carbon::parse($this->check_out_time);

        // Calculate difference in minutes and convert to hours
        $minutes = $checkOut->diffInMinutes($checkIn);
        
        return round($minutes / 60, 2);
    }
}
