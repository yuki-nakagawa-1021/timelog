@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">
@endsection

@section('content')
<div class="verify-email">
    <div class="verify-email__content">
        <p class="verify-email__text">登録していただいたメールアドレスに認証メールを送付しました。</p>
        <p class="verify-email__text">メール認証を完了して下さい。</p>
        <div class="verify-email__button-wrapper">
            <a class="verify-email__link" href="http://localhost:8025" target="_blank" rel="noopener noreferrer">認証はこちらから</a>
        </div>
        <form class="verify-email__form" action="/email/verification-notification" method="POST">
            @csrf
            <button class="verify-email__button" type="submit">認証メールを再送する</button>
        </form>
    </div>
</div>
@endsection