@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/request_list.css') }}">
@endsection

@section('content')
<div class="detail-wrapper">
    <h2 class="detail-title">勤怠詳細</h2>
    @foreach($pendingRequests as $request)
    <div class="detail-card">
        <div class="detail-row">
            <div class="detail-label">名前</div>
            <div class="detail-value">
                {{ Auth::user()->name }}
            </div>
        </div>
        <div class="detail-row">
            <div class="detail-label">日付</div>
            <div class="detail-value">
                {{ \Carbon\Carbon::parse($request->attendance->date)->format('Y年 n月j日') }}
            </div>
        </div>
        <div class="detail-row">
            <div class="detail-label">出勤・退勤</div>
            <div class="detail-value">
                {{ $request->attendance->clock_in ? \Carbon\Carbon::parse($request->attendance->clock_in)->format('H:i') : '' }}
                〜
                {{ $request->attendance->clock_out ? \Carbon\Carbon::parse($request->attendance->clock_out)->format('H:i') : '' }}
            </div>
        </div>
        @foreach($request->attendance->breakTimes as $i => $break)
        <div class="detail-row">
            <div class="detail-label">
                休憩{{ $i === 0 ? '' : $i+1 }}
            </div>
            <div class="detail-value">
                {{ $break->break_start ? \Carbon\Carbon::parse($break->break_start)->format('H:i') : '' }}
                〜
                {{ $break->break_end ? \Carbon\Carbon::parse($break->break_end)->format('H:i') : '' }}
            </div>
        </div>
        @endforeach
        <div class="detail-row">
            <div class="detail-label">備考</div>
            <div class="detail-value">
                {{ $request->reason }}
            </div>
        </div>
    </div>
    <p class="pending-message">
        ※承認待ちのため修正はできません。
    </p>
    @endforeach
</div>
@endsection