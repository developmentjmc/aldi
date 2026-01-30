@php
use \App\Models\User;

$indexHref = fn($params = []) => route('backend.user.index', array_merge(
	$request->query->all(),
	$params,
));
$createHref = route('backend.user.create');
$updateHref = fn($model) => route('backend.user.edit', ['user' => $model]);
$deleteHref = fn($model) => route('backend.user.destroy', ['user' => $model, 'redirect' => $indexHref()]);
$sortHref = fn($field) => route('backend.user.index', array_merge(
	$request->query->all(),
	[
		'sorter' => $search->sorterQueryParam($field),
	],
));

$title = $pageName = 'User';

$breadcrumbs[] = 'User';
$breadcrumbs[] = 'Index';
@endphp

@extends('backend/layouts/main', get_defined_vars())

@section('content')
	<div class="card" id="index-pjax">
		@include('backend.user.index_header', get_defined_vars())

		<div class="card-body table-responsive p-0 m-0">
			<table class="table table-bordered table-hover p-0 m-0">
				<thead>
					<tr>
						<th width="50">Aksi</th>
						<th>Tanggal</th>
						<th><a href="{{ $sortHref('name') }}">Name</a></th>
						<th><a href="{{ $sortHref('phone') }}">Phone</a></th>
						<th><a href="{{ $sortHref('email') }}">Email</a></th>
						<th><a href="{{ $sortHref('role_name') }}">Role</a></th>
						<th><a href="{{ $sortHref('status') }}">Status</a></th>
					</tr>
				</thead>
				<tbody>
					@forelse($models as $model)
					<tr>
						<td class="text-nowrap">
							<a href="{{ $updateHref($model) }}" data-pjax="0" class="text-primary text-decoration-none">
								<i class="bi bi-pencil-square" data-bs-toggle="tooltip" data-bs-placement="top" title="Update"></i>
							</a>
							@if (auth()->user()->id != $model->id)
							<a href="{{ $deleteHref($model) }}" data-pjax="0" onclick="modalDeleteConfirm(this, event)" class="text-danger text-decoration-none">
								<i class="bi bi-trash" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"></i>
							</a>
							@endif
						</td>
						<td>{{ date('d-m-Y', strtotime($model->created_at)) }}</td>
						<td>{{ $model->name }}</td>
						<td>{{ $model->phone }}</td>
						<td>{{ $model->email }}</td>
						<td>{{ $model->role_name }}</td>
						<td>{{ $model->statusOptions($model->status) }}</td>
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
