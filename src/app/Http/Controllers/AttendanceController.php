<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', today())
            ->first();

        if (!$attendance) {
            $status = 'before';
        } elseif ($attendance->clock_out) {
            $status = 'done';
        } elseif ($attendance->breakTimes()->whereNull('break_end')->exists()) {
            $status = 'break';
        } else {
            $status = 'working';
        }

        return view('attendance.index', compact('status'));
    }

    public function start()
    {
        $exists = Attendance::where('user_id', Auth::id())
            ->whereDate('date', today())
            ->exists();

        if ($exists) {
            return redirect('/attendance');
        }

        Attendance::create([
            'user_id' => Auth::id(),
            'date' => today(),
            'clock_in' => now(),
        ]);

        return redirect('/attendance');
    }

    public function end()
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', today())
            ->first();

        if (!$attendance || $attendance->clock_out) {
            return redirect('/attendance');
        }

        $attendance->update([
            'clock_out' => now(),
        ]);

        return redirect('/attendance');
    }

    public function breakstart()
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', today())
            ->first();

        if (!$attendance) {
            return redirect('/attendance');
        }

        $isBreaking = $attendance->breakTimes()
            ->whereNull('break_end')
            ->exists();

        if ($isBreaking) {
            return redirect('/attendance');
        }

        $attendance->breakTimes()->create([
            'break_start' => now(),
        ]);

        return redirect('/attendance');
    }

    public function breakEnd()
    {
        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', today())
            ->first();

        if (!$attendance) {
            return redirect('/attendance');
        }

        $break = $attendance->breakTimes()
            ->whereNull('break_end')
            ->latest()
            ->first();

        if ($break) {
            $break->update([
                'break_end' => now(),
            ]);
        }

        return redirect('/attendance');
    }
}