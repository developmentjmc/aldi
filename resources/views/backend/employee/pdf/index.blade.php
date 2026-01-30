<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Pegawai {{ now()->format('Y-m-d') }}</title>
</head>
<body>
    <h1>Data Pegawai {{ now()->format('Y-m-d') }}</h1>
    <table style="width: 100%;border: 1px solid black;border-collapse: collapse;">
        <thead>
            <tr>
                <th style="border: 1px solid black;">No</th>
                <th style="border: 1px solid black;">NIP</th>
                <th style="border: 1px solid black;">Nama</th>
                <th style="border: 1px solid black;">Jabatan</th>
                <th style="border: 1px solid black;">Jenis Pegawai</th>
                <th style="border: 1px solid black;">Masa Kerja (Tahun)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($models as $index => $model)
                <tr>
                    <td style="border: 1px solid black; text-align: center;">{{ $index + 1 }}</td>
                    <td style="border: 1px solid black;">{{ $model->nip }}</td>
                    <td style="border: 1px solid black;">{{ $model->name }}</td>
                    <td style="border: 1px solid black;">{{ $model->jabatan }}</td>
                    <td style="border: 1px solid black;">{{ $model->jenis_pegawai }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $model->masa_kerja }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>