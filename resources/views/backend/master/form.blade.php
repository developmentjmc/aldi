@php

$title = 'Form Data Master';
$indexHref = route('backend.master.index');
$breadcrumbs[] = 'Data Master';

if (isset($model->id)) {
	$submitHref = route('backend.master.update', ['master' => $model->id]);
	$breadcrumbs[] = 'Update';
} else {
	$submitHref = route('backend.master.store');
	$breadcrumbs[] = 'Create';
}
@endphp

@extends('backend/layouts/main', get_defined_vars())

@section('content')
<form method="POST" action="{{ $submitHref }}" enctype="multipart/form-data" id="form">
	@csrf
	@if ($model->id)
		@method('put')
	@endif

	@if ($errors->any())
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	<div class="card mb-5">
		<div class="card-body">
			<div class="row">
				<div class="col-md-6 mb-3">
					<label class="form-label required">Key</label>
					<input type="text" name="name" class="form-control" value="{{ old('name', $model->name ?? '') }}">
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-6 mb-3">
					<label class="form-label required">Value</label>
					<input type="text" name="description" class="form-control" value="{{ old('description', $model->description ?? '') }}">
					<div class="invalid-feedback"></div>
				</div>
			</div>

			<div class="row">
				<div class="col text-start">
					<a href="{{ $indexHref }}" class="btn btn-danger">Batal</a>
					&nbsp;
					<button type="submit" class="btn btn-success">Simpan</button>
				</div>
			</div>
			
		</div>
	</div>
</form>
@endsection

@section('scripts')
	<script id="data" type="application/json">
		{!! $model->toJson(JSON_FORCE_OBJECT) !!}
	</script>

	<script type="module">
		// jmc preset 
		const dataJson = jsonScriptToFormFields('#form', '#data');

		$('#form').formAjaxSubmit();
	</script>
@endsection
