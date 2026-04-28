<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Requests\AttendanceRequest;

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

    public function list(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));

        $start = Carbon::parse($month)->startOfMonth();
        $end = Carbon::parse($month)->endOfMonth();

        $attendances = Attendance::where('user_id', Auth::id())
            ->whereBetween('date', [$start, $end])
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });

        $dates = [];
        $current = $start->copy();

        while ($current <= $end) {
            $dates[] = $current->copy();
            $current->addDay();
        }

        return view('attendance.list', compact('attendances', 'dates', 'month'));
    }

    public function show($date)
    {
        $attendance = Attendance::with('breakTimes')
            ->where('user_id', Auth::id())
            ->whereDate('date', $date)
            ->first();

        return view('attendance.detail', compact('attendance', 'date'));
    }

    public function update(AttendanceRequest $request, $date)
    {
        $attendance = Attendance::firstOrCreate([
            'user_id' => Auth::id(),
            'date' => $date
        ]);

        $attendance->update([
            'clock_in' => $request->clock_in,
            'clock_out' => $request->clock_out,
            'note' => $request->note,
            'status' => 'pending'
        ]);

        $attendance->breakTimes()->delete();

        if ($request->break_start) {
            foreach ($request->break_start as $i => $start) {
                $end = $request->break_end[$i] ?? null;

                if ($start && $end) {
                    $attendance->breakTimes()->create([
                        'break_start' => $start,
                        'break_end' => $end,
                    ]);
                }
            }
        }

        return redirect()->back();
    }
}