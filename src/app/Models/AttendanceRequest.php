<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'attendance_id',
        'status',
        'reason',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function items()
    {
        return $this->hasMany(AttendanceRequestItem::class);
    }
}