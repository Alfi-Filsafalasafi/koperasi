@extends('layouts.master')
@section('title', 'Jurnal Kas Masuk')
@section('subtitle', 'Daftar')

@section('dashboard', 'collapsed')
@section('anggota', 'collapsed')
@section('simpanan', 'collapsed')
@section('transaksi_simpanan', 'collapsed')
@section('pinjaman', 'collapsed')
@section('transaksi_pinjaman', 'collapsed')
@section('jurnal_kas_masuk', '')
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
            <div class="card-body pt-4 text-end">
                <form class="d-flex justify-content-end align-items-center mb-3" method="GET"
                    action="{{ route('jurnal-kas-masuk.index') }}">
                    <div class="me-2">
                        <select name="tahun" class="form-select" onchange="this.form.submit()">
                            @foreach ($tahunList as $th)
                                <option value="{{ $th }}" {{ $tahun == $th ? 'selected' : '' }}>
                                    {{ $th }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <a href="{{ route('jurnal-kas-masuk.cetak', ['tahun' => $tahun]) }}" target="_blank"
                            class="btn btn-sm btn-warning">
                            <i class="bi bi-printer-fill"></i> Cetak PDF
                        </a>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>ID Anggota</th>
                                <th>Nama Anggota</th>
                                <th>Tanggal</th>
                                <th>No Bukti</th>
                                <th>Uraian</th>
                                <th>Akun Debit</th>
                                <th>Akun Kredit</th>
                                <th>Nominal Debit</th>
                                <th>Nominal Kredit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datas as $data)
                                <tr>
                                    <td>{{ $data->anggota->id_anggota ?? '-' }}</td>
                                    <td>{{ $data->anggota->nama_lengkap ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data->tanggal)->format('d-m-Y') }}</td>
                                    <td>{{ $data->no_bukti }}</td>
                                    <td>{{ $data->uraian }}</td>
                                    <td>{{ $data->akun_debit }}</td>
                                    <td>{{ $data->akun_kredit }}</td>
                                    <td>{{ $data->nominal_debit }}</td>
                                    <td>{{ $data->nominal_kredit }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
