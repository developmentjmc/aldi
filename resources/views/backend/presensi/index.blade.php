@php
$indexHref = fn($params = []) => route('backend.presensi.index', array_merge(
	$request->query->all(),
	$params,
));
$createHref = route('backend.presensi.create');
$updateHref = fn($model) => route('backend.presensi.edit', ['presensi' => $model]);
$deleteHref = fn($model) => route('backend.presensi.destroy', ['presensi' => $model, 'redirect' => $indexHref()]);
$sortHref = fn($field) => route('backend.presensi.index', array_merge(
	$request->query->all(),
	[
		'sorter' => $search->sorterQueryParam($field),
	],
));

$title = $pageName = 'Rekap Presensi Pegawai';

$breadcrumbs[] = 'Presensi';
$breadcrumbs[] = 'Index';

@endphp

@extends('backend/layouts/main', get_defined_vars())

@section('content')
	<div class="card" id="index-pjax">
		@include('backend.presensi.index_header', get_defined_vars())

		<div class="card-body table-responsive p-0 m-0">
		<div class="card-body table-responsive p-0 m-0">
			<table class="table table-bordered table-hover p-0 m-0">
				<thead>
					<tr>
						<th>No.</th>
						<th><a href="{{ $sortHref('name') }}">Nama</a></th>
						<th><a href="{{ $sortHref('jabatan') }}">Jabatan</a></th>
						<th><a href="{{ $sortHref('total_hadir') }}">Hadir</a></th>
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
						<td class="text-center">
							<span class="badge badge-primary">{{ $model->total_hadir }}</span>
						</td>
						<td class="text-center">
							@if($model->status_hadir == 'Baik')
								<span class="badge badge-success">{{ $model->status_hadir }}</span>
							@elseif($model->status_hadir == 'Cukup')
								<span class="badge badge-warning">{{ $model->status_hadir }}</span>
							@else
								<span class="badge badge-danger">{{ $model->status_hadir }}</span>
							@endif
						</td>
						<td class="text-center">
							<span class="badge badge-info">{{ $model->total_cuti }}</span>
						</td>
						<td class="text-center">{{ $model->kuota_cuti }}</td>
						<td class="text-center">
							<span class="badge badge-secondary">{{ $model->total_izin }}</span>
						</td>
						<td class="text-center">{{ $model->kuota_izin }}</td>
						<td class="text-nowrap">
							<a href="{{ $updateHref($model) }}" data-pjax="0" class="text-primary text-decoration-none">
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
@endsection

@section('scripts')
	@parent

	<script type="module">
		$('#index-pjax').pjaxCreate();
	</script>
@endsection