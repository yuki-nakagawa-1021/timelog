@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/list.css') }}">
@endsection

@section('content')
<div class="attendance-wrapper">

    <div class="attendance-card">
        <h2 class="attendance-title">勤怠一覧</h2>

        <div class="month-nav">
            <a class="month-btn" href="/attendance/list?month={{ \Carbon\Carbon::parse($month)->subMonth()->format('Y-m') }}">← 前月</a>
            <div class="month-display">
                <form method="GET" action="/attendance/list" class="month-form">
                    <input class="month-input" type="month" name="month" value="{{ $month }}" onchange="this.form.submit()">
                </form>
                {{ \Carbon\Carbon::parse($month)->format('Y/m') }}
            </div>
            <a class="month-btn" href="/attendance/list?month={{ \Carbon\Carbon::parse($month)->addMonth()->format('Y-m') }}">翌月 →</a>
        </div>
        <table class="attendance-table">
            <thead class="table-head">
                <tr class="table-row table-row-head">
                    <th class="table-header">日付</th>
                    <th class="table-header">出勤</th>
                    <th class="table-header">退勤</th>
                    <th class="table-header">休憩</th>
                    <th class="table-header">合計</th>
                    <th class="table-header">詳細</th>
                </tr>
            </thead>
            <tbody class="table-body">
                @foreach ($dates as $date)
                    @php
                        $key = $date->format('Y-m-d');
                        $attendance = $attendances[$key] ?? null;
                    @endphp
                    <tr class="table-row">
                        <td class="table-cell">{{ $date->format('m/d') }}（{{ $date->isoFormat('ddd') }}）</td>
                        <td class="table-cell">
                            {{ $attendance && $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '' }}
                        </td>
                        <td class="table-cell">
                            {{ $attendance && $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '' }}
                        </td>
                        <td class="table-cell">
                            @if($attendance && $attendance->breakTimes->count())
                                @php
                                    $totalMinutes = 0;
                                    foreach ($attendance->breakTimes as $break) {
                                        if ($break->break_start && $break->break_end) {
                                            $start = \Carbon\Carbon::parse($break->break_start);
                                            $end = \Carbon\Carbon::parse($break->break_end);
                                            $totalMinutes += $start->diffInMinutes($end);
                                        }
                                    }
                                    $hours = floor($totalMinutes / 60);
                                    $mins = $totalMinutes % 60;
                                @endphp
                                {{ sprintf('%02d:%02d', $hours, $mins) }}
                            @endif
                        </td>
                        <td class="table-cell">
                            @if($attendance && $attendance->clock_in && $attendance->clock_out)
                                @php
                                    $start = \Carbon\Carbon::parse($attendance->clock_in);
                                    $end = \Carbon\Carbon::parse($attendance->clock_out);
                                    $workMinutes = $start->diffInMinutes($end);
                                    $breakMinutes = 0;
                                    foreach ($attendance->breakTimes as $break) {
                                        if ($break->break_start && $break->break_end) {
                                            $bStart = \Carbon\Carbon::parse($break->break_start);
                                            $bEnd = \Carbon\Carbon::parse($break->break_end);
                                            $breakMinutes += $bStart->diffInMinutes($bEnd);
                                        }
                                    }
                                    $actualMinutes = $workMinutes - $breakMinutes;
                                    $actualMinutes = max($actualMinutes, 0);
                                    $hours = floor($actualMinutes / 60);
                                    $mins = $actualMinutes % 60;
                                @endphp
                                {{ sprintf('%02d:%02d', $hours, $mins) }}
                            @endif
                        </td>
                        <td class="table-cell">
                            @if(!empty($attendance))
                                <a href="/attendance/detail/{{ $attendance->id }}" class="detail-link">
                                    詳細
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection