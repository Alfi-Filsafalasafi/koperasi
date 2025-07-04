<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Kas Masuk {{ $tahun }}</title>
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
            padding: 10px;
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
    <h2>Laporan Jurnal Kas Masuk</h2>
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
                    <th colspan="2">Keterangan</th>
                    <th>Debit</th>
                    <th>Kredit</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $subDebit = 0;
                    $subKredit = 0;
                @endphp

                @foreach ($items as $i => $item)
                    @php
                        $subDebit += $item->nominal_debit;
                        $subKredit += $item->nominal_kredit;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                        <td colspan="2">{{ $item->akun_debit }}</td>
                        <td>
                            Rp {{ number_format($item->nominal_debit, 0, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                    @if ($item->akun_kredit[0] == '2')
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ explode('-', $item->akun_kredit)[1] }}</td>
                            <td></td>
                            <td>
                                Rp {{ number_format($item->nominal_kredit, 0, ',', '.') }}
                            </td>

                        </tr>
                    @else
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ explode('-', $item->akun_kredit)[1] }}</td>
                            <td></td>
                            <td>
                                Rp {{ number_format($item->pembayaran_pokok, 0, ',', '.') }}
                            </td>

                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Pendapatan Nisbah</td>
                            <td></td>
                            <td>
                                Rp {{ number_format($item->pembayaran_bunga, 0, ',', '.') }}
                            </td>

                        </tr>
                        @if ($item->pembayaran_denda != 0)
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Pendapatan Denda</td>
                                <td></td>
                                <td>
                                    Rp {{ number_format($item->pembayaran_bunga, 0, ',', '.') }}
                                </td>

                            </tr>
                        @endif
                    @endif
                @endforeach

                <tr class="subtotal">
                    <td colspan="4">Subtotal Bulan {{ $bulan }}</td>
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
