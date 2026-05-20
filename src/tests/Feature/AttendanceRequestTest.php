<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Attendance;
use App\Models\AttendanceRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendanceRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function clock_in_after_clock_out_shows_error()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'clock_in' => '18:00',
            'clock_out' => '09:00',
        ]);

        $response = $this->post("/attendance/request/{$attendance->id}", [
            'clock_in' => '18:00',
            'clock_out' => '09:00',
        ]);

        $response->assertSessionHasErrors();
    }

    /** @test */
    public function break_start_after_clock_out_shows_error()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'clock_in' => '09:00',
            'clock_out' => '18:00',
        ]);

        $response = $this->post("/attendance/request/{$attendance->id}", [
            'clock_in' => '09:00',
            'clock_out' => '18:00',
            'break_start' => ['19:00'],
            'break_end' => ['20:00'],
        ]);

        $response->assertSessionHasErrors();
    }

    /** @test */
    public function break_end_after_clock_out_shows_error()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
            'clock_in' => '09:00',
            'clock_out' => '18:00',
        ]);

        $response = $this->post("/attendance/request/{$attendance->id}", [
            'clock_in' => '09:00',
            'clock_out' => '18:00',
            'break_start' => ['10:00'],
            'break_end' => ['19:00'],
        ]);

        $response->assertSessionHasErrors();
    }

    /** @test */
    public function note_is_required()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->post("/attendance/request/{$attendance->id}", [
            'clock_in' => '09:00',
            'clock_out' => '18:00',
            'note' => '',
        ]);

        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_is_created()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->post("/attendance/request/{$attendance->id}", [
            'clock_in' => '09:00',
            'clock_out' => '18:00',
            'note' => '修正します',
            'break_start' => ['10:00'],
            'break_end' => ['11:00'],
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('attendance_requests', [
            'attendance_id' => $attendance->id,
        ]);
    }

    /** @test */
    public function pending_requests_are_listed()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        AttendanceRequest::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $response = $this->get('/stamp_correction_request/list?status=pending');

        $response->assertStatus(200);
        $response->assertSee('pending');
    }

    /** @test */
    public function approved_requests_are_listed()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        AttendanceRequest::factory()->create([
            'user_id' => $user->id,
            'status' => 'approved',
        ]);

        $response = $this->get('/stamp_correction_request/list?status=approved');

        $response->assertStatus(200);
        $response->assertSee('approved');
    }

    /** @test */
    public function detail_button_redirects()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $attendance = Attendance::factory()->create([
            'user_id' => $user->id,
        ]);

        AttendanceRequest::factory()->create([
            'attendance_id' => $attendance->id,
            'user_id' => $user->id,
        ]);

        $response = $this->get('/stamp_correction_request/list');

        $response->assertStatus(200);
        $response->assertSee('/attendance/detail/');
    }
}