@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance/request_list.css') }}">
@endsection

@section('content')

<div class="request-wrapper">
    <h2 class="request-title">申請一覧</h2>
    <div class="request-tab">
        <a href="/stamp_correction_request/list?status=pending" class="{{ request('status', 'pending') === 'pending' ? 'active-tab' : '' }}">
            承認待ち
        </a>
        <a href="/stamp_correction_request/list?status=approved" class="{{ request('status') === 'approved' ? 'active-tab' : '' }}">
            承認済み
        </a>
    </div>
    <table class="request-table">
        <thead>
            <tr>
                <th>状態</th>
                <th>名前</th>
                <th>対象日時</th>
                <th>申請理由</th>
                <th>申請日時</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @php
                $status = request('status', 'pending');

                $requests =
                    $status === 'approved'
                    ? $approvedRequests
                    : $pendingRequests;
            @endphp
            @foreach($requests as $request)
            <tr>
                <td>{{ $request->status === 'pending' ? '承認待ち' : '承認済み' }}</td>
                <td>{{ $request->user->name }}</td>
                <td>{{ \Carbon\Carbon::parse($request->attendance->date)->format('Y/m/d') }}</td>
                <td>{{ $request->reason }}</td>
                <td>{{ $request->created_at->format('Y/m/d') }}</td>
                <td>
                    <a href="/attendance/detail/{{ $request->attendance->id }}" class="detail-link">
                        詳細
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection