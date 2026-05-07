@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/attendance.css') }}">
@endsection

@section('content')
<div class="admin-wrapper">
    <h2 class="page-title">{{ \Carbon\Carbon::parse($date)->format('Y年n月j日') }}の勤怠</h2>
    <div class="date-nav">
        <a class="date-link" href="?date={{ \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d') }}">← 前日</a>
        <form method="GET" action="/admin/attendance/list" class="date-form">
            <input class="date-input" type="date" name="date" value="{{ $date }}" onchange="this.form.submit()">
        </form>
        <div class="date-center">
            {{ \Carbon\Carbon::parse($date)->format('Y/m/d') }}
        </div>
        <a class="date-link" href="?date={{ \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d') }}">翌日 →</a>
    </div>
    <table class="attendance-table">
        <thead class="table-head">
            <tr class="table-row">
                <th class="table-heading">名前</th>
                <th class="table-heading">出勤</th>
                <th class="table-heading">退勤</th>
                <th class="table-heading">休憩</th>
                <th class="table-heading">合計</th>
                <th class="table-heading">詳細</th>
            </tr>
        </thead>
        <tbody class="table-body">
            @foreach($attendances as $attendance)
                @php
                    $breakMinutes = $attendance->breakTimes->sum(function($b){
                        if ($b->break_start && $b->break_end) {
                            return \Carbon\Carbon::parse($b->break_end)
                                ->diffInMinutes(\Carbon\Carbon::parse($b->break_start));
                        }
                        return 0;
                    });

                    $workMinutes = 0;

                    if ($attendance->clock_in && $attendance->clock_out) {
                        $workMinutes = \Carbon\Carbon::parse($attendance->clock_out)
                            ->diffInMinutes(\Carbon\Carbon::parse($attendance->clock_in))
                            - $breakMinutes;
                    }
                @endphp
                <tr class="table-data-row">
                    <td class="table-data">{{ $attendance->user->name }}</td>
                    <td class="table-data">{{ $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '' }}</td>
                    <td class="table-data">{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '' }}</td>
                    <td class="table-data">{{ floor($breakMinutes / 60) }}:{{ str_pad($breakMinutes % 60, 2, '0', STR_PAD_LEFT) }}</td>
                    <td class="table-data">{{ floor($workMinutes / 60) }}:{{ str_pad($workMinutes % 60, 2, '0', STR_PAD_LEFT) }}</td>
                    <td class="table-data">
                        <a href="/admin/attendance/detail/{{ $attendance->id }}" class="detail-link">詳細</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection