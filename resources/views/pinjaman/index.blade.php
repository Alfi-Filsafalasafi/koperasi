@extends('layouts.master')
@section('title', 'Pinjaman')
@section('subtitle', 'Daftar')

@section('dashboard', 'collapsed')
@section('anggota', 'collapsed')
@section('simpanan', 'collapsed')
@section('transaksi_simpanan', 'collapsed')
@section('pinjaman', '')
@section('transaksi_pinjaman', 'collapsed')
@section('jurnal_kas_masuk', 'collapsed')
@section('jurnal_kas_keluar', 'collapsed')
@section('laporan_nisbah_tahun', 'collapsed')
@section('laporan_nisbah_bulan', 'collapsed')

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
                <a href="{{ route('pinjaman.create') }}" class="btn btn-sm btn-success my-3">
                    <i class="bi bi-plus"></i> Tambah
                </a>
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>ID Pinjaman</th>
                                <th>Nama Anggota</th>
                                <th>Jenis</th>
                                <th>Jumlah</th>
                                <th>Bunga</th>
                                <th>Nisbah</th>
                                <th>Waktu</th>
                                <th>Tgl Pinjam</th>
                                <th>Jatuh Tempo</th>
                                <th>Total</th>
                                <th>Sisa</th>
                                <th>Status</th>
                                <th><i class="bi bi-gear"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datas as $data)
                                <tr>
                                    <td>{{ $data->id_pinjaman }}</td>
                                    <td>{{ $data->anggota->nama_lengkap ?? '-' }}</td>
                                    <td>{{ ucfirst($data->jenis_pinjaman) }}</td>
                                    <td>Rp {{ number_format($data->jumlah_pinjaman, 2, ',', '.') }}</td>
                                    <td>{{ $data->bunga }}%</td>
                                    <td>Rp {{ number_format($data->nisbah, 2, ',', '.') }}</td>
                                    <td>{{ $data->jangka_waktu }}</td>
                                    <td>{{ $data->tanggal_pinjaman }}</td>
                                    <td>{{ $data->tanggal_jatuh_tempo }}</td>
                                    <td>Rp {{ number_format($data->total_pinjaman, 2, ',', '.') }}</td>
                                    <td>Rp {{ number_format($data->sisa_pinjaman, 2, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $data->status == 'lunas' ? 'success' : 'warning' }}">
                                            {{ ucfirst($data->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('pinjaman.edit', $data->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('pinjaman.destroy', $data->id) }}"
                                            class="d-inline form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger btn-delete"
                                                data-id="{{ $data->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
