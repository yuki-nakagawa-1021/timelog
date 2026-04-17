@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/index.css') }}">
@endsection

@section('content')
<div class="attendance-container">
    <div class="attendance-card">
        <h2 class="attendance-status">
            @if($status === 'before')
                勤務外
            @elseif($status === 'working')
                出勤中
            @elseif($status === 'break')
                休憩中
            @elseif($status === 'done')
                退勤済
            @endif
        </h2>
        <p class="attendance-date">{{ now()->format('Y年n月j日') }}（{{ ['日','月','火','水','木','金','土'][now()->dayOfWeek] }}）</p>
        <p class="attendance-time">{{ now()->format('H:i') }}</p>
        <div class="attendance-actions">
            @if($status === 'before')
                <form method="POST" action="/attendance/start">
                    @csrf
                    <button class="btn_start" type="submit">出勤</button>
                </form>
            @endif

            @if($status === 'working')
                <form method="POST" action="/attendance/break/start">
                    @csrf
                    <button class="btn-break_start" type="submit">休憩入</button>
                </form>
                <form method="POST" action="/attendance/end">
                    @csrf
                    <button class="btn_end" type="submit">退勤</button>
                </form>
            @endif

            @if($status === 'break')
                <form method="POST" action="/attendance/break/end">
                    @csrf
                    <button class="btn-break_end" type="submit">休憩戻</button>
                </form>
            @endif
        </div>
        @if($status === 'done')
            <p class="attendance-message">お疲れ様でした。</p>
        @endif

    </div>
</div>
@endsection