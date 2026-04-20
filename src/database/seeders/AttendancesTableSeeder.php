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
            'clock_in' => '09:00:00',
            'clock_out' => '18:00:00',
            'status' => '退勤済',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('breaks')->insert([
            'attendance_id' => $attendanceId,
            'break_start' => '12:00:00',
            'break_end' => '13:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}