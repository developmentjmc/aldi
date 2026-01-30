@php
$indexHref = fn($params = []) => route('backend.pegawai.index', array_merge(
	$request->query->all(),
	$params,
));
$createHref = route('backend.pegawai.create');
$updateHref = fn($model) => route('backend.pegawai.edit', ['pegawai' => $model]);
$deleteHref = fn($model) => route('backend.pegawai.destroy', ['pegawai' => $model, 'redirect' => $indexHref()]);
$sortHref = fn($field) => route('backend.pegawai.index', array_merge(
	$request->query->all(),
	[
		'sorter' => $search->sorterQueryParam($field),
	],
));

$title = $pageName = 'Data Pegawai';

$breadcrumbs[] = 'Data Pegawai';
$breadcrumbs[] = 'Index';

@endphp

@extends('backend/layouts/main', get_defined_vars())

@section('content')
	<div class="card" id="index-pjax">
		@include('backend.employee.index_header', get_defined_vars())

		<div class="card-body table-responsive p-0 m-0">
			<table class="table table-bordered table-hover p-0 m-0">
				<thead>
					<tr>
						<th>No.</th>
						<th><a href="{{ $sortHref('nip') }}">NIP</a></th>
						<th><a href="{{ $sortHref('name') }}">Nama</a></th>
						<th><a href="{{ $sortHref('jabatan') }}">Jabatan</a></th>
						<th><a href="{{ $sortHref('tanggal_masuk') }}">Tanggal Masuk</a></th>
						<th><a href="{{ $sortHref('masa_kerja') }}">Masa Kerja</a></th>
						<th width="50">Aksi</th>
					</tr>
				</thead>
				<tbody>
					@forelse($models as $model)
					<tr>
						<td>{{ ($models->currentPage() - 1) * $models->perPage() + $loop->iteration }}</td>
						<td>{{ $model->nip }}</td>
						<td>{{ $model->name }}</td>
						<td>{{ $model->jabatan }}</td>
						<td>{{ $model->tanggal_masuk }}</td>
						<td>{{ $model->masa_kerja }} tahun</td>
						<td class="text-nowrap">
							<a href="{{ $updateHref($model) }}" data-pjax="0" class="text-primary text-decoration-none">
								<i class="bi bi-pencil-square" data-bs-toggle="tooltip" data-bs-placement="top" title="Update"></i>
							</a>
							@if (auth()->user()->role->name === 'Admin HRD')
								@if (auth()->user()->id != $model->id)
									<form action="{{ $deleteHref($model) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
										@csrf
										@method('DELETE')
										<button type="submit" class="btn btn-link text-danger text-decoration-none p-0 border-0">
											<i class="bi bi-trash" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"></i>
										</button>
									</form>
								@endif
							@endif
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
