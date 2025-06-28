<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Kas Keluar {{ $tahun }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        h1 {
            text-align: center;
            margin-bottom: 10px;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .subtotal {
            font-weight: bold;
            background-color: #e0e0e0;
        }

        .total {
            font-weight: bold;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <h1>KSP Abdi Guna Artha</h1>
    <h2>Laporan Jurnal Kas Keluar</h2>
    <h2>Tahun {{ $tahun }}</h2>

    @php
        $totalDebit = 0;
        $totalKredit = 0;
    @endphp

    @foreach ($datas as $bulan => $items)
        <h3>{{ $bulan }}</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>No Bukti</th>
                    <th>Anggota</th>
                    <th>Uraian</th>
                    <th>Akun Debit</th>
                    <th>Akun Kredit</th>
                    <th>Nominal Debit</th>
                    <th>Nominal Kredit</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $subDebit = 0;
                    $subKredit = 0;
                @endphp

                @foreach ($items as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                        <td>{{ $item->no_bukti }}</td>
                        <td>{{ $item->anggota->nama_lengkap ?? '-' }}</td>
                        <td>{{ $item->uraian }}</td>
                        <td>{{ $item->akun_debit }}</td>
                        <td>{{ $item->akun_kredit }}</td>
                        <td>Rp {{ number_format($item->nominal_debit, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item->nominal_kredit, 0, ',', '.') }}</td>
                    </tr>
                    @php
                        $subDebit += $item->nominal_debit;
                        $subKredit += $item->nominal_kredit;
                    @endphp
                @endforeach

                <tr class="subtotal">
                    <td colspan="7">Subtotal Bulan {{ $bulan }}</td>
                    <td>Rp {{ number_format($subDebit, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($subKredit, 0, ',', '.') }}</td>
                </tr>

                @php
                    $totalDebit += $subDebit;
                    $totalKredit += $subKredit;
                @endphp
            </tbody>
        </table>
    @endforeach

    <table>
        <tr class="total">
            <td colspan="7">TOTAL KESELURUHAN</td>
            <td>Rp {{ number_format($totalDebit, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($totalKredit, 0, ',', '.') }}</td>
        </tr>
    </table>
</body>

</html>
