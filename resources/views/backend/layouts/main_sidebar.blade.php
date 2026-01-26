<?php
$menuTree = \jeemce\models\Menu::tree([
    'id_menu' => null,
    'status' => 'publish',
]);
?>

<aside class="navbar navbar-vertical navbar-expand-lg navbar-dark sidebar" data-bs-theme="dark">
    <div class="container-fluid px-0 justify-content-start">
        <h1 class="navbar-brand text-white ms-3 ms-lg-0 gap-3">
            <div class="logo">
                <img src="{{ asset('assets/img/logo.png') }}" alt="" height="30">
            </div>
            <a href="{{ route('backend.home.index') }}" target="_blank" class="fw-bold hstack gap-3 text-decoration-none">
                <div style="font-size: .9rem;">{{ env('APP_NAME') }}</div>
            </a>
        </h1>
        <div class="offcanvas offcanvas-start px-lg-3" id="sidebar-menu">
            <div class="offcanvas-header">
                <div class="d-flex gap-3 align-items-center">
                    <div class="image">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="" height="15">
                    </div>
                    <div class="logo-text flex-grow-1">
                        <h3 class="m-0"></h3>
                        <div class="fs-4 fw-bold">JMC CMS Laravel</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-3 p-lg-0 flex-column flex-grow-1 overflow-auto">
                <ul class="navbar-nav align-items-start mt-lg-3">
                    <?php foreach ($menuTree as $menu) { ?>
                    @include('backend/layouts/main_sidebar_entry', get_defined_vars())
                    <?php } ?>
                </ul>

            </div>
        </div>
    </div>
</aside>
