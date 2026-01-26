@extends('layouts.backend.main')

@section('title', 'Detail Presensi - ' . $employee->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Presensi - {{ $employee->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ action([App\Backend\PresensiController::class, 'index']) }}?month={{ request('month') }}&year={{ request('year') }}" 
                           class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali ke List
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Info Pegawai -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info">
                                    <i class="fas fa-user"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Nama Pegawai</span>
                                    <span class="info-box-number">{{ $employee->name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary">
                                    <i class="fas fa-briefcase"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Jabatan</span>
                                    <span class="info-box-number">{{ $employee->jabatan ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rekap Statistik -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $rekap->total_hadir ?? 0 }}</h3>
                                    <p>Hari Hadir</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $rekap->total_cuti ?? 0 }}/{{ $rekap->kuota_cuti ?? 12 }}</h3>
                                    <p>Cuti</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $rekap->total_izin ?? 0 }}/{{ $rekap->kuota_izin ?? 6 }}</h3>
                                    <p>Izin</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-secondary">
                                <div class="inner">
                                    <h3>{{ round($rekap->avg_durasi_hadir ?? 0) }}</h3>
                                    <p>Rata-rata Menit/Hari</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-stopwatch"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Periode -->
                    <form method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="month" class="form-control" onchange="this.form.submit()">
                                    @foreach($months as $key => $monthName)
                                        <option value="{{ $key }}" {{ request('month', now()->subMonth()->month) == $key ? 'selected' : '' }}>
                                            {{ $monthName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="year" class="form-control" onchange="this.form.submit()">
                                    @foreach($years as $yearOption)
                                        <option value="{{ $yearOption }}" {{ request('year', now()->subMonth()->year) == $yearOption ? 'selected' : '' }}>
                                            {{ $yearOption }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>

                    <!-- Tabel Detail Presensi -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="12%">Tanggal</th>
                                    <th width="15%">Lokasi Absen</th>
                                    <th width="10%">Check In</th>
                                    <th width="10%">Check Out</th>
                                    <th width="8%">Hadir</th>
                                    <th width="6%">Cuti</th>
                                    <th width="6%">Izin</th>
                                    <th width="8%">Durasi (mnt)</th>
                                    <th width="10%">Verifikasi</th>
                                    <th width="10%">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($presensiData as $index => $data)
                                    <tr>
                                        <td>{{ $presensiData->firstItem() + $index }}</td>
                                        <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y') }}</td>
                                        <td>{{ $data->lokasi_absen }}</td>
                                        <td>
                                            @if($data->checkin)
                                                {{ \Carbon\Carbon::parse($data->checkin)->format('H:i') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($data->checkout)
                                                {{ \Carbon\Carbon::parse($data->checkout)->format('H:i') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($data->hadir)
                                                <span class="badge badge-success">{{ $data->hadir }}</span>
                                            @else
                                                <span class="text-muted">0</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($data->cuti)
                                                <span class="badge badge-info">{{ $data->cuti }}</span>
                                            @else
                                                <span class="text-muted">0</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($data->izin)
                                                <span class="badge badge-warning">{{ $data->izin }}</span>
                                            @else
                                                <span class="text-muted">0</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $data->durasi_hadir }}</td>
                                        <td class="text-center">
                                            @if($data->verifikasi == 'disetujui')
                                                <span class="badge badge-success">Disetujui</span>
                                            @elseif($data->verifikasi == 'ditolak')
                                                <span class="badge badge-danger">Ditolak</span>
                                            @else
                                                <span class="badge badge-secondary">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ Str::limit($data->keterangan, 50) }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                            Tidak ada data presensi untuk periode ini
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Menampilkan {{ $presensiData->firstItem() ?? 0 }} - {{ $presensiData->lastItem() ?? 0 }} 
                            dari {{ $presensiData->total() }} data
                        </div>
                        {{ $presensiData->withQueryString()->links() }}
                    </div>
                </div>
            </div>
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