<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TimeLog</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <a class="header__logo-link" href="/">
                <img class="header__logo" src="{{ asset('img/logo.png') }}" alt="coachtechロゴ">
            </a>
            @auth
            @if(!request()->is('login') && !request()->is('admin/login'))
                <nav class="header-nav">
                    @if(auth()->user()->role === 'admin')
                        <a class="header-nav__link" href="/admin/attendance/list">勤怠一覧</a>
                        <a class="header-nav__link" href="/admin/staff/list">スタッフ一覧</a>
                        <a class="header-nav__link" href="/admin/stamp_correction_request/list">申請一覧</a>
                        <form action="/admin/logout" method="POST">
                            @csrf
                            <button type="submit">ログアウト</button>
                        </form>
                    @else
                        <a class="header-nav__link" href="/attendance">勤怠</a>
                        <a class="header-nav__link" href="/attendance/list">勤怠一覧</a>
                        <a class="header-nav__link" href="/stamp_correction_request/list">申請</a>
                        <form action="/logout" method="POST">
                            @csrf
                            <button class=header-nav__button type="submit">ログアウト</button>
                        </form>
                    @endif
                </nav>
            @endif
        @endauth
        </div>
    </header>
    <main>
        @yield('content')
    </main>
</body>

</html>