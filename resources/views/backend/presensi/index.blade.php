@php
$indexHref = fn($params = []) => route('backend.presensi.index', array_merge(
	$request->query->all(),
	$params,
));
$createHref = route('backend.presensi.create');
$viewHref = fn($model) => route('backend.presensi.view', ['presensi' => $model->id_employee]);
$deleteHref = fn($model) => route('backend.presensi.destroy', ['presensi' => $model, 'redirect' => $indexHref()]);

$title = $pageName = 'Rekap Presensi Pegawai';

$breadcrumbs[] = 'Presensi';
$breadcrumbs[] = 'Index';

@endphp

@extends('backend/layouts/main', get_defined_vars())

@section('content')
	@if ($errors->any())
		<div class="alert alert-danger">
			<ul class="mb-0">
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	<div class="card" id="index-pjax">
		@include('backend.presensi.index_header', get_defined_vars())

		<div class="card-body table-responsive p-0 m-0">
			<table class="table table-bordered table-hover p-0 m-0">
				<thead>
					<tr>
						<th>No.</th>
						<th>Nama</th>
						<th>Jabatan</th>
						<th>Hadir</th>
						<th>Status Hadir</th>
						<th>Cuti</th>
						<th>Kuota Cuti</th>
						<th>Izin</th>
						<th>Kuota Izin</th>
						<th width="50">Aksi</th>
					</tr>
				</thead>
				<tbody>
					@forelse($models as $model)
					<tr>
						<td>{{ ($models->currentPage() - 1) * $models->perPage() + $loop->iteration }}</td>
						<td>{{ $model->name }}</td>
						<td>{{ $model->jabatan }}</td>
						<td>
							<span class="badge badge-primary">{{ $model->durasi_hadir ? $model->durasi_hadir/8 : 0 }}</span>
						</td>
						<td>
							<span class="badge badge-success">{{ $model->durasi_hadir/8 > 20 ? 'terpenuhi' : 'tidak terpenuhi' }}</span>
						</td>
						<td>
							<span class="badge badge-info">{{ $model->cuti ?? 0 }}</span>
						</td>
						<td>{{ $model->kuota_cuti }}</td>
						<td>
							<span class="badge badge-secondary">{{ $model->izin ?? 0 }}</span>
						</td>
						<td>{{ $model->kuota_izin }}</td>
						<td class="text-nowrap">
							<a href="{{ $viewHref($model) }}" data-pjax="0" class="text-primary text-decoration-none">
								<i class="bi bi-eye" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail"></i>
							</a>
						</td>
					</tr>
					@empty
						<tr>
							<td colspan="999" class="text-center">
								{{ __('Data Tidak Tersedia') }}
							</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>

		@include('backend/partials/index_footer', get_defined_vars())
	</div>
	<div class="alert alert-info mt-2" role="alert">
		<small>
			<strong>Petunjuk:</strong>
			<ul class="mb-0 ps-3">
				<li>Hadir : Total kehadiran pegawai dalam hari</li>
				<li>Status Hadir : jika total hadir > 20 maka "terpehuni" selain itu "tidak terpehuni"</li>
			</ul>
		</small>
	</div>
@endsection

@section('scripts')
	@parent

	<script type="module">
		$('#index-pjax').pjaxCreate();
	</script>
@endsection