@php
use \Illuminate\Support\Js;
use jeemce\helpers\DBHelper;
use App\Models\User;

$createHref ??= route('backend.presensi.create');
$searchHref = fn() => $indexHref([
	'page' => null,
	'search' => null,
	'filter' => null,
]);

$months = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];

$years = range(date('Y') - 5, date('Y') + 1);
$currentMonth = request('month', date('m'));
$currentYear = request('year', date('Y'));

@endphp

<div class="card-header bg-light text-dark d-md-flex flex-wrap">
	
	<div class="mb-2 mb-lg-0">
		<a href="{{ route('backend.presensi.export') }}?month={{ $currentMonth }}&year={{ $currentYear }}" 
		   class="btn btn-success d-block d-md-inline-block">
			<i class="bi bi-download"></i> Download Excel
		</a>
	</div>

    <div class="mb-2 mb-lg-0 ms-2">
		<a href="#" data-pjax="0" class="btn btn-primary d-block d-md-inline-block">
			<i class="bi bi-upload"></i> Import Presensi
		</a>
	</div>

	<div class="me-md-auto"></div>

	<form id="index-header" class="row" method="get" action="{{ $searchHref() }}">
		<div class="col-12 col-lg-auto mb-2 mb-lg-0">
			<select name="month" class="form-select" onchange="this.form.submit()">
				@foreach($months as $key => $monthName)
					<option value="{{ $key }}" {{ $currentMonth == $key ? 'selected' : '' }}>
						{{ $monthName }}
					</option>
				@endforeach
			</select>
		</div>

		<div class="col-12 col-lg-auto mb-2 mb-lg-0">
			<select name="year" class="form-select" onchange="this.form.submit()">
				@foreach($years as $yearOption)
					<option value="{{ $yearOption }}" {{ $currentYear == $yearOption ? 'selected' : '' }}>
						{{ $yearOption }}
					</option>
				@endforeach
			</select>
		</div>

		<div class="col-12 col-lg-auto">
			<div class="input-group">
				<input type="search" name="search" value="{{ $search->search ?? request('search') }}" class="form-control" placeholder="Cari nama atau jabatan...">
				<button type="submit" class="btn btn-outline-secondary">
					<i class="bi bi-search"></i>
				</button>
			</div>
		</div>

		<!-- Hidden inputs untuk menjaga parameter lain -->
		@if(request('filter'))
			@foreach(request('filter', []) as $key => $value)
				<input type="hidden" name="filter[{{ $key }}]" value="{{ $value }}">
			@endforeach
		@endif
		@if(request('sorter'))
			<input type="hidden" name="sorter" value="{{ request('sorter') }}">
		@endif
	</form>
</div>