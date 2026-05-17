@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/attendance_list.css') }}">
@endsection

@section('content')

<div class="staff-attendance-wrapper">
    <h2 class="staff-attendance-title">{{ $user->name }}さんの勤怠</h2>
    <div class="month-nav">
        <a href="?month={{ \Carbon\Carbon::parse($month)->subMonth()->format('Y-m') }}" class="month-link">
            ← 前月
        </a>
        <form method="GET" action="" class="month-form">
            <input type="month" name="month" value="{{ $month }}" class="month-input" onchange="this.form.submit()">

        </form>
        <a href="?month={{ \Carbon\Carbon::parse($month)->addMonth()->format('Y-m') }}" class="month-link">
            翌月 →
        </a>
    </div>
    <table class="staff-attendance-table">
        <thead>
            <tr>
                <th>日付</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)

                @php

                    $breakMinutes = $attendance->breakTimes->sum(function($break){

                        if ($break->break_start && $break->break_end) {

                            return \Carbon\Carbon::parse($break->break_end)
                                ->diffInMinutes(
                                    \Carbon\Carbon::parse($break->break_start)
                                );
                        }

                        return 0;
                    });

                    $workMinutes = 0;

                    if ($attendance->clock_in && $attendance->clock_out) {

                        $workMinutes =
                            \Carbon\Carbon::parse($attendance->clock_out)
                                ->diffInMinutes(
                                    \Carbon\Carbon::parse($attendance->clock_in)
                                ) - $breakMinutes;
                    }

                @endphp
                <tr>
                    <td>
                        {{ \Carbon\Carbon::parse($attendance->date)->isoFormat('MM/DD(ddd)') }}
                    </td>
                    <td>
                        {{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '' }}
                    </td>
                    <td>
                        {{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '' }}
                    </td>
                    <td>
                        {{ floor($breakMinutes / 60) }}:{{ str_pad($breakMinutes % 60, 2, '0', STR_PAD_LEFT) }}
                    </td>
                    <td>
                        {{ floor($workMinutes / 60) }}:{{ str_pad($workMinutes % 60, 2, '0', STR_PAD_LEFT) }}
                    </td>
                    <td>
                        <a href="/admin/attendance/detail/{{ $attendance->id }}" class="detail-link">
                            詳細
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="csv-button-area">
        <a href="/admin/attendance/staff/{{ $user->id }}/csv?month={{ $month }}" class="csv-button">
            CSV出力
        </a>
    </div>
</div>

@endsection