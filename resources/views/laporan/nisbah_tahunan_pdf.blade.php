<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Nisbah Tahun {{ $tahun }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #333;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        h1 {
            text-align: center;
            margin-bottom: 0;
        }

        h2 {
            text-align: center;
            margin-bottom: 0;
        }

        .header {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <h1>KSP Abdi Guna Artha</h1>
    <h2>Laporan Nisbah Tahunan</h2>
    <h2 class="header">Tahun: {{ $tahun }}</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Nisbah</th>
                <th>Periode</th>
                <th>Besaran Nisbah</th>
                <th>Total Pendapatan</th>
                <th>Pendapatan Dibagi (70%)</th>
                <th>Pendapatan Ditahan (30%)</th>
                <th>Jumlah Hari</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporan as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row['kode_nisbah'] }}</td>
                    <td>{{ $row['periode'] }}</td>
                    <td>Rp {{ number_format($row['besaran_nisbah'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($row['total_pendapatan'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($row['pendapatan_dibagi'], 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($row['pendapatan_ditahan'], 0, ',', '.') }}</td>
                    <td>{{ $row['jumlah_hari'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
