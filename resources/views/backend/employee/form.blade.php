@php

$title = 'Form Pegawai';
$indexHref = route('backend.pegawai.index');
$breadcrumbs[] = 'Pegawai';

if (isset($model->id)) {
	$submitHref = route('backend.pegawai.update', ['pegawai' => $model->id]);
	$breadcrumbs[] = 'Update';
} else {
	$submitHref = route('backend.pegawai.store');
	$breadcrumbs[] = 'Create';
}

$jabatanOptions = \App\Enums\JabatanEnum::options();
$jenisPegawai = \App\Enums\JenisPegawaiEnum::options();
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
			<!-- Data Pribadi -->
			<h5 class="mb-3">Data Pribadi</h5>
			<div class="row">
				<div class="col-md-4 mb-3">
					<label class="form-label required">NIP</label>
					<input type="text" name="nip" class="form-control" value="{{ old('nip', $model->nip ?? '') }}">
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-4 mb-3">
					<label class="form-label required">Nama Pegawai</label>
					<input type="text" name="name" class="form-control" value="{{ old('name', $model->name ?? '') }}">
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-4 mb-3">
					<label class="form-label required">Email</label>
					<input type="email" name="email" class="form-control" value="{{ old('email', $model->email ?? '') }}">
					<div class="invalid-feedback"></div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4 mb-3">
					<label class="form-label required">No. HP</label>
					<div class="input-group">
						<span class="input-group-text">+62</span>
						<input type="text" name="no_hp" class="form-control" placeholder="82218458888" value="{{ old('no_hp', $model->no_hp ?? '') }}">
					</div>
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-4 mb-3">
					<label class="form-label required">Tanggal Lahir</label>
					<input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $model->tanggal_lahir ?? '') }}">
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-4 mb-3">
					<label class="form-label required">Tempat Lahir</label>
					<select name="tempat_lahir_kabupaten_id" class="form-control">
						<option value="">-- Pilih Kabupaten/Kota --</option>
						@foreach (\App\Helpers\DataHelper::getWilayah('kabupaten') as $item)
							<option value="{{ $item->id }}" {{ old('tempat_lahir_kabupaten_id', $model->tempat_lahir_kabupaten_id ?? '') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
						@endforeach
					</select>
					<div class="invalid-feedback"></div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4 mb-3">
					<label class="form-label required">Status Kawin</label>
					<select name="status_kawin" class="form-control">
						<option value="">-- Pilih Status --</option>
						<option value="Belum Kawin" {{ old('status_kawin', $model->status_kawin ?? '') == 'Belum Kawin' ? 'selected' : '' }}>Belum Kawin</option>
						<option value="Kawin" {{ old('status_kawin', $model->status_kawin ?? '') == 'Kawin' ? 'selected' : '' }}>Kawin</option>
						<option value="Cerai Hidup" {{ old('status_kawin', $model->status_kawin ?? '') == 'Cerai Hidup' ? 'selected' : '' }}>Cerai Hidup</option>
						<option value="Cerai Mati" {{ old('status_kawin', $model->status_kawin ?? '') == 'Cerai Mati' ? 'selected' : '' }}>Cerai Mati</option>
					</select>
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-4 mb-3">
					<label class="form-label">Jumlah Anak</label>
					<input type="number" name="jumlah_anak" class="form-control" value="{{ old('jumlah_anak', $model->jumlah_anak ?? 0) }}" min="0">
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-4 mb-3">
					<label class="form-label">Usia</label>
					<input type="number" name="usia" class="form-control" value="{{ old('usia', $model->usia ?? '') }}" readonly>
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-4 mb-3">
					<label class="form-label required">Pendidikan</label>
					<select name="pendidikan[]" class="form-select" multiple="multiple">
						<option value="SD" {{ in_array('SD', old('pendidikan', is_array($model->pendidikan ?? []) ? $model->pendidikan : json_decode($model->pendidikan ?? '[]', true))) ? 'selected' : '' }}>SD</option>
						<option value="SMP" {{ in_array('SMP', old('pendidikan', is_array($model->pendidikan ?? []) ? $model->pendidikan : json_decode($model->pendidikan ?? '[]', true))) ? 'selected' : '' }}>SMP</option>
						<option value="SMA" {{ in_array('SMA', old('pendidikan', is_array($model->pendidikan ?? []) ? $model->pendidikan : json_decode($model->pendidikan ?? '[]', true))) ? 'selected' : '' }}>SMA</option>
						<option value="D3" {{ in_array('D3', old('pendidikan', is_array($model->pendidikan ?? []) ? $model->pendidikan : json_decode($model->pendidikan ?? '[]', true))) ? 'selected' : '' }}>D3</option>
						<option value="S1" {{ in_array('S1', old('pendidikan', is_array($model->pendidikan ?? []) ? $model->pendidikan : json_decode($model->pendidikan ?? '[]', true))) ? 'selected' : '' }}>S1</option>
					</select>
					<div class="invalid-feedback"></div>
				</div>
			</div>

			<!-- Data Kepegawaian -->
			<h5 class="mb-3 mt-4">Data Kepegawaian</h5>
			<div class="row">
				<div class="col-md-4 mb-3">
					<label class="form-label required">Tanggal Masuk</label>
					<input type="date" name="tanggal_masuk" class="form-control" value="{{ old('tanggal_masuk', $model->tanggal_masuk ?? '') }}">
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-4 mb-3">
					<label class="form-label required">Jabatan</label>
					<select name="jabatan" class="form-control">
						<option value="">-- Pilih Jabatan --</option>
						@foreach($jabatanOptions as $value => $label)
							<option value="{{ $value }}" {{ old('jabatan', $model->jabatan ?? '') == $value ? 'selected' : '' }}>{{ $label }}</option>
						@endforeach
					</select>
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-4 mb-3">
					<label class="form-label required">Jenis Pegawai</label>
					<select name="jenis_pegawai" class="form-control">
						<option value="">-- Pilih Jenis Pegawai --</option>
						@foreach($jenisPegawai as $value => $label)
							<option value="{{ $value }}" {{ old('jenis_pegawai', $model->jenis_pegawai ?? '') == $value ? 'selected' : '' }}>{{ $label }}</option>
						@endforeach
					</select>
					<div class="invalid-feedback"></div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4 mb-3">
					<label class="form-label required">Departemen</label>
					<input type="text" name="departemen" class="form-control" value="{{ old('departemen', $model->departemen ?? '') }}">
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-4 mb-3">
					<label class="form-label required">Status</label>
					<select name="status" class="form-control">
						<option value="">-- Pilih Status --</option>
						@foreach (\App\Helpers\DataHelper::getStatusOptions() as $value => $label)
							<option value="{{ $value }}">{{ $label }}</option>
						@endforeach
					</select>
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-4 mb-3">
					<label class="form-label">Cuti (hari)</label>
					<input type="number" name="cuti" class="form-control" step="0.01" min="0" value="{{ old('cuti', $model->cuti ?? '') }}">
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-4 mb-3">
					<label class="form-label">Kuota Cuti (hari)</label>
					<input type="number" name="kuota_cuti" class="form-control" step="0.01" min="0" value="{{ old('kuota_cuti', $model->kuota_cuti ?? '') }}">
					<div class="invalid-feedback"></div>
				</div>
			</div>

			<!-- Alamat -->
			<h5 class="mb-3 mt-4">Alamat</h5>
			<div class="row">
				<div class="col-md-3 mb-3">
					<label class="form-label required">Provinsi</label>
					<select name="alamat_provinsi_id" class="form-control">
						<option value="">-- Pilih Provinsi --</option>
						@foreach (\App\Helpers\DataHelper::getWilayah('provinsi') as $item)
							<option value="{{ $item->id }}">{{ $item->name }}</option>
						@endforeach
					</select>
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-3 mb-3">
					<label class="form-label required">Kabupaten/Kota</label>
					<select name="alamat_kabupaten_id" class="form-control">
						<option value="">-- Pilih Kabupaten/Kota --</option>
						@foreach (\App\Helpers\DataHelper::getWilayah('kabupaten') as $item)
							<option value="{{ $item->id }}">{{ $item->name }}</option>
						@endforeach
					</select>
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-3 mb-3">
					<label class="form-label required">Kecamatan</label>
					<select name="alamat_kecamatan_id" class="form-control">
						<option value="">-- Pilih Kecamatan --</option>
						@foreach (\App\Helpers\DataHelper::getWilayah('kecamatan') as $item)
							<option value="{{ $item->id }}">{{ $item->name }}</option>
						@endforeach
					</select>
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-3 mb-3">
					<label class="form-label required">Kelurahan</label>
					<select name="alamat_kelurahan_id" class="form-control">
						<option value="">-- Pilih Kelurahan --</option>
						@foreach (\App\Helpers\DataHelper::getWilayah('kelurahan') as $item)
							<option value="{{ $item->id }}">{{ $item->name }}</option>
						@endforeach
					</select>
					<div class="invalid-feedback"></div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12 mb-3">
					<label class="form-label required">Alamat Detail</label>
					<textarea name="alamat_detail" class="form-control" rows="3">{{ old('alamat_detail', $model->alamat_detail ?? '') }}</textarea>
					<div class="invalid-feedback"></div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6 mb-3">
					<label class="form-label">Latitude</label>
					<input type="text" name="latitude" class="form-control" placeholder="Contoh: -6.200000" value="{{ old('latitude', $model->latitude ?? '') }}">
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-6 mb-3">
					<label class="form-label">Longitude</label>
					<input type="text" name="longitude" class="form-control" placeholder="Contoh: 106.816666" value="{{ old('longitude', $model->longitude ?? '') }}">
					<div class="invalid-feedback"></div>
				</div>
			</div>

			<div class="row">
				<div class="col text-end">
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

		// set usia 
		const tanggalLahirInput = document.querySelector('input[name="tanggal_lahir"]');
		const usiaInput = document.querySelector('input[name="usia"]');
		tanggalLahirInput.addEventListener('change', function() {
			const today = new Date();
			const birthDate = new Date(this.value);
			let age = today.getFullYear() - birthDate.getFullYear();
			const m = today.getMonth() - birthDate.getMonth();
			if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
				age--;
			}
			usiaInput.value = age;
		});

		// jmc preset 
		const dataJson = jsonScriptToFormFields('#form', '#data');

		$('#form').formAjaxSubmit();
	</script>
@endsection
