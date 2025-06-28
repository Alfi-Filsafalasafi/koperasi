@extends('layouts.master')
@section('title', 'Transaksi Simpanan')
@section('subtitle', 'Daftar')

@section('transaksi_simpanan', '') {{-- sesuaikan jika ada pada sidebar --}}
@section('content')
    <div class="col-lg-12">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body pt-4">
                <a href="{{ route('transaksi-simpanan.create') }}" class="btn btn-sm btn-success my-3">
                    <i class="bi bi-plus"></i> Tambah
                </a>
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Transaksi</th>
                                <th>ID Simpanan</th>
                                <th>Tanggal</th>
                                <th>Nama Anggota</th>
                                <th>Jenis Simpanan</th>
                                <th>Jenis Transaksi</th>
                                <th>Jumlah</th>
                                <th>Petugas</th>
                                <th><i class="bi bi-gear"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->id_transaksi_simpanan }}</td>
                                    <td>{{ $data->simpanan->id_simpanan }}</td>
                                    <td>{{ $data->tanggal }}</td>
                                    <td>{{ $data->simpanan->anggota->nama_lengkap ?? '-' }}</td>
                                    <td>{{ ucfirst($data->simpanan->jenis_simpanan) ?? '-' }}</td>
                                    <td>{{ ucfirst($data->jenis_transaksi) }}</td>
                                    <td>Rp {{ number_format($data->jumlah, 2, ',', '.') }}</td>
                                    <td>{{ $data->petugas->nama_lengkap ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('transaksi-simpanan.edit', $data->id) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('transaksi-simpanan.destroy', $data->id) }}"
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
