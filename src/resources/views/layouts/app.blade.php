<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TimeLog</title>
    <link rel="stylesheet" href="{{ (asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ (asset('css/common.css') }}">
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <a class="header__logo-link" href="/">
                <img class="header__logo" src="{{ asset('img/COACHTECHヘッダーロゴ.png') }}" alt="coachtechロゴ">
            </a>
            <nav>
                <ul class="header-nav">
                    <li class="header-nav__item">
                        <a class="header-nav__" href=" ">勤怠</a>
                    </li>
                    <li class="header-nav__item">
                        <a class="header-nav__" href=" ">勤怠一覧</a>
                    </li>
                    <li class="header-nav__item">
                        <a class="header-nav__" href=" ">申請</a>
                    </li>
                    <li class="header-nav__item">
                        <form class="header-nav__form" action="/logout" method="POST">
                            @csrf
                            <button class="header-nav__button" type="submit">ログアウト</button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
</body>

</html>