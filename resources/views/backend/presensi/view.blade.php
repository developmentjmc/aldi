@extends('backend.layouts.main')

@section('title', 'Detail Presensi - ' . $employee->name)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title float-start">Detail Presensi - {{ $employee->name }}</h3>
                    <div class="card-tools float-end">
                        <a href="{{ action([App\Backend\PresensiController::class, 'index']) }}?month={{ request('month') }}&year={{ request('year') }}" 
                           class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke List
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Info Pegawai -->
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="info-box">
                                <div class="info-box-content">
                                    <span class="info-box-text">NIP Pegawai</span>
                                    <span class="info-box-number">: {{ $employee->nip }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="info-box">
                                <div class="info-box-content">
                                    <span class="info-box-text">Nama Pegawai</span>
                                    <span class="info-box-number">: {{ $employee->name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="info-box">
                                <div class="info-box-content">
                                    <span class="info-box-text">Departemen</span>
                                    <span class="info-box-number">: {{ $employee->departemen ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="info-box">
                                <div class="info-box-content">
                                    <span class="info-box-text">Jabatan</span>
                                    <span class="info-box-number">: {{ $employee->jabatan ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="info-box">
                                <div class="info-box-content">
                                    <span class="info-box-text">Cuti</span>
                                    <span class="info-box-number">: {{ $cutiDiambil }}/{{ $employee->kuota_cuti ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body table-responsive p-0 m-0">
                    <table class="table table-bordered table-hover p-0 m-0">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Check-In</th>
                                <th>Check-Out</th>
                                <th>Lokasi Absen</th>
                                <th>Kehadiran</th>
                                <th>Durasi Hadir (Hari)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($models as $model)
                            <tr>
                                <td>{{ ($models->currentPage() - 1) * $models->perPage() + $loop->iteration }}</td>
                                <th>{{ $model->checkin->format('Y-m-d H:i:s') }}</th>
                                <th>{{ $model->checkout->format('Y-m-d H:i:s') }}</th>
                                <th>{{ $model->lokasi_absen }}</th>
                                <td>
                                    @php
                                        $statusHadir = $model->status_hadir;
                                        if ($model->cuti > 0) {
                                            $statusHadir = 'Cuti';
                                        } elseif ($model->izin > 0) {
                                            $statusHadir = 'Izin';
                                        }
                                    @endphp
                                    <span class="badge">{{ $statusHadir }}</span>
                                </td>
                                <td>
                                    {{ $model->durasi_hadir/8 }}
                                </td>
                                <td>
                                    <span class="badge badge-primary">{{ $model->durasi_hadir >= 8 ? "Terpenuhi" : "Tidak Terpenuhi"    }}</span>
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
        </div>
    </div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Auto submit form when filter changes
    $('select[name="month"], select[name="year"]').on('change', function() {
        $(this).closest('form').submit();
    });
});
</script>
@endsection