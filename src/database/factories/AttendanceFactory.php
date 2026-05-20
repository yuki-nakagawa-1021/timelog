<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'date' => now()->format('Y-m-d'),
            'clock_in' => now()->setTime(9, 0, 0),
            'clock_out' => now()->setTime(18, 0, 0),
            'break_time' => 60,
            'status' => 'done',
        ];
    }
}