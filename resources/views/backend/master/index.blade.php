@php
$indexHref = fn($params = []) => route('backend.master.index', array_merge(
	$request->query->all(),
	$params,
));
$createHref = route('backend.master.create');
$updateHref = fn($model) => route('backend.master.edit', ['master' => $model]);
$deleteHref = fn($model) => route('backend.master.destroy', ['master' => $model, 'redirect' => $indexHref()]);
$sortHref = fn($field) => route('backend.master.index', array_merge(
	$request->query->all(),
	[
		'sorter' => $search->sorterQueryParam($field),
	],
));

$title = $pageName = 'Data Master';

$breadcrumbs[] = 'Data Master';
$breadcrumbs[] = 'Index';

@endphp

@extends('backend/layouts/main', get_defined_vars())

@section('content')
	<div class="card" id="index-pjax">
		@include('backend.master.index_header', get_defined_vars())
		<div class="card-body table-responsive p-0 m-0">
			<table class="table table-bordered table-hover p-0 m-0">
				<thead>
					<tr>
						<th width="70">No.</th>
						<th><a href="{{ $sortHref('name') }}">Key</a></th>
						<th><a href="{{ $sortHref('description') }}">Value</a></th>
						<th width="50">Aksi</th>
					</tr>
				</thead>
				<tbody>
					@forelse($models as $model)
					<tr>
						<td>{{ ($models->currentPage() - 1) * $models->perPage() + $loop->iteration }}</td>
						<td>{{ $model->name }}</td>
						<td>{{ $model->description }}</td>
						<td class="text-nowrap">
							<a href="{{ $updateHref($model) }}" data-pjax="0" class="text-primary text-decoration-none">
								<i class="bi bi-pencil-square" data-bs-toggle="tooltip" data-bs-placement="top" title="Update"></i>
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
