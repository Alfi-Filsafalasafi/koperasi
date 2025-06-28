@extends('layouts.master')
@section('title', 'Transaksi Pinjaman')
@section('subtitle', 'Daftar')

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
                <a href="{{ route('transaksi-pinjaman.create') }}" class="btn btn-sm btn-success my-3">
                    <i class="bi bi-plus"></i> Tambah
                </a>
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Transaksi</th>
                                <th>ID Pinjaman</th>
                                <th>Anggota</th>
                                <th>Tanggal Transaksi</th>
                                <th>Cicilan Ke</th>
                                <th>Pembayaran Pokok</th>
                                <th>Pembayaran Bunga</th>
                                <th>Pembayaran Denda</th>
                                <th>Pembayaran Total</th>
                                <th>Keterangan</th>
                                <th>Petugas</th>
                                <th><i class="bi bi-gear"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datas as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td> {{ $data->id_transaksi_pinjaman }}</td>
                                    <td>{{ $data->pinjaman->id_pinjaman }}</td>
                                    <td>{{ $data->pinjaman->anggota->nama_lengkap ?? '-' }}</td>
                                    <td>{{ $data->tanggal_bayar }}</td>
                                    <td>{{ $data->cicilan_ke }}</td>
                                    <td>Rp {{ number_format($data->pembayaran_pokok, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($data->pembayaran_nisbah, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($data->pembayaran_denda, 0, ',', '.') }}</td>
                                    <td>Rp
                                        {{ number_format($data->pembayaran_pokok + $data->pembayaran_nisbah + $data->pembayaran_denda, 0, ',', '.') }}
                                    </td>
                                    <td>{{ $data->keterangan }}</td>
                                    <td>{{ $data->petugas->nama_lengkap ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('transaksi-pinjaman.edit', $data->id) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('transaksi-pinjaman.destroy', $data->id) }}"
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
