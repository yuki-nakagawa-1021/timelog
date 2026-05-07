<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BreakTime;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
    ];

    public function breakTimes()
    {
        return $this->hasMany(BreakTime::class);
    }

    public function requests()
    {
        return $this->hasMany(AttendanceRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
