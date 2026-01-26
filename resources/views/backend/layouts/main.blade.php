@php
    $appName = env('APP_NAME');
    $appSort = env('APP_NAME_SORT', $appName);
    $appYear = env('APP_YEAR', date('Y'));
    $clientName = env('CLIENT_NAME', $appName);

    $title ??= env('APP_NAME');
    $pageMenu ??= 'Dashboard';
    $pageSubMenu ??= '';
    $pageTitle ??= $title;
    $breadcrumbs ??= [];
@endphp

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-copyright="JMC Indonesia">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ $title }}</title>
    @vite('resources/assets/backend.less')

    @yield('styles')
</head>

<body>
    <div class="page">
        <!-- SIDEBAR -->
        @include('backend.layouts.main_sidebar', get_defined_vars())
        <!-- END SIDEBAR -->

        <!-- HEADER -->
        @include('backend.layouts.main_navbar', get_defined_vars())
        <!-- END HEADER -->

        <div class="page-wrapper" data-menu-active="{{ $pageMenu }}" data-submenu-active="{{ $pageSubMenu }}">
            @sectionMissing('header')
                <div class="page-header d-print-none">
                    <div class="container-xl">
                        <div class="row g-2 align-items-center">
                            @sectionMissing('header_left')
                                <div class="col">
                                    <div class="page-pretitle mb-2">
                                        <ol class="breadcrumb" aria-label="breadcrumbs">
                                            @foreach ($breadcrumbs as $breadcrumb)
                                                <li class="breadcrumb-item active">
                                                    @if (is_string($breadcrumb))
                                                        <a href="#">{{ $breadcrumb }}</a>
                                                    @else
                                                        <a
                                                            href="{{ $breadcrumb['href'] }}">{{ $breadcrumb['text'] }}</a>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ol>
                                    </div>

                                    <h2 class="page-title">
                                        {{ $pageTitle }}
                                    </h2>
                                </div>
                            @endif

                            @sectionMissing('header_right')
                                <!-- Page title actions -->
                                <div class="col-auto ms-auto d-print-none">
                                    @yield('header_right_content')
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="page-body">
                <div class="container-xl">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <div id="modal-form-ajax" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"></div>
        </div>
    </div>

    @include('backend.partials.modals')

    @vite('resources/assets/backend.ts')

    @yield('scripts')
</body>

</html>
