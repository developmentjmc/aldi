@php

$title = 'Form Presensi';
$indexHref = route('backend.presensi.index');
$breadcrumbs[] = 'Presensi';

if (isset($model->id)) {
	$submitHref = route('backend.presensi.update', ['presensi' => $model->id]);
	$breadcrumbs[] = 'Update';
} else {
	$submitHref = route('backend.presensi.store');
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
			<!-- Data Presensi -->
			<h5 class="mb-3">Data Presensi</h5>
			<div class="row">
				<div class="col-md-6 mb-3">
					<label class="form-label required">Pegawai</label>
					<select name="id_employee" class="form-control">
						<option value="">-- Pilih Pegawai --</option>
						@foreach($employees as $employee)
							<option value="{{ $employee->id }}" 
									{{ old('id_employee', $model->id_employee ?? '') == $employee->id ? 'selected' : '' }}
									data-name="{{ $employee->name }}"
									data-jabatan="{{ $employee->jabatan }}">
								{{ $employee->name }} - {{ $employee->jabatan }}
							</option>
						@endforeach
					</select>
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-6 mb-3">
					<label class="form-label required">Nama</label>
					<input type="text" name="name" class="form-control" value="{{ old('name', $model->name ?? '') }}" readonly>
					<div class="invalid-feedback"></div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6 mb-3">
					<label class="form-label required">Jabatan</label>
					<input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $model->jabatan ?? '') }}" readonly>
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-6 mb-3">
					<label class="form-label required">Lokasi Absen</label>
					<select name="lokasi_absen" class="form-control">
						<option value="">-- Pilih Lokasi --</option>
						<option value="Gedung Utama" {{ old('lokasi_absen', $model->lokasi_absen ?? '') == 'Gedung Utama' ? 'selected' : '' }}>Gedung Utama</option>
						<option value="Gedung A" {{ old('lokasi_absen', $model->lokasi_absen ?? '') == 'Gedung A' ? 'selected' : '' }}>Gedung A</option>
						<option value="Gedung B" {{ old('lokasi_absen', $model->lokasi_absen ?? '') == 'Gedung B' ? 'selected' : '' }}>Gedung B</option>
					</select>
					<div class="invalid-feedback"></div>
				</div>
			</div>

			<!-- Waktu Kehadiran -->
			<h5 class="mb-3 mt-4">Waktu Kehadiran</h5>
			<div class="row">
				<div class="col-md-6 mb-3">
					<label class="form-label">Check In</label>
					<input type="datetime-local" name="checkin" class="form-control" value="{{ old('checkin', $model->checkin ? $model->checkin->format('Y-m-d\TH:i') : '') }}">
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-6 mb-3">
					<label class="form-label">Check Out</label>
					<input type="datetime-local" name="checkout" class="form-control" value="{{ old('checkout', $model->checkout ? $model->checkout->format('Y-m-d\TH:i') : '') }}">
					<div class="invalid-feedback"></div>
				</div>
			</div>

			<!-- Status Kehadiran -->
			<h5 class="mb-3 mt-4">Status Kehadiran</h5>
			<div class="row">
				<div class="col-md-3 mb-3">
					<label class="form-label">Hadir</label>
					<select name="hadir" class="form-control">
						<option value="0" {{ old('hadir', $model->hadir ?? '') == '0' ? 'selected' : '' }}>Tidak</option>
						<option value="1" {{ old('hadir', $model->hadir ?? '') == '1' ? 'selected' : '' }}>Ya</option>
					</select>
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-3 mb-3">
					<label class="form-label">Cuti</label>
					<select name="cuti" class="form-control">
						<option value="0" {{ old('cuti', $model->cuti ?? '') == '0' ? 'selected' : '' }}>Tidak</option>
						<option value="1" {{ old('cuti', $model->cuti ?? '') == '1' ? 'selected' : '' }}>Ya</option>
					</select>
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-3 mb-3">
					<label class="form-label">Izin</label>
					<select name="izin" class="form-control">
						<option value="0" {{ old('izin', $model->izin ?? '') == '0' ? 'selected' : '' }}>Tidak</option>
						<option value="1" {{ old('izin', $model->izin ?? '') == '1' ? 'selected' : '' }}>Ya</option>
					</select>
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-3 mb-3">
					<label class="form-label">Durasi Hadir (menit)</label>
					<input type="number" name="durasi_hadir" class="form-control" value="{{ old('durasi_hadir', $model->durasi_hadir ?? '') }}" min="0">
					<div class="invalid-feedback"></div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6 mb-3">
					<label class="form-label">Kuota Cuti</label>
					<input type="number" name="kuota_cuti" class="form-control" value="{{ old('kuota_cuti', $model->kuota_cuti ?? '') }}" min="0">
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-6 mb-3">
					<label class="form-label">Kuota Izin</label>
					<input type="number" name="kuota_izin" class="form-control" value="{{ old('kuota_izin', $model->kuota_izin ?? '') }}" min="0">
					<div class="invalid-feedback"></div>
				</div>
			</div>

			<!-- Verifikasi -->
			<h5 class="mb-3 mt-4">Verifikasi</h5>
			<div class="row">
				<div class="col-md-6 mb-3">
					<label class="form-label">Status Verifikasi</label>
					<select name="verifikasi" class="form-control">
						<option value="">-- Belum Diverifikasi --</option>
						<option value="disetujui" {{ old('verifikasi', $model->verifikasi ?? '') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
						<option value="ditolak" {{ old('verifikasi', $model->verifikasi ?? '') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
					</select>
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-6 mb-3">
					<label class="form-label">Verifikator</label>
					<input type="text" name="verifikator" class="form-control" value="{{ old('verifikator', $model->verifikator ?? '') }}">
					<div class="invalid-feedback"></div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12 mb-3">
					<label class="form-label">Keterangan</label>
					<textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $model->keterangan ?? '') }}</textarea>
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
		// Auto fill nama dan jabatan ketika employee dipilih
		const employeeSelect = document.querySelector('select[name="id_employee"]');
		const nameInput = document.querySelector('input[name="name"]');
		const jabatanInput = document.querySelector('input[name="jabatan"]');
		
		employeeSelect.addEventListener('change', function() {
			const selectedOption = this.options[this.selectedIndex];
			const name = selectedOption.getAttribute('data-name');
			const jabatan = selectedOption.getAttribute('data-jabatan');
			
			nameInput.value = name || '';
			jabatanInput.value = jabatan || '';
		});

		// Hitung durasi otomatis ketika checkin/checkout berubah
		const checkinInput = document.querySelector('input[name="checkin"]');
		const checkoutInput = document.querySelector('input[name="checkout"]');
		const durasiInput = document.querySelector('input[name="durasi_hadir"]');
		const hadirSelect = document.querySelector('select[name="hadir"]');
		
		function calculateDuration() {
			const checkin = checkinInput.value;
			const checkout = checkoutInput.value;
			
			if (checkin && checkout) {
				const start = new Date(checkin);
				const end = new Date(checkout);
				const diffMs = end - start;
				const diffMins = Math.round(diffMs / 60000);
				
				if (diffMins > 0) {
					durasiInput.value = diffMins;
					hadirSelect.value = '1';
				}
			}
		}
		
		checkinInput.addEventListener('change', calculateDuration);
		checkoutInput.addEventListener('change', calculateDuration);

		// Reset hadir/cuti/izin ketika salah satu diubah
		const hadirSelectField = document.querySelector('select[name="hadir"]');
		const cutiSelect = document.querySelector('select[name="cuti"]');
		const izinSelect = document.querySelector('select[name="izin"]');
		
		function resetOtherStatus(changedElement) {
			if (changedElement.value === '1') {
				[hadirSelectField, cutiSelect, izinSelect].forEach(select => {
					if (select !== changedElement) {
						select.value = '0';
					}
				});
			}
		}
		
		hadirSelectField.addEventListener('change', () => resetOtherStatus(hadirSelectField));
		cutiSelect.addEventListener('change', () => resetOtherStatus(cutiSelect));
		izinSelect.addEventListener('change', () => resetOtherStatus(izinSelect));

		// jmc preset 
		const dataJson = jsonScriptToFormFields('#form', '#data');

		$('#form').formAjaxSubmit();
	</script>
@endsection