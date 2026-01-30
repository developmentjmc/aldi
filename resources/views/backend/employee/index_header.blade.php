@php
use \Illuminate\Support\Js;
use jeemce\helpers\DBHelper;
use App\Models\User;

$createHref ??= '#';
$searchHref = fn() => $indexHref([
	'page' => null,
	'search' => null,
	'filter' => null,
]);

$jabatanOptions = \App\Enums\JabatanEnum::options();
$jenisPegawai = \App\Enums\JenisPegawaiEnum::options();
$masaKerja = [
    '>' => 'Lebih dari 5 tahun',
    '<' => 'Kurang dari 5 tahun',
];

@endphp

<div class="card-header bg-light text-dark d-md-flex flex-wrap">
	<div class="mb-2 mb-lg-0">
		@if (auth()->user()->role->name === 'Admin HRD')
			<a href="{{ $createHref }}" data-pjax="0" class="btn btn-primary d-block d-md-inline-block">Create</a>
		@endif
		<a href="{{ route('backend.pegawai.pdf') }}" class="btn btn-secondary d-block d-md-inline-block ms-2" target="_blank">Export PDF</a>
	</div>

	<div class="me-md-auto"></div>

	<form id="index-header" class="row" method="get" action="{{ $searchHref() }}">
		<div class="col-12 col-lg-auto mb-2 mb-lg-0">
			{{ HtmlHelper::select([
				'name' => 'filter[jabatan]',
				'value' => $search->filterValue('jabatan'),
				'options' => $jabatanOptions,
				'onchange' => "$(this.form).trigger('submit')",
				'class' => 'form-select',
				'placeholder' => 'Semua Jabatan',
			]) }}
		</div>

		<div class="col-12 col-lg-auto mb-2 mb-lg-0">
			{{ HtmlHelper::select([
				'name' => 'filter[masa_kerja]',
				'value' => $search->filterValue('masa_kerja'),
				'options' => $masaKerja,
				'onchange' => "$(this.form).trigger('submit')",
				'class' => 'form-select',
				'placeholder' => 'Semua Masa Kerja',
			]) }}
		</div>

        <div class="col-12 col-lg-auto mb-2 mb-lg-0">
			{{ HtmlHelper::select([
				'name' => 'filter[jenis_pegawai]',
				'value' => $search->filterValue('jenis_pegawai'),
				'options' => $jenisPegawai,
				'onchange' => "$(this.form).trigger('submit')",
				'class' => 'form-select',
				'placeholder' => 'Semua Jenis Pegawai',
			]) }}
		</div>

		<div class="col-12 col-lg-auto">
			<div class="input-group">
				<input type="search" name="search" value="{{ $search->search }}" class="form-control" placeholder="Pencarian ...">
				<button type="submit" class="btn btn-outline-secondary">
					<i class="bi bi-search"></i>
				</button>
			</div>
		</div>
	</form>
</div>
