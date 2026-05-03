<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_request_id',
        'field',
        'old_value',
        'new_value',
    ];

    public function request()
    {
        return $this->belongsTo(AttendanceRequest::class, 'attendance_request_id');
    }
}
