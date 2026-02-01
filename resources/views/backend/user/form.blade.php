@php

$title = 'Form User';
$indexHref = route('backend.user.index');
$breadcrumbs[] = 'User';

if (isset($model->id)) {
	$submitHref = route('backend.user.update', ['user' => $model->id]);
	$breadcrumbs[] = 'Update';
} else {
	$submitHref = route('backend.user.store');
	$breadcrumbs[] = 'Create';
}

$roleOptions = jeemce\models\Role::options('id','name');

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
		<div class="card-body row">
			<div class="col-md-12 mb-3">
				<label for="" class="form-label">Pilih Pegawai</label>
				<select name="employee" id="">
					<option value="">-- Pilih Pegawai --</option>
					@foreach ($employees as $item)
						<option value="{{ $item->id }}">{{ $item->name }}</option>
					@endforeach
				</select>
			</div>
			<fieldset class="col-md-6">
				<div class="col mb-3">
					<label class="form-label required">Name</label>
					<input name="name" class="form-control" readonly>
					<div class="invalid-feedback"></div>
				</div>

				<div class="col mb-3">
					<label class="form-label required">Username</label>
					<input name="username" class="form-control">
					<div class="invalid-feedback"></div>
				</div>

				<div class="col mb-3">
					<label class="form-label required">Phone</label>
					<input name="phone" type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="form-control">
					<div class="invalid-feedback"></div>
				</div>

				<div class="col mb-3">
					<label class="form-label required">Email</label>
					<input name="email" class="form-control" readonly>
					<div class="invalid-feedback"></div>
				</div>
				
			</fieldset>

			<fieldset class="col-md-6">

				<div class="col mb-3">
					<label class="form-label required">Password</label>
					<input type="password" name="password" class="form-control">
					<div class="invalid-feedback"></div>
				</div>

				<div class="col mb-3">
					<label class="form-label required">Password Confirmation</label>
					<input type="password" name="password_confirmation" class="form-control">
					<div class="invalid-feedback"></div>
				</div>

				<div class="col mb-3">
					<label class="form-label required">Role</label>
					{{ HtmlHelper::select([
						'name' => 'id_role',
						'options' => $roleOptions,
						'class' => 'form-select',
						'required' => 'required',
					]) }}
					<div class="invalid-feedback"></div>
				</div>

				<div class="col mb-3">
					<label class="form-label required">Status</label>
					@if (strtolower($model->name) == 'admin')
						<div style="pointer-events: none;">
							{{ HtmlHelper::select([
								'name' => 'status',
								'options' => $model->statusOptions(),
								'class' => 'form-select',
								'required' => 'required',
							]) }}
						</div>
					@else
						{{ HtmlHelper::select([
							'name' => 'status',
							'options' => $model->statusOptions(),
							'class' => 'form-select',
							'required' => 'required',
						]) }}
					@endif
					<div class="invalid-feedback"></div>
				</div>

				<div class="col text-end">
					<label class="form-label">&nbsp;</label>
					<a href="{{ $indexHref }}" class="btn btn-danger">Batal</a>
					&nbsp;
					<button type="submit" class="btn btn-success">Simpan</button>
				</div>
			</fieldset>
		</div>
	</div>
</form>
@endsection

@section('scripts')
	<script id="data-employees" type="application/json">
		{!! $employees->toJson(JSON_FORCE_OBJECT) !!}
	</script>

	<script id="data" type="application/json">
		{!! $model->toJson(JSON_FORCE_OBJECT) !!}
	</script>

	<script type="module">
		const employeeSelect = new Choices('select[name="employee"]', {
			searchEnabled: true,
			searchPlaceholderValue: 'Cari...',
			noResultsText: 'Tidak ada data',
			itemSelectText: 'Pilih',
		});

		// on change employee select
		employeeSelect.passedElement.element.addEventListener('change', function(event) {
			const selectedEmployeeId = event.target.value;
			const el = document.getElementById('data-employees');
    		const employeesData = JSON.parse(el.textContent);

			if (employeesData[selectedEmployeeId]) {
				const selectedEmployee = employeesData[selectedEmployeeId];
				document.querySelector('input[name="name"]').value = selectedEmployee.name || '';
				document.querySelector('input[name="phone"]').value = selectedEmployee.no_hp || '';
				document.querySelector('input[name="email"]').value = selectedEmployee.email || '';
			} else {
				document.querySelector('input[name="name"]').value = '';
				document.querySelector('input[name="phone"]').value = '';
				document.querySelector('input[name="email"]').value = '';
			}
		});

		// preset
		const dataJson = jsonScriptToFormFields('#form', '#data');

		$('#form').formAjaxSubmit();
	</script>
@endsection
