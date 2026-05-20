<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendanceDetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function detail_shows_logged_in_user_name()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
        ]);

        $this->actingAs($user);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => '2026-05-20',
            'clock_in' => '09:00:00',
            'clock_out' => '18:00:00',
        ]);

        $response = $this->get('/attendance/detail/' . $attendance->id);

        $response->assertStatus(200);
        $response->assertSee('Test User');
    }

    /** @test */
    public function detail_shows_correct_date()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => '2026-05-20',
        ]);

        $response = $this->get('/attendance/detail/' . $attendance->id);

        $response->assertStatus(200);

        $response->assertSee('2026年');
        $response->assertSee('5月20日');
    }

    /** @test */
    public function detail_shows_clock_in_and_out()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => '2026-05-20',
            'clock_in' => '09:00:00',
            'clock_out' => '18:00:00',
        ]);

        $response = $this->get('/attendance/detail/' . $attendance->id);

        $response->assertStatus(200);

        $response->assertSee('09:00');
        $response->assertSee('18:00');

        $response->assertSee('休憩');
        $response->assertSee('break_start[]');
        $response->assertSee('break_end[]');
    }

    /** @test */
    public function detail_shows_break_time()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => '2026-05-20',
        ]);

        $response = $this->get('/attendance/detail/' . $attendance->id);

        $response->assertStatus(200);

        $response->assertSee('休憩');
    }
}