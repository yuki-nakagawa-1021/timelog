@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/detail.css') }}">
@endsection

@section('content')
<div class="detail-wrapper">
    <h2 class="detail-title">勤怠詳細</h2>
    <form method="POST" action="/admin/attendance/request/{{ $attendance->id }}">
        @csrf
        <div class="detail-card">
            <div class="detail-row">
                <div class="detail-label">名前</div>
                <div class="detail-value">
                    {{ $attendance->user->name }}
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">日付</div>
                <div class="detail-value">
                    <span class="date-year">
                        {{ \Carbon\Carbon::parse($date)->format('Y年') }}
                    </span>
                    <span class="date-day">
                        {{ \Carbon\Carbon::parse($date)->format('n月j日') }}
                    </span>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">出勤・退勤</div>
                <div class="detail-value">
                    <input type="time" name="clock_in" class="time-input"
                        value="{{ $attendance && $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '' }}">
                    <span class="time-separator">〜</span>
                    <input type="time" name="clock_out" class="time-input"
                        value="{{ $attendance && $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '' }}">
                </div>
            </div>
            @php
                $breakTimes = $attendance->breakTimes ?? collect();
                $displayIndex = 1;
            @endphp
            @foreach($breakTimes as $break)
                @if($break->break_start || $break->break_end)
                    <div class="detail-row">
                        <div class="detail-label">
                            休憩{{ $displayIndex === 1 ? '' : $displayIndex }}
                        </div>
                        <div class="detail-value">
                            <input type="time" name="break_start[]" class="time-input"
                                value="{{ $break->break_start ? \Carbon\Carbon::parse($break->break_start)->format('H:i') : '' }}">
                            <span class="time-separator">〜</span>
                            <input type="time" name="break_end[]" class="time-input"
                                value="{{ $break->break_end ? \Carbon\Carbon::parse($break->break_end)->format('H:i') : '' }}">
                        </div>
                    </div>
                    @php $displayIndex++; @endphp
                @endif
            @endforeach
            <div class="detail-row">
                <div class="detail-label">
                    休憩{{ $displayIndex === 1 ? '' : $displayIndex }}
                </div>
                <div class="detail-value">
                    <input type="text" name="break_start[]" class="time-input">
                    <span class="time-separator">〜</span>
                    <input type="text" name="break_end[]" class="time-input">
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">備考</div>
                <div class="detail-value">
                    <textarea name="note" class="note-input">{{ optional($attendance)->note }}</textarea>
                </div>
            </div>
        </div>
        <div class="button-area">
            <button class="submit-btn">修正</button>
        </div>
    </form>
</div>
@endsection