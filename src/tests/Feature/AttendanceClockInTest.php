<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceClockInTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 出勤ボタンが正しく機能する
     */
    public function test_clock_in_success()
    {
        $user = User::factory()->create();

        // 勤務外（まだattendanceなし）
        $response = $this->actingAs($user)->get('/attendance');

        $response->assertStatus(200);
        $response->assertSee('出勤');

        // 出勤処理
        $this->actingAs($user)->post('/attendance/start');

        // 出勤後の状態確認
        $response = $this->actingAs($user)->get('/attendance');

        $response->assertSee('出勤中');
    }

    /**
     * 出勤は一日一回のみ
     */
    public function test_clock_in_only_once()
    {
        $user = User::factory()->create();

        // すでに退勤済データ
        Attendance::create([
            'user_id' => $user->id,
            'date' => now()->toDateString(),
            'clock_in' => now()->subHours(8),
            'clock_out' => now(),
        ]);

        $response = $this->actingAs($user)->get('/attendance');

        // 出勤ボタンが出ない
        $response->assertDontSee('出勤');
    }

    /**
     * 出勤時刻が一覧で確認できる
     */
    public function test_clock_in_time_shown_in_list()
    {
        $user = User::factory()->create();

        $clockInTime = now();

        Attendance::create([
            'user_id' => $user->id,
            'date' => now()->toDateString(),
            'clock_in' => $clockInTime,
            'clock_out' => null,
        ]);

        $response = $this->actingAs($user)->get('/attendance/list');

        $response->assertStatus(200);
        $response->assertSee($clockInTime->format('H:i'));
    }
}