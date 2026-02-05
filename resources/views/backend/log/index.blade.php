@php
$indexHref = fn($params = []) => route('backend.log.index', array_merge(
	$request->query->all(),
	$params,
));
$viewHref = fn($model) => route('backend.log.show', ['log' => $model]);

$title = $pageName = 'Log Aktivitas';

$breadcrumbs[] = 'Log Aktivitas';
$breadcrumbs[] = 'Index';

@endphp

@extends('backend/layouts/main', get_defined_vars())

@section('content')
	<div class="card" id="index-pjax">
		@include('backend.log.index_header', get_defined_vars())

		<div class="card-body table-responsive p-0 m-0">
			<table class="table table-bordered table-hover p-0 m-0">
				<thead>
					<tr>
						<th>No.</th>
						<th>Tanggal</th>
						<th>Jam</th>
						<th>Username</th>
						<th>Modul</th>
						<th>Aksi</th>
						<th>Deskripsi</th>
						<th width="50">Detail</th>
					</tr>
				</thead>
				<tbody>
					@forelse($models as $model)
					<tr>
						<td>{{ ($models->currentPage() - 1) * $models->perPage() + $loop->iteration }}</td>
						<td>{{ date('Y-m-d', strtotime($model->tanggal)) }}</td>
						<td>{{ date('H:i:s', strtotime($model->jam)) }}</td>
						<td>{{ $model->username }}</td>
						<td>{{ $model->modul }}</td>
						<td>
							<span class="badge text-white
								@if($model->aksi == 'create') bg-success
								@elseif($model->aksi == 'update') bg-warning
								@elseif($model->aksi == 'delete') bg-danger
								@elseif($model->aksi == 'login') bg-info
								@elseif($model->aksi == 'logout') bg-secondary
								@else bg-primary
								@endif
							">{{ ucfirst($model->aksi) }}</span>
						</td>
						<td>{{ Str::limit($model->deskripsi, 50) }}</td>
						<td class="text-nowrap">
							<a href="{{ $viewHref($model) }}" data-pjax="0" class="text-primary text-decoration-none">
								<i class="bi bi-eye" data-bs-toggle="tooltip" data-bs-placement="top" title="View"></i>
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