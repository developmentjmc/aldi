@php

$title = 'Form Tunjangan Transport';
$indexHref = route('backend.tunjangan-transport.index');
$breadcrumbs[] = 'Tunjangan Transport';

if (isset($model->id)) {
    $submitHref = route('backend.tunjangan-transport.update', ['tunjangan_transport' => $model->id]);
    $breadcrumbs[] = 'Update';
} else {
    $submitHref = route('backend.tunjangan-transport.store');
    $breadcrumbs[] = 'Create';
}

@endphp

@extends('backend/layouts/main', get_defined_vars())

@section('content')
<form method="POST" action="{{ $submitHref }}" id="form">
    @csrf
    @if ($model->id)
        @method('put')
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-5">
        <div class="card-body">
            <h5 class="mb-4">Data Tunjangan Transport</h5>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label required">Pegawai</label>
                    <select name="employee_id" class="form-select">
                        <option value="">Pilih Pegawai</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" 
                                data-latitude="{{ $employee->latitude }}"
                                data-longitude="{{ $employee->longitude }}"
                                {{ old('employee_id', $model->employee_id ?? '') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->nip }} - {{ $employee->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Rumah Pegawai</label>
                    <input type="text" class="form-control rumah-pegawai" readonly>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label required">Tarif Dasar per KM</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="base_fare" class="form-control" step="0.01" min="0" readonly value="{{ old('base_fare', $model->base_fare ?? $baseFare) }}" required>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Kantor</label>
                            <select name="kantor" id="" class="form-select">
                                <option value="">Pilih...</option>
                                @foreach ($gedung as $item)
                                <option value="{{ $item->name }}" 
                                    {{ old('kantor', $model->kantor ?? '') == $item->name ? 'selected' : '' }}
                                    data-lokasi="{{ $item->description }}">
                                    {{ $item->name }}
                                </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label required">Jarak (Dari Gedung)</label>
                    <div class="input-group">
                        <input type="number" name="jarak" class="form-control" step="0.01" min="0"
                               value="{{ old('jarak', $model->jarak ?? '') }}" placeholder="12.5">
                        <span class="input-group-text">km</span>
                    </div>
                    <div class="form-text">Jarak dari rumah ke kantor. Max 25km untuk perhitungan tunjangan.</div>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label required">Jumlah Hari Masuk Kerja</label>
                    <input type="number" name="hari_kerja" class="form-control" min="0" max="31"
                           value="{{ old('hari_kerja', $model->hari_kerja ?? '') }}" placeholder="22">
                    <div class="form-text">Minimal 19 hari untuk mendapat tunjangan transport.</div>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label required">Bulan Tunjangan</label>
                    <input type="month" name="bulan_tunjangan" class="form-control"
                           value="{{ old('bulan_tunjangan', isset($model->bulan_tunjangan) ? \Carbon\Carbon::parse($model->bulan_tunjangan)->format('Y-m') : '') }}">
                    <div class="invalid-feedback"></div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3" 
                              placeholder="Keterangan tambahan (opsional)">{{ old('keterangan', $model->keterangan ?? '') }}</textarea>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            @if($model->id)
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info">
                        <h6>Hasil Perhitungan:</h6>
                        <ul class="mb-0">
                            <li><strong>Jarak Bulat:</strong> {{ $model->jarak_bulat }} km</li>
                            <li><strong>Total Tunjangan:</strong> Rp {{ number_format($model->tunjangan, 0, ',', '.') }}</li>
                            <li><strong>Status:</strong> 
                                @if($model->is_eligible)
                                    <span class="badge bg-success">Memenuhi Syarat</span>
                                @else
                                    <span class="badge bg-warning text-white">Tidak Memenuhi Syarat</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <div class="alert alert-warning">
                <h6>Ketentuan Tunjangan Transport:</h6>
                <ul class="mb-0">
                    <li>Minimal 19 hari kerja dalam sebulan</li>
                    <li>Jarak minimal 5 km dari rumah ke kantor</li>
                    <li>Jarak maksimal yang dihitung adalah 25 km</li>
                    <li>Hanya untuk pegawai tetap</li>
                    <li>Rumus: Tarif Dasar × Jarak (dibulatkan) × Hari Kerja</li>
                </ul>
            </div>
        </div>

        <div class="card-footer">
            <div class="d-flex justify-content-between">
                <a href="{{ $indexHref }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
    @parent
    <script type="module">

        // on change
        function updatePegawaiDanKantor() {
            const employeeSelect = document.querySelector('select[name="employee_id"]');
            let latitude = '';
            let longitude = '';
            let kantorLat = '';
            let kantorLong = '';
            
            const kantorSelect   = document.querySelector('select[name="kantor"]');

            if (employeeSelect && employeeSelect.value) {
                const selectedOption = employeeSelect.options[employeeSelect.selectedIndex];
                latitude  = selectedOption.getAttribute('data-latitude')  || '';
                longitude = selectedOption.getAttribute('data-longitude') || '';
                document.querySelector('.rumah-pegawai').value = `Lat: ${latitude}, Long: ${longitude}`;
            } else {
                document.querySelector('.rumah-pegawai').value = '';
            }

            if (kantorSelect && kantorSelect.value) {
                const kantorOption   = kantorSelect.options[kantorSelect.selectedIndex];
                const kantorLatLong = kantorOption.getAttribute('data-lokasi') || '';
                kantorLat= kantorLatLong.split(',')[0] || '';
                kantorLong= kantorLatLong.split(',')[1] || '';
            }

            // itung jarak otomatis jika kedua data ada
            console.log(latitude, longitude, kantorLat, kantorLong);
            
            if (latitude && longitude && kantorLat && kantorLong) {
                const R = 6371; 
                const dLat = (kantorLat - latitude) * Math.PI / 180;
                const dLon = (kantorLong - longitude) * Math.PI / 180;
                const a = 
                    Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(latitude * Math.PI / 180) * Math.cos(kantorLat * Math.PI / 180) *
                    Math.sin(dLon/2) * Math.sin(dLon/2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                const distance = R * c; 

                document.querySelector('input[name="jarak"]').value = distance.toFixed(2);
                calculatePreview();
            }
            
        }

        ['employee_id', 'kantor'].forEach(name => {
            const el = document.querySelector(`select[name="${name}"]`);
            if (el) {
                el.addEventListener('change', updatePegawaiDanKantor);
            }
        });

        const inputFields = document.querySelectorAll('input[name="base_fare"], input[name="jarak"], input[name="hari_kerja"]');
        inputFields.forEach(input => {
            input.addEventListener('input', calculatePreview);
        });

        function calculatePreview() {
            const baseFare = parseFloat(document.querySelector('input[name="base_fare"]').value) || 0;
            const jarak = parseFloat(document.querySelector('input[name="jarak"]').value) || 0;
            const hariKerja = parseInt(document.querySelector('input[name="hari_kerja"]').value) || 0;

            let eligible = true;
            let messages = [];

            if (hariKerja < 19) {
                eligible = false;
                messages.push('Hari kerja kurang dari 19 hari');
            }

            if (jarak < 5) {
                eligible = false;
                messages.push('Jarak kurang dari 5 km');
            }

            let jarakEfektif = Math.min(jarak, 25);
            let decimal = jarakEfektif - Math.floor(jarakEfektif);
            let jarakBulat = decimal < 0.5 ? Math.floor(jarakEfektif) : Math.ceil(jarakEfektif);
            
            let tunjangan = eligible ? baseFare * jarakBulat * hariKerja : 0;

            if (baseFare > 0 && jarak > 0 && hariKerja > 0) {
                let previewHtml = `
                    <div class="alert alert-info mt-3 preview-calculation">
                        <h6>Preview Perhitungan:</h6>
                        <ul class="mb-0">
                            <li><strong>Jarak Efektif:</strong> ${jarakEfektif.toFixed(2)} km (max 25km)</li>
                            <li><strong>Jarak Bulat:</strong> ${jarakBulat} km</li>
                            <li><strong>Estimasi Tunjangan:</strong> Rp ${tunjangan.toLocaleString('id-ID')}</li>
                            ${!eligible ? `<li class="text-danger"><strong>Status:</strong> ${messages.join(', ')}</li>` : ''}
                        </ul>
                    </div>
                `;
                
                const existingPreview = document.querySelector('.preview-calculation');
                if (existingPreview) {
                    existingPreview.remove();
                }
                
                const alertWarning = document.querySelector('.card-body .alert-warning');
                if (alertWarning) {
                    alertWarning.insertAdjacentHTML('afterend', previewHtml);
                }
            } else {
                const existingPreview = document.querySelector('.preview-calculation');
                if (existingPreview) {
                    existingPreview.remove();
                }
            }
        }

        // trigger change on load data
        document.addEventListener('DOMContentLoaded', function() {
            updatePegawaiDanKantor();
        });
        // jmc preset 
		const dataJson = jsonScriptToFormFields('#form', '#data');

		$('#form').formAjaxSubmit();
    </script>
@endsection