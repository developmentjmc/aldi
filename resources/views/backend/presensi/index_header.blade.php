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

$months = \App\Helpers\DataHelper::getMonth();

$years = range(date('Y') - 5, date('Y') + 1);
$currentMonth = request('month', date('m'));
$currentYear = request('year', date('Y'));

@endphp

<div class="card-header bg-light text-dark d-md-flex flex-wrap">
	
	@if (auth()->user()->role->name === 'Admin HRD')
		<div class="mb-2 mb-lg-0">
			<a href="{{ route('backend.presensi.export') }}?month={{ $currentMonth }}&year={{ $currentYear }}" 
			class="btn btn-success d-block d-md-inline-block">
				<i class="bi bi-download"></i> Download Template
			</a>
		</div>

		<div class="mb-2 mb-lg-0 ms-2">
			<button type="button" class="btn btn-primary d-block d-md-inline-block" data-bs-toggle="modal" data-bs-target="#importPresensiModal">
				<i class="bi bi-upload"></i> Import Presensi
			</button>
		</div>
	@endif

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

<!-- Modal Import Presensi -->
<div class="modal fade" id="importPresensiModal" tabindex="-1" aria-labelledby="importPresensiModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="importPresensiModalLabel">
					<i class="bi bi-upload"></i> Import Data Presensi
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form action="{{ route('backend.presensi.import') }}" method="POST" enctype="multipart/form-data">
				@csrf
				<div class="modal-body">
					<div class="mb-3">
						<label for="excelFile" class="form-label">Pilih File Excel</label>
						<input type="file" class="form-control" id="excelFile" name="excel_file" accept=".xlsx,.xls" required>
						<div class="form-text">Format file yang didukung: .xlsx, .xls</div>
					</div>
					<div class="alert alert-info" role="alert">
						<small>
							<strong>Petunjuk:</strong>
							<ul class="mb-0 ps-3">
								<li>Pastikan format file Excel sesuai dengan template</li>
								<li>Jangan ubah kolom apapun dalam template</li>
								<li>Lokasi Gedung hanya boleh di isi : Gedung Utama, Gedung A, Gedung B</li>
								<li>Gunakan format tanggal: YYYY-MM-DD H:i:s untuk kolom tanggal checkin / checkout</li>
							</ul>
						</small>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
					<button type="submit" class="btn btn-primary">
						<i class="bi bi-upload"></i> Upload & Import
					</button>
				</div>
			</form>
		</div>
	</div>
</div>