@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/staff.css') }}">
@endsection

@section('content')

<div class="staff-wrapper">
    <h2 class="staff-title">スタッフ一覧</h2>
    <table class="staff-table">
        <thead>
            <tr class="staff-table__row">
                <th class="staff-table__header">名前</th>
                <th class="staff-table__header">メールアドレス</th>
                <th class="staff-table__header">月次勤怠</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr class="staff-table__row">
                    <td class="staff-table__data">{{ $user->name }}</td>
                    <td class="staff-table__data">{{ $user->email }}</td>
                    <td class="staff-table__data">
                        <a href="/admin/attendance/list?user_id={{ $user->id }}" class="staff-table__link">詳細</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection