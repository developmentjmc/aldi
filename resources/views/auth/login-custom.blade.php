@php
	$appName = env('APP_NAME', 'Kepegawaian JMC');
	$clientName = env('CLIENT_NAME', 'JMC');
@endphp

@extends('layouts/auth', get_defined_vars())

@section('content')
	<div class="w-100 mb-4">
	    <div class="text-center">
	        <h1 class="mb-0 text-navy">Login {{ $appName }}</h1>
	    </div>
	</div>

	<p class="text-center">
		Selamat datang, silakan masuk menggunakan akun anda untuk masuk ke sistem.
	</p>

	@if ($errors->any())
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
    @endif

	<form action="{{ route('login') }}" method="POST" id="login-form" autocomplete="off">
	    @csrf

	    <div class="mb-2">
	        <input type="text" class="form-control py-3" placeholder="username/email/telepon ..." name="email" required>
	        <span class="invalid-feedback"></span>
	    </div>
	    <div class="mb-2">
	        <input type="password" class="form-control py-3 input-password mb-1" name="password" placeholder="password ..." required>
	        <span class="invalid-feedback"></span>
	    </div>
        <div class="mb-2">
			<div class="form-check">
				<input class="form-check-input" type="checkbox" id="remember_me" name="remember" value="1">
				<label class="form-check-label" for="remember_me">
					{{ __('Remember me') }}
				</label>
			</div>
			<small>* Untuk telepon gunakan format internasional tanpa tanda +, misal 62812345678111</small>
		</div>
	    <div class="d-grid mt-4">
	        <button class="btn btn-blue text-uppercase shadow py-3" type="submit">Masuk</button>
	    </div>
	</form>
@endsection
