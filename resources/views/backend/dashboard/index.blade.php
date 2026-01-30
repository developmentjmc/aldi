@extends('backend/layouts/main', get_defined_vars())

@section('content')
@if (auth()->user()->role->name === 'Manager HRD' )
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Statistik Kepegawaian</h3>
                <div class="row g-4">
                    <div class="col-6">
                        <div class="card border-0" style="background-color: #333A73;">
                            <div class="card-body text-white text-center">
                                <h3 class="h1 fw-bolder mb-1">Total Pegawai</h3>
                                <p class="fs-1">{{ $totalPegawai }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0" style="background-color: #009FBD">
                            <div class="card-body text-white text-center">
                                <h3 class="h1 fw-bolder mb-1">Total Pegawai Kontrak</h3>
                                <p class="fs-1">{{ $totalPegawaiKontrak }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0" style="background-color: #FBA834;">
                            <div class="card-body text-white text-center">
                                <h3 class="h1 fw-bolder mb-1">Total Pegawai Tetap</h3>
                                <p class="fs-1">{{ $totalPegawaiTetap }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card border-0" style="background-color: #DD5746">
                            <div class="card-body text-white text-center">
                                <h3 class="h1 fw-bolder mb-1">Total Peserta Magang</h3>
                                <p class="fs-1">{{ $totalPesertaMagang }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mt-3">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Visualisasi Perbandingan Jenis Pegawai</h3>
                <div id="chartPegawai"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mt-3">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">5 Pegawai Terbaru</h3>
                <div class="table-responsive">
                    <table class="table table-vcenter table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Jenis Pegawai</th>
                                <th>Tanggal Masuk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pegawaiTerbaru as $index => $pegawai)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $pegawai->name }}</td>
                                <td>
                                    {{ $pegawai->jenis_pegawai }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($pegawai->tanggal_masuk)->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-3">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Peta Domisili Pegawai</h3>
                <div id="mapPegawai" style="height: 450px; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>
@endif

@if (auth()->user()->role->name != 'Manager HRD')
    Selamat Datang {{ auth()->user()->name }} - {{ auth()->user()->role->name }}
@endif

@endsection

@section('scripts')
@if (auth()->user()->role->name === 'Manager HRD')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var options = {
        series: [{{ $totalPegawaiKontrak }}, {{ $totalPegawaiTetap }}, {{ $totalPesertaMagang }}],
        chart: {
            type: 'donut',
            height: 350
        },
        labels: ['Pegawai Kontrak', 'Pegawai Tetap', 'Peserta Magang'],
        colors: ['#009FBD', '#FBA834', '#DD5746'],
        legend: {
            position: 'bottom'
        },
        dataLabels: {
            enabled: true,
            formatter: function (val, opts) {
                return opts.w.config.series[opts.seriesIndex]
            }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total Pegawai',
                            fontSize: '18px',
                            fontWeight: 600,
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => {
                                    return a + b
                                }, 0)
                            }
                        }
                    }
                }
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    height: 300
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    var chart = new ApexCharts(document.querySelector("#chartPegawai"), options);
    chart.render();

    //bagian map
    var map = L.map('mapPegawai').setView([-7.7956, 110.3695], 11); // Jogja

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);

    var employees = @json($pegawaiDomisili);

    employees.forEach(function(employee) {
        if (employee.latitude && employee.longitude) {
            var markerColor = '#3388ff'; 
            
            var customIcon = L.divIcon({
                className: 'custom-marker',
                html: `<div style="background-color: ${markerColor}; width: 25px; height: 25px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>`,
                iconSize: [25, 25],
                iconAnchor: [12, 12]
            });

            var marker = L.marker([employee.latitude, employee.longitude], {
                icon: customIcon
            }).addTo(map);

            // pop upp
            var jenisLabel = employee.jenis_pegawai.charAt(0).toUpperCase() + employee.jenis_pegawai.slice(1);
            marker.bindPopup(`
                <strong>${employee.name}</strong><br>
                <span class="badge" style="background-color: ${markerColor};color:white">${jenisLabel}</span><br>
                <small>${employee.alamat || 'Alamat tidak tersedia'}</small>
            `);
        }
    });

    // ngatur point2nya
    if (employees.length > 0) {
        var bounds = [];
        employees.forEach(function(employee) {
            if (employee.latitude && employee.longitude) {
                bounds.push([employee.latitude, employee.longitude]);
            }
        });
        if (bounds.length > 0) {
            map.fitBounds(bounds, { padding: [50, 50] });
        }
    }
});
</script>
@endif
@endsection
