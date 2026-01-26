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
		Masukan kode OTP yang telah dikirim ke email anda {{ auth()->user()->email }}.
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

	<form action="{{ route('otp.verify') }}" method="POST" autocomplete="off">
	    @csrf
        <div class="mb-2">
			<input type="text" class="form-control py-3" placeholder="kode OTP" name="code" required>
	        <span class="invalid-feedback"></span>
		</div>
	    <div class="d-grid mt-4">
	        <button class="btn btn-blue text-uppercase shadow py-3" type="submit">Submit</button>
	    </div>
	</form>
@endsection
