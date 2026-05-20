<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendanceListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_attendance_is_displayed()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => now()->format('Y-m-d'),
        ]);

        $response = $this->get('/attendance/list');

        $response->assertStatus(200);
    }

    /** @test */
    public function current_month_is_displayed()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/attendance/list');

        $response->assertStatus(200);

        $response->assertSee(now()->format('Y/m'));
    }

    /** @test */
    public function previous_month_button_works()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $lastMonth = now()->subMonth()->format('Y-m');

        $response = $this->get('/attendance/list?month=' . $lastMonth);

        $response->assertStatus(200);
        $response->assertSee(now()->subMonth()->format('Y/m'));
    }

    /** @test */
    public function next_month_button_works()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $nextMonth = now()->addMonth()->format('Y-m');

        $response = $this->get('/attendance/list?month=' . $nextMonth);

        $response->assertStatus(200);
        $response->assertSee(now()->addMonth()->format('Y/m'));
    }

    /** @test */
    public function detail_link_is_displayed()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'date' => now()->format('Y-m-d'),
        ]);

        $response = $this->get('/attendance/list');

        $response->assertStatus(200);

        $response->assertSee('/attendance/detail/' . $attendance->id);
    }
}