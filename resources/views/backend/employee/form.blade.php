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

<style>
	.choices{
		margin-bottom: 0px !important;
	}
</style>

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
					<label class="form-label required">Foto Pegawai</label>
					<input type="file" name="upload_foto_pegawai" class="form-control" accept="image/jpeg,image/png,image/jpg">
					<div class="invalid-feedback"></div>
					@if (count($model->files) > 0)
						<img src="{{ $model->file('foto_pegawai')->url() }}" alt="Foto Pegawai" class="img-thumbnail mt-2">
					@endif
				</div>
				<div class="col-md-8 mb-3"></div>
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
					<select name="pendidikan[]" data-error-name="pendidikan" class="form-select" multiple="multiple">
						<option value="SD" {{ in_array('SD', old('pendidikan', is_array($model->pendidikan ?? '') ? $model->pendidikan : json_decode($model->pendidikan ?? '[]', true))) ? 'selected' : '' }}>SD</option>
						<option value="SMP" {{ in_array('SMP', old('pendidikan', is_array($model->pendidikan ?? '') ? $model->pendidikan : json_decode($model->pendidikan ?? '[]', true))) ? 'selected' : '' }}>SMP</option>
						<option value="SMA" {{ in_array('SMA', old('pendidikan', is_array($model->pendidikan ?? '') ? $model->pendidikan : json_decode($model->pendidikan ?? '[]', true))) ? 'selected' : '' }}>SMA</option>
						<option value="D3" {{ in_array('D3', old('pendidikan', is_array($model->pendidikan ?? '') ? $model->pendidikan : json_decode($model->pendidikan ?? '[]', true))) ? 'selected' : '' }}>D3</option>
						<option value="S1" {{ in_array('S1', old('pendidikan', is_array($model->pendidikan ?? '') ? $model->pendidikan : json_decode($model->pendidikan ?? '[]', true))) ? 'selected' : '' }}>S1</option>
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
					<label class="form-label">Kuota Izin (hari)</label>
					<input type="number" name="kuota_izin" class="form-control" step="0.01" min="0" value="{{ old('kuota_izin', $model->kuota_izin ?? '') }}">
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
						@foreach (\App\Helpers\DataHelper::getWilayah('provinsi') as $key => $value)
							<option value="{{ $value->id }}" {{ old('alamat_provinsi_id', $model->alamat_provinsi_id ?? '') == $value->id ? 'selected' : '' }}>{{ $value->name }}</option>
						@endforeach
					</select>
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-3 mb-3">
					<label class="form-label required">Kabupaten/Kota</label>
					<select name="alamat_kabupaten_id" class="form-control">
						@foreach (\App\Helpers\DataHelper::getWilayah('kabupaten') as $key => $value)
							<option value="{{ $value->id }}" {{ old('alamat_kabupaten_id', $model->alamat_kabupaten_id ?? '') == $value->id ? 'selected' : '' }}>{{ $value->name }}</option>
						@endforeach
					</select>
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-3 mb-3">
					<label class="form-label required">Kecamatan</label>
					<select name="alamat_kecamatan_id" class="form-control">
						@if ($model?->alamat_kecamatan_id)
							<option value="{{ $model->alamat_kecamatan_id }}" selected>{{ $model->kecamatan->name }}</option>
						@endif
					</select>
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-3 mb-3">
					<label class="form-label required">Kelurahan</label>
					<select name="alamat_kelurahan_id">
						@if ($model?->alamat_kelurahan_id)
							<option value="{{ $model->alamat_kelurahan_id }}" selected>{{ $model->kelurahan->name }}</option>
						@endif
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
				<div class="col-md-12 pb-3">
					<div id="map" style="height: 450px; width: 100%;"></div>
				</div>
				<div class="col-md-6 mb-3">
					<label class="form-label">Rumah Pegawai - Latitude</label>
					<input type="text" name="latitude" class="form-control" placeholder="Contoh: -6.200000" value="{{ old('latitude', $model->latitude ?? '') }}">
					<div class="invalid-feedback"></div>
				</div>

				<div class="col-md-6 mb-3">
					<label class="form-label">Rumah Pegawai - Longitude</label>
					<input type="text" name="longitude" class="form-control" placeholder="Contoh: 106.816666" value="{{ old('longitude', $model->longitude ?? '') }}">
					<div class="invalid-feedback"></div>
				</div>
			</div>

			<div class="row">
				<div class="col text-end">
					@if (auth()->user()->role->name === 'Admin HRD')
						<a href="{{ $indexHref }}" class="btn btn-danger">Batal</a>
						&nbsp;
						<button type="submit" class="btn btn-success">Simpan</button>
					@endif
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

	<script>
		let elementChoices = {};

		document.addEventListener('DOMContentLoaded', function() {
			// Leaflet map
			var map = L.map('map').setView([-7.7956, 110.3695], 11); // Jogja

			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
				maxZoom: 19
			}).addTo(map);

			function onMapClick(e) {
				var lat = e.latlng.lat.toFixed(6);
				var lng = e.latlng.lng.toFixed(6);

				L.popup()
				.setLatLng([lat, lng])
				.setContent(`Rumah Pegawai:<br>Latitude: ${lat}<br>Longitude: ${lng}`)
				.openOn(map);

				document.querySelector('input[name="latitude"]').value = lat;
				document.querySelector('input[name="longitude"]').value = lng;
			}
			map.on('click', onMapClick);

			// set marker jika ada data
			let latitude = document.querySelector('input[name="latitude"]').value;
			let longitude = document.querySelector('input[name="longitude"]').value;
			if (latitude && longitude) {
				latitude = parseFloat(latitude);
				longitude = parseFloat(longitude);

				var marker = L.marker([latitude, longitude]).addTo(map);
				marker.bindPopup(`Rumah Pegawai:<br>Latitude: ${latitude}<br>Longitude: ${longitude}`).openPopup();
				map.setView([latitude, longitude], 13);
			}
			
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

			// Choices.js 
			pendidikanChoices = new Choices('select[name="pendidikan[]"]', {
				removeItemButton: true,
				searchEnabled: false,
				multiSelect: true,
				itemSelectText: 'Pilih',
			});

			selectSingleElement = [
				'select[name="tempat_lahir_kabupaten_id"]',
				'select[name="status_kawin"]',
				'select[name="jabatan"]',
				'select[name="jenis_pegawai"]',
				'select[name="status"]',
				'select[name="alamat_provinsi_id"]',
				'select[name="alamat_kabupaten_id"]',
				'select[name="alamat_kecamatan_id"]',
				'select[name="alamat_kelurahan_id"]',
			];

			selectSingleElement.forEach(element => {
				var initializeSelect = document.querySelector(element);
				if (initializeSelect) {
					elementChoices[element] = new Choices(initializeSelect, {
						searchEnabled: true,
						searchPlaceholderValue: 'Cari...',
						noResultsText: 'Tidak ada data',
						itemSelectText: 'Pilih',
					});
				}
			});
			
			loadWilayah(elementChoices['select[name="alamat_kecamatan_id"]'], 'kecamatan');

			const kecamatanSelect = document.querySelector('select[name="alamat_kecamatan_id"]');
			if (kecamatanSelect) {
				kecamatanSelect.addEventListener('change', function() {
					const kecamatanChoice = elementChoices['select[name="alamat_kecamatan_id"]'];
					const selectedValue = kecamatanChoice.getValue(true);
					
					// set kabupaten 
					const selectedItem = kecamatanChoice._currentState.items.find(item => item.value == selectedValue);
					
					if (selectedItem?.customProperties?.parentId) {
						let kabupatenChoice = elementChoices['select[name="alamat_kabupaten_id"]'];
						kabupatenChoice.removeActiveItems();
						kabupatenChoice.setChoiceByValue(String(selectedItem.customProperties.parentId));

						let provinsiChoice = elementChoices['select[name="alamat_provinsi_id"]'];
						provinsiChoice.removeActiveItems();
						if (selectedItem?.customProperties?.grandparentId) {
							provinsiChoice.setChoiceByValue(String(selectedItem.customProperties.grandparentId));
						}
					}
					
					loadWilayah(elementChoices['select[name="alamat_kelurahan_id"]'], 'kelurahan', selectedValue);
				});
			}
		});

		async function loadWilayah(element, tipe = null, parentId = null) {
			if (!element) return;

			let selectedElementId = element.getValue(true);

			try {
				let url = '{{ route("backend.json.wilayah") }}?tipe=' + tipe;
				if (parentId) {
					url += '&parent_id=' + parentId;
				}
				const response = await fetch(url);
				const result = await response.json();

				element.clearStore();

				if (result.success && result.data) {
					const choices = result.data.map(item => ({
						value: item.id,
						label: item.name,
						selected: selectedElementId && item.id == selectedElementId,
						customProperties: {
							parentId: item.id_parent || null,
							grandparentId: item.id_grandparent || null,
						}
					}));
					element.setChoices(choices, 'value', 'label', false);
				}
			} catch (error) {
				console.error(`Error loading ${tipe}:`, error);
			}
		}

	</script>

	<script type="module">
		// jmc preset 
		const dataJson = jsonScriptToFormFields('#form', '#data');

		$('#form').formAjaxSubmit();
	</script>
@endsection
