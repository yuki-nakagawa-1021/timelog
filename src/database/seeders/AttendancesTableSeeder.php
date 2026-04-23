<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendancesTableSeeder extends Seeder
{
    public function run()
    {
        $user = DB::table('users')->where('role', 'user')->first();

        $attendanceId = DB::table('attendances')->insertGetId([
            'user_id' => $user->id,
            'date' => now()->format('Y-m-d'),
            'clock_in' => now()->format('Y-m-d') . ' 09:00:00',
            'clock_out' => now()->format('Y-m-d') . ' 18:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('break_times')->insert([
            'attendance_id' => $attendanceId,
            'break_start' => now()->format('Y-m-d') . ' 12:00:00',
            'break_end' => now()->format('Y-m-d') . ' 13:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}