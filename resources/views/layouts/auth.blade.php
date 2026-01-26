@php
$appName = env('APP_NAME', 'Kepegawaian JMC');
$lang = str_replace('_', '-', app()->getLocale());
@endphp

<!doctype html>
<html lang="{{ $lang }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title><?= $appName ?></title>

    <link rel="icon" href="">

    @vite([
        'resources/assets/backend.less',
        'resources/assets/login.css',
    ])
</head>

<body class="d-flex flex-column">
    <div class="login-cover bg-dark position-fixed swiper" style="inset:0;">
        <img src="{{ asset('static/login/bg-1.jpg') }}" alt="" class="cover">
    </div>

    <div class="page page-center position-relative" style="z-index: 10;">
        <div class="container container-tight py-4">
            <div class="card card-md card-loginbox" style="background:#fff;border-radius: 30px;">
                <div class="card-body">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    @vite([
        'resources/assets/tabler.min.js',
        'resources/assets/backend.js',
        'resources/assets/app.js',
    ])
</html>
