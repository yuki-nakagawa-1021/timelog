<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class AttendanceClockOutListTest extends TestCase
{
    use RefreshDatabase;

    public function test_clock_out_time_is_shown_in_attendance_list()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        // 出勤
        $this->post('/attendance/start');

        // 退勤時間を固定
        Carbon::setTestNow(Carbon::create(2026, 5, 20, 18, 0, 0));

        // 退勤
        $this->post('/attendance/end');

        // 一覧取得
        $response = $this->get('/attendance/list');

        $response->assertStatus(200);

        // 退勤時刻が表示されているか
        $response->assertSee('18:00');
    }
}