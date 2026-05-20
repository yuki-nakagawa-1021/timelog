<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class AttendanceBreakTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 休憩入ボタンが正しく機能する
     */
    public function test_break_start()
    {
        $user = User::factory()->create();

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => now()->toDateString(),
            'clock_in' => now(),
            'clock_out' => null,
        ]);

        // 休憩開始（仮ルート）
        $this->actingAs($user)->post('/attendance/break/start');

        $response = $this->actingAs($user)->get('/attendance');

        $response->assertStatus(200);
        $response->assertSee('休憩中');
    }

    /**
     * 休憩は何回でもできる
     */
    public function test_break_multiple_times()
    {
        $user = User::factory()->create();

        Attendance::create([
            'user_id' => $user->id,
            'date' => now()->toDateString(),
            'clock_in' => now(),
            'clock_out' => null,
        ]);

        // 2回休憩してもボタンが出る想定
        $this->actingAs($user)->post('/attendance/break/start');
        $this->actingAs($user)->post('/attendance/break/end');

        $response = $this->actingAs($user)->get('/attendance');

        $response->assertSee('休憩入');
    }

    /**
     * 休憩戻ボタンが正しく機能する
     */
    public function test_break_end()
    {
        $user = User::factory()->create();

        Attendance::create([
            'user_id' => $user->id,
            'date' => now()->toDateString(),
            'clock_in' => now(),
            'clock_out' => null,
        ]);

        $this->actingAs($user)->post('/attendance/break/start');
        $this->actingAs($user)->post('/attendance/break/end');

        $response = $this->actingAs($user)->get('/attendance');

        $response->assertStatus(200);
        $response->assertSee('出勤中');
    }

    /**
     * 休憩時刻が一覧で確認できる
     */
    public function test_break_time_shown_in_list()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        // 出勤
        $this->post('/attendance/start');

        // 休憩開始
        $breakStart = now()->setTime(12, 0);
        Carbon::setTestNow($breakStart);
        $this->post('/attendance/break/start');

        // 休憩終了
        $breakEnd = now()->setTime(12, 30);
        Carbon::setTestNow($breakEnd);
        $this->post('/attendance/break/end');

        // 一覧へ
        $response = $this->get('/attendance/list');

        $response->assertStatus(200);

        // 休憩時間（合計）が表示されていることを確認
        $response->assertSee('00:30');
    }
}