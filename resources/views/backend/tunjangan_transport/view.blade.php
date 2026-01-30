@php

$title = 'Detail Tunjangan Transport';
$indexHref = route('backend.tunjangan-transport.index');
$editHref = route('backend.tunjangan-transport.edit', ['tunjangan_transport' => $model->id]);
$breadcrumbs[] = 'Tunjangan Transport';
$breadcrumbs[] = 'Detail';

@endphp

@extends('backend/layouts/main', get_defined_vars())

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detail Tunjangan Transport</h5>
            <div>
                <a href="{{ $editHref }}" class="btn btn-primary">
                    <i class="bi bi-pencil-square"></i> Edit
                </a>
                <a href="{{ $indexHref }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted">Informasi Pegawai</h6>
                    <table class="table table-borderless">
                        <tr>
                            <td width="150"><strong>NIP</strong></td>
                            <td>: {{ $model->employee->nip }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nama</strong></td>
                            <td>: {{ $model->employee->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Jenis Pegawai</strong></td>
                            <td>: {{ $model->employee->jenis_pegawai }}</td>
                        </tr>
                        <tr>
                            <td><strong>Kantor</strong></td>
                            <td>: {{ $model->kantor }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h6 class="text-muted">Data Tunjangan</h6>
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Tarif Dasar</strong></td>
                            <td>: {{ $model->formatted_base_fare }}</td>
                        </tr>
                        <tr>
                            <td><strong>Jarak</strong></td>
                            <td>: {{ number_format($model->jarak, 2) }} km</td>
                        </tr>
                        <tr>
                            <td><strong>Jarak Bulat</strong></td>
                            <td>: {{ $model->jarak_bulat }} km</td>
                        </tr>
                        <tr>
                            <td><strong>Hari Kerja</strong></td>
                            <td>: {{ $model->hari_kerja }} hari</td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-12">
                    <h6 class="text-muted">Perhitungan Tunjangan</h6>
                    
                    <div class="alert {{ $model->is_eligible ? 'alert-success' : 'alert-warning' }}">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="mb-2">
                                    @if($model->is_eligible)
                                        <i class="bi bi-check-circle"></i> Memenuhi Syarat
                                    @else
                                        <i class="bi bi-exclamation-triangle"></i> Tidak Memenuhi Syarat
                                    @endif
                                </h5>
                                
                                @if($model->is_eligible)
                                    <div class="mb-2">
                                        <strong>Rumus:</strong> {{ $model->formatted_base_fare }} × {{ $model->jarak_bulat }} km × {{ $model->hari_kerja }} hari
                                    </div>
                                @else
                                    <div class="mb-2">
                                        <strong>Alasan tidak memenuhi syarat:</strong>
                                        <ul class="mb-0 mt-1">
                                            @if($model->employee->jenis_pegawai !== 'Tetap')
                                                <li>Bukan pegawai tetap</li>
                                            @endif
                                            @if($model->hari_kerja < 19)
                                                <li>Hari kerja kurang dari 19 hari ({{ $model->hari_kerja }} hari)</li>
                                            @endif
                                            @if($model->jarak < 5)
                                                <li>Jarak kurang dari 5 km ({{ number_format($model->jarak, 2) }} km)</li>
                                            @endif
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="col-md-4 text-end">
                                <div class="display-6 fw-bold">
                                    {{ $model->formatted_tunjangan }}
                                </div>
                                <small class="text-muted">Total Tunjangan</small>
                            </div>
                        </div>
                    </div>

                    @if($model->keterangan)
                        <div class="mt-3">
                            <h6 class="text-muted">Keterangan</h6>
                            <p class="mb-0">{{ $model->keterangan }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="card-footer bg-light">
            <div class="row">
                <div class="col-md-6">
                    <small class="text-muted">
                        <i class="bi bi-calendar"></i> Dibuat: {{ $model->created_at->format('d F Y H:i') }}
                    </small>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">
                        <i class="bi bi-clock"></i> Diupdate: {{ $model->updated_at->format('d F Y H:i') }}
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h6 class="mb-0">Ketentuan Tunjangan Transport</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-success">Syarat Mendapat Tunjangan:</h6>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-check-circle text-success"></i> Pegawai tetap</li>
                        <li><i class="bi bi-check-circle text-success"></i> Minimal 19 hari kerja</li>
                        <li><i class="bi bi-check-circle text-success"></i> Jarak minimal 5 km</li>
                        <li><i class="bi bi-check-circle text-success"></i> Jarak maksimal 25 km</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="text-info">Aturan Pembulatan:</h6>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-info-circle text-info"></i> Desimal < 0.5 → dibulatkan ke bawah</li>
                        <li><i class="bi bi-info-circle text-info"></i> Desimal ≥ 0.5 → dibulatkan ke atas</li>
                    </ul>
                    
                    <h6 class="text-primary mt-3">Rumus:</h6>
                    <div class="bg-light p-2 rounded">
                        <code>Tunjangan = Tarif × Jarak (bulat) × Hari Kerja</code>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection