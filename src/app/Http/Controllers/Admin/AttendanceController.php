<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));

        $attendances = Attendance::with(['user', 'breakTimes'])
            ->whereDate('date', $date)
            ->get();

        return view('admin.attendance.list', compact('attendances', 'date'));
    }

    public function show($id)
    {
        $attendance = Attendance::with(['user', 'breakTimes'])->findOrFail($id);
        $date = $attendance->date;

        return view('admin.attendance.detail', compact('attendance', 'date'));
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $attendance->update([
            'clock_in' => $request->clock_in,
            'clock_out' => $request->clock_out,
            'note' => $request->note,
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