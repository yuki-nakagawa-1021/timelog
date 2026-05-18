@extends('layouts.admin')

@section('content')

<div class="detail-wrapper">

    <h2 class="detail-title">勤怠詳細</h2>
    <div class="detail-card">
        <div class="detail-row">
            <div class="detail-label">名前</div>
            <div class="detail-value">
                {{ $request->user->name }}
            </div>
        </div>
        <div class="detail-row">
            <div class="detail-label">対象日時</div>
            <div class="detail-value">
                {{ \Carbon\Carbon::parse($request->attendance->date)->format('Y年n月j日') }}
            </div>
        </div>
        @foreach($request->items as $item)
            <div class="detail-row">
                <div class="detail-label">
                    @if($item->field === 'clock_in')
                        出勤時間
                    @elseif($item->field === 'clock_out')
                        退勤時間
                    @else
                        {{ $item->field }}
                    @endif
                </div>
                <div class="detail-value">
                    {{ $item->new_value }}
                </div>
            </div>
        @endforeach
        <div class="detail-row">
            <div class="detail-label">申請理由</div>
            <div class="detail-value">
                {{ $request->reason }}
            </div>
        </div>
    </div>
    @if($request->status === 'pending')
        <form method="POST"
            action="/admin/stamp_correction_request/approve/{{ $request->id }}">
            @csrf
            <div class="button-area">
                <button type="submit" class="approve-btn">
                    承認
                </button>
            </div>
        </form>
    @else
        <p class="approved-message">
            承認済み
        </p>
    @endif
</div>

@endsection