@extends('layouts.master')
@section('title', 'Anggota')
@section('subtitle', 'Daftar')

@section('dashboard', 'collapsed')
@section('anggota', '')
@section('simpanan', 'collapsed')
@section('transaksi_simpanan', 'collapsed')
@section('pinjaman', 'collapsed')
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
                <a href="{{ route('anggota.create') }}" class="btn btn-sm btn-success my-3">
                    <i class="bi bi-plus"></i> Tambah
                </a>
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Anggota</th>
                                <th>Nama Lengkap</th>
                                <th>No KTP</th>
                                <th>P/L</th>
                                <th>TTL</th>
                                <th>Alamat</th>
                                <th>Pekerjaan</th>
                                <th>Tgl Masuk</th>
                                <th>No Telp</th>
                                <th><i class="bi bi-gear"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->id_anggota }}</td>
                                    <td>{{ $data->nama_lengkap }}</td>
                                    <td>{{ $data->no_ktp }}</td>
                                    <td>{{ $data->jenis_kelamin }}</td>
                                    <td>{{ $data->tempat_lahir }}, {{ $data->tanggal_lahir }}</td>
                                    <td>{{ $data->alamat }}</td>
                                    <td>{{ $data->pekerjaan }}</td>
                                    <td>{{ $data->tanggal_masuk }}</td>
                                    <td>{{ $data->no_telp }}</td>
                                    <td>
                                        <a href="{{ route('anggota.edit', $data->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('anggota.destroy', $data->id) }}"
                                            class="form-delete d-inline">
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
