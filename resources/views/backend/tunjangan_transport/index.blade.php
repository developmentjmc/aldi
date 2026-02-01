@php
use \App\Models\TunjanganTransport;

$indexHref = fn($params = []) => route('backend.tunjangan-transport.index', array_merge(
    $request->query->all(),
    $params,
));
$createHref = route('backend.tunjangan-transport.create');
$updateHref = fn($model) => route('backend.tunjangan-transport.edit', ['tunjangan_transport' => $model]);
$deleteHref = fn($model) => route('backend.tunjangan-transport.destroy', ['tunjangan_transport' => $model, 'redirect' => $indexHref()]);
$viewHref = fn($model) => route('backend.tunjangan-transport.show', ['tunjangan_transport' => $model]);
$sortHref = fn($field) => route('backend.tunjangan-transport.index', array_merge(
    $request->query->all(),
    [
        'sorter' => $search->sorterQueryParam($field),
    ],
));

$title = $pageName = 'Tunjangan Transport';

$breadcrumbs[] = 'Tunjangan Transport';
$breadcrumbs[] = 'Index';

@endphp

@extends('backend/layouts/main', get_defined_vars())

@section('content')
    <div class="card" id="index-pjax">
        @include('backend.tunjangan_transport.index_header', get_defined_vars())

        <div class="card-body table-responsive p-0 m-0">
            <table class="table table-bordered table-hover p-0 m-0">
                <thead>
                    <tr>
                        <th width="50">No.</th>
                        <th><a href="{{ $sortHref('nip') }}">NIP</a></th>
                        <th><a href="{{ $sortHref('employee_name') }}">Nama Pegawai</a></th>
                        <th>Gedung Kerja</th>
                        <th><a href="{{ $sortHref('bulan_tunjangan') }}">Bulan</a></th>
                        <th><a href="{{ $sortHref('jarak') }}">Jarak (km)</a></th>
                        <th><a href="{{ $sortHref('hari_kerja') }}">Hari Kerja</a></th>
                        <th><a href="{{ $sortHref('base_fare') }}">Tarif/km</a></th>
                        <th><a href="{{ $sortHref('calculated_tunjangan') }}">Tunjangan</a></th>
                        <th width="50">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($models as $model)
                    <tr class="{{ $model->calculated_tunjangan == 0 ? 'table-warning' : '' }}">
                        <td>{{ ($models->currentPage() - 1) * $models->perPage() + $loop->iteration }}</td>
                        <td>{{ $model->nip }}</td>
                        <td>{{ $model->employee_name }}</td>
                        <td>{{ $model->kantor }}</td>
                        <td>{{ \Carbon\Carbon::parse($model->bulan_tunjangan)->translatedFormat('F Y') }}</td>
                        <td class="text-end">{{ number_format($model->jarak, 2) }}</td>
                        <td class="text-center">{{ $model->hari_kerja }}</td>
                        <td class="text-end">Rp {{ number_format($model->base_fare, 0, ',', '.') }}</td>
                        <td class="text-end">
                            <strong>Rp {{ number_format($model->calculated_tunjangan, 0, ',', '.') }}</strong>
                            @if($model->calculated_tunjangan == 0)
                                <br><small class="text-muted">Tidak memenuhi syarat</small>
                            @endif
                        </td>
                        <td class="text-nowrap">
                            <a href="{{ $viewHref($model) }}" data-pjax="0" class="text-info text-decoration-none me-2">
                                <i class="bi bi-eye" data-bs-toggle="tooltip" data-bs-placement="top" title="View"></i>
                            </a>
                            @if (auth()->user()->role->name === 'Admin HRD')
                                <a href="{{ $updateHref($model) }}" data-pjax="0" class="text-primary text-decoration-none">
                                    <i class="bi bi-pencil-square" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"></i>
                                </a>
                                <form action="{{ $deleteHref($model) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger text-decoration-none p-0 border-0">
                                        <i class="bi bi-trash" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">
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