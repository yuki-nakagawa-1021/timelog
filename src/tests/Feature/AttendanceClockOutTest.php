<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendanceClockOutTest extends TestCase
{
    use RefreshDatabase;

    public function test_clock_out_button_works_and_status_becomes_done()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        // 出勤
        $this->post('/attendance/start');

        // 退勤
        $this->post('/attendance/end');

        // 画面確認
        $response = $this->get('/attendance');

        $response->assertStatus(200);
        $response->assertSee('退勤済');
    }
}