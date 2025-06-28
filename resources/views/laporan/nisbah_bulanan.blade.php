@extends('layouts.master')
@section('title', 'Laporan Nisbah')
@section('subtitle', 'Bulanan')
@section('transaksi_pinjaman', '')

@section('content')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body pt-4">
                <form method="GET" class="row g-3 mb-3">
                    <div class="col-md-3">
                        <select name="tahun" class="form-select">
                            @foreach ($list_tahun as $th)
                                <option value="{{ $th }}" {{ $th == $tahun ? 'selected' : '' }}>{{ $th }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="bulan" class="form-select">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $i == $bulan ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-6 text-end">
                        <button class="btn btn-primary">Tampilkan</button>
                        <a href="{{ route('laporan.nisbah.bulanan.pdf', ['tahun' => $tahun, 'bulan' => $bulan]) }}"
                            class="btn btn-danger" target="_blank"><i class="bi bi-file-earmark-pdf"></i> Export PDF</a>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode Nisbah</th>
                                <th>Periode</th>
                                <th>Besaran Nisbah</th>
                                <th>Total Pendapatan</th>
                                <th>Pendapatan Dibagi</th>
                                <th>Pendapatan Ditahan</th>
                                <th>Jumlah Hari</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($laporan as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item['kode_nisbah'] }}</td>
                                    <td>{{ $item['periode'] }}</td>
                                    <td>Rp {{ number_format($item['besaran_nisbah'], 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item['total_pendapatan'], 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item['pendapatan_dibagi'], 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item['pendapatan_ditahan'], 0, ',', '.') }}</td>
                                    <td>{{ $item['jumlah_hari'] }}</td>
                                </tr>
                            @endforeach
                            @if ($laporan->isEmpty())
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak ditemukan.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
