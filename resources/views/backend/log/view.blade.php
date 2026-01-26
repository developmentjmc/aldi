@php
$title = $pageName = 'Detail Log Aktivitas';

$breadcrumbs[] = 'Log Aktivitas';
$breadcrumbs[] = 'Detail';

$indexHref = route('backend.log.index');
@endphp

@extends('backend/layouts/main', get_defined_vars())

@section('content')
	<div class="card">
		<div class="card-header bg-light text-dark">
			<div class="d-flex w-100">
				<h5 class="card-title mb-0">Detail Log Aktivitas</h5>
				<div class="ms-auto">
					<a href="{{ $indexHref }}" class="btn btn-outline-secondary">
						<i class="bi bi-arrow-left"></i> Kembali
					</a>
				</div>
			</div>
		</div>

		<div class="card-body">
			<div class="row">
				<div class="col-md-12">
					<table class="table table-borderless">
						<tr>
							<th>Tanggal:</th>
							<td>{{ $model->tanggal }}</td>
						</tr>
						<tr>
							<th>Jam:</th>
							<td>{{ $model->jam }}</td>
						</tr>
						<tr>
							<th>Username:</th>
							<td>{{ $model->username }}</td>
						</tr>
						<tr>
							<th>Modul:</th>
							<td>{{ $model->modul }}</td>
						</tr>
						<tr>
							<th>Aksi:</th>
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
						</tr>
						<tr>
							<th>Deskripsi:</th>
							<td>{{ $model->deskripsi }}</td>
						</tr>
						<tr>
							<th>Dibuat pada:</th>
							<td>{{ $model->created_at->format('d-m-Y H:i:s') }}</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection