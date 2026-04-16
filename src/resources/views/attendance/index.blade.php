@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.index') }}">
@endsection

@section('content')
<div>
    <h2>
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
    <p>{{ now()->format('Y年m月d日') }}</p>
    <p>{{ now()->format('H:i') }}</p>
    <div>
        @if($status === 'before')
            <form method="POST" action="/attendance/start">
                @csrf
                <button type="submit">出勤</button>
            </form>
        @endif
        @if($status === 'working')
            <form method="POST" action="/attendance/break/start">
                @csrf
                <button type="submit">休憩入</button>
            </form>
            <form method="POST" action="/attendance/end">
                @csrf
                <button type="submit">退勤</button>
            </form>
        @endif
        @if($status === 'break')
            <form method="POST" action="/attendance/break/end">
                @csrf
                <button type="submit">休憩戻</button>
            </form>
        @endif
    </div>
    @if($status === 'done')
        <p>お疲れ様でした。</p>
    @endif
</div>
@endsection