@php
$viewHref = route('backend.user.show', ['user' => AuthHelper::id()]);
$changePasswordHref = route('backend.user.changePassword');
@endphp

<header class="navbar navbar-expand-lg d-print-none sticky-top" id="navbar">
    <div class="container-xl justify-content">
        <button class="sidebar-toggler d-none d-lg-block" type="button">
            <span class="sidebar-icon"></span>
        </button>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar-menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-nav flex-row order-md-last ms-md-auto">
			<!-- Dark Mode Button -->
			<button class="nav-link px-0 btn-toggle-theme hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip" data-bs-placement="bottom" type="button">
				<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
					<path stroke="none" d="M0 0h24v24H0z" fill="none"/>
					<path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" />
				</svg>
			</button>
		
			<!-- Light Mode Button -->
			<button class="nav-link px-0 btn-toggle-theme hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip" data-bs-placement="bottom" type="button">
				<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
					<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
					<path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
					<path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7"></path>
				</svg>
			</button>
		
			<!-- Notifications Dropdown -->
			<div class="nav-item dropdown">
				@include('backend.layouts.main_navbar_notify')
			</div>
		
			<!-- User Dropdown -->
			<div class="nav-item dropdown">
				<a href="#" class="nav-link d-flex lh-1 text-reset p-0 dropdown-toggle" data-bs-toggle="dropdown">
					<span class="bg-primary text-white avatar rounded-circle">
						AD
					</span>
					<div class="d-none d-xl-block ps-2">
						<div class="fw-bold">{{ AuthHelper::user()?->name }}</div>
						<div class="mt-1 small text-primary">{{ auth()->user()->roleModel?->name }}</div>
					</div>
				</a>
				<div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
					{{-- <a class="dropdown-item" href="{{ $viewHref }}">
						<i class="bi bi-person me-2"></i> My Profile
					</a>
					<a class="dropdown-item" href="{{ $changePasswordHref }}">
						<i class="bi bi-key me-2"></i> Change Password
					</a> --}}
					<form method="POST" action="{{ route('logout') }}" class="dropdown-item">
						@csrf
						<button type="submit" class="d-block btn btn-danger w-100">
							<i class="bi bi-box-arrow-right me-2"></i> Logout
						</button>
					</form>
				</div>
			</div>
		</div>
		
    </div>
</header>
