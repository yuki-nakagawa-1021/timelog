<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
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

    public function staffAttendance(Request $request, $id)
    {
        $month = $request->input('month', now()->format('Y-m'));

        $user = User::findOrFail($id);

        $attendances = Attendance::with('breakTimes')
            ->where('user_id', $id)
            ->whereYear('date', Carbon::parse($month)->year)
            ->whereMonth('date', Carbon::parse($month)->month)
            ->get();

        return view(
            'admin.staff.attendance_list',
            compact('user', 'attendances', 'month')
        );
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

        $breakStarts = $request->break_start ?? [];
        $breakEnds = $request->break_end ?? [];

        foreach ($breakStarts as $i => $start) {

            $end = $breakEnds[$i] ?? null;

            if ($start && $end) {

                $attendance->breakTimes()->create([
                    'break_start' => $start,
                    'break_end' => $end,
                ]);
            }
        }

        return redirect()->back();
    }
}