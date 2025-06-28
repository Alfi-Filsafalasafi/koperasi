@extends('layouts.master')
@section('title', 'Laporan Nisbah')
@section('subtitle', 'Tahunan')

@section('transaksi_pinjaman', '') {{-- Sesuaikan dengan menu aktif --}}
@section('content')
    <div class="col-lg-12">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body pt-4">

                {{-- Form Filter Tahun dan Export --}}
                <form method="GET" action="{{ route('laporan.nisbah.tahunan') }}" class="row g-3 align-items-center mb-4">
                    <div class="col-auto">
                        <label for="tahun" class="col-form-label">Pilih Tahun:</label>
                    </div>
                    <div class="col-auto">
                        <select name="tahun" id="tahun" class="form-select">
                            @foreach ($list_tahun as $tahunItem)
                                <option value="{{ $tahunItem }}" {{ $tahunItem == $tahun ? 'selected' : '' }}>
                                    {{ $tahunItem }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-filter"></i> Filter</button>
                        <a href="{{ route('laporan.nisbah.tahunan.pdf', ['tahun' => $tahun]) }}" target="_blank"
                            class="btn btn-danger">
                            <i class="bi bi-file-earmark-pdf"></i> Export PDF
                        </a>
                    </div>
                </form>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Nisbah</th>
                                <th>Periode (Tahun)</th>
                                <th>Besaran Nisbah</th>
                                <th>Total Pendapatan</th>
                                <th>Pendapatan Dibagi</th>
                                <th>Pendapatan Ditahan</th>
                                <th>Jumlah Hari</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($laporan as $row)
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
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
