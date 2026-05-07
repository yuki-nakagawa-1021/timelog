@extends('layouts.app')

@section('content')
<div class="admin-wrapper">
    <h2 class="admin-title">スタッフ一覧</h2>
    <table class="admin-table">
        <tr>
            <th>名前</th>
            <th>メール</th>
            <th>詳細</th>
        </tr>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                <a href="/admin/attendance?user_id={{ $user->id }}">勤怠</a>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection