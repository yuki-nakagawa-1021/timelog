<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AttendanceDatetimeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 勤怠打刻画面の日時表示が現在日時と一致する
     */
    public function test_attendance_datetime_display()
    {
        $now = Carbon::create(2026, 5, 20, 12, 34, 56);
        Carbon::setTestNow($now);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/attendance');

        $expected = $now->format('H:i');

        $response->assertStatus(200);
        $response->assertSee($expected);
    }
}