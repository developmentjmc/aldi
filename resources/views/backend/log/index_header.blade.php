@php
$searchHref = fn() => $indexHref([
	'page' => null,
	'search' => null,
	'filter' => null,
]);

@endphp

<div class="card-header bg-light text-dark d-md-flex flex-wrap">
	<div class="mb-2 mb-lg-0">
		<h5 class="card-title mb-0">Log Aktivitas</h5>
	</div>

	<div class="me-md-auto"></div>

	<form id="index-header" class="row" method="get" action="{{ $searchHref() }}">
		<div class="col-12 col-lg-auto">
            <div class="input-group">
				<input type="search" name="search" value="{{ $search->search }}" class="form-control" placeholder="Cari by username, jam, modul..">
				<button type="submit" class="btn btn-outline-secondary">
					<i class="bi bi-search"></i>
				</button>
			</div>
		</div>
	</form>
</div>