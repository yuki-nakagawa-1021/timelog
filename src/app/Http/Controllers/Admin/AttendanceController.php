<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use App\Models\AttendanceRequest;
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

    public function exportCsv(Request $request, $id)
    {
        $month = $request->input('month', now()->format('Y-m'));

        $user = User::findOrFail($id);

        $attendances = Attendance::with('breakTimes')
            ->where('user_id', $id)
            ->whereYear('date', Carbon::parse($month)->year)
            ->whereMonth('date', Carbon::parse($month)->month)
            ->get();

        $csvData = [];

        $csvData[] = [
            '日付',
            '出勤',
            '退勤',
            '休憩',
            '合計'
        ];

        foreach ($attendances as $attendance) {

            $breakMinutes = $attendance->breakTimes->sum(function($break){

                if ($break->break_start && $break->break_end) {

                    return Carbon::parse($break->break_end)
                        ->diffInMinutes(
                            Carbon::parse($break->break_start)
                        );
                }

                return 0;
            });

            $workMinutes = 0;

            if ($attendance->clock_in && $attendance->clock_out) {

                $workMinutes =
                    Carbon::parse($attendance->clock_out)
                        ->diffInMinutes(
                            Carbon::parse($attendance->clock_in)
                        ) - $breakMinutes;
            }

            $csvData[] = [
                Carbon::parse($attendance->date)->format('Y-m-d'),
                $attendance->clock_in
                    ? Carbon::parse($attendance->clock_in)->format('H:i')
                    : '',
                $attendance->clock_out
                    ? Carbon::parse($attendance->clock_out)->format('H:i')
                    : '',
                floor($breakMinutes / 60) . ':' .
                    str_pad($breakMinutes % 60, 2, '0', STR_PAD_LEFT),
                floor($workMinutes / 60) . ':' .
                    str_pad($workMinutes % 60, 2, '0', STR_PAD_LEFT),
            ];
        }

        $fileName =
            $user->name . '_' . $month . '_attendance.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' =>
                'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($csvData) {

            $file = fopen('php://output', 'w');

            foreach ($csvData as $row) {

                mb_convert_variables('SJIS-win', 'UTF-8', $row);

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function requestList(Request $request)
        {
            $status = $request->input('status', 'pending');

            $requests = AttendanceRequest::with([
                'user',
                'attendance'
            ])
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();

            return view(
                'admin.stamp_correction_request.list',
                compact('requests', 'status')
            );
        }

    public function approve($id)
    {
        $attendanceRequest = AttendanceRequest::with('items')
            ->findOrFail($id);

        $attendance = Attendance::findOrFail(
            $attendanceRequest->attendance_id
        );

        foreach ($attendanceRequest->items as $item) {

            if ($item->field === 'clock_in') {

                $attendance->clock_in =
                    Carbon::parse($attendance->date . ' ' . $item->new_value);

            } elseif ($item->field === 'clock_out') {

                $attendance->clock_out =
                    Carbon::parse($attendance->date . ' ' . $item->new_value);
            }
        }

        $attendance->status = 'approved';

        $attendance->save();

        $attendanceRequest->update([
            'status' => 'approved'
        ]);

        return redirect('/admin/stamp_correction_request/list');
    }

    public function showRequest($id)
    {
        $request = AttendanceRequest::with([
            'user',
            'attendance',
            'items'
        ])->findOrFail($id);

        return view(
            'admin.stamp_correction_request.approve',
            compact('request')
        );
    }
}