@extends('layouts.master')
@section('title', 'Anggota')
@section('subtitle', 'Tambah')

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
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Tambah Anggota</h5>

                {{-- Alert Error Validation --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>Periksa kembali!</strong> Terdapat kesalahan pada form yang Anda isi:
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif


                <form action="{{ route('anggota.store') }}" method="POST" class="row g-3 needs-validation" novalidate>
                    @csrf

                    <div class="col-md-6">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" id="nama_lengkap"
                            value="{{ old('nama_lengkap') }}" required>
                        @error('nama_lengkap')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="no_ktp" class="form-label">No KTP</label>
                        <input type="text" name="no_ktp" class="form-control" id="no_ktp" value="{{ old('no_ktp') }}"
                            required>
                        @error('no_ktp')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control" id="tempat_lahir"
                            value="{{ old('tempat_lahir') }}" required>
                        @error('tempat_lahir')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" id="tanggal_lahir"
                            value="{{ old('tanggal_lahir') }}" required>
                        @error('tanggal_lahir')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" required>{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="pekerjaan" class="form-label">Pekerjaan</label>
                        <input type="text" name="pekerjaan" class="form-control" id="pekerjaan"
                            value="{{ old('pekerjaan') }}" required>
                        @error('pekerjaan')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" class="form-control" id="tanggal_masuk"
                            value="{{ old('tanggal_masuk') }}" required>
                        @error('tanggal_masuk')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="no_telp" class="form-label">Nomor Telepon</label>
                        <input type="text" name="no_telp" class="form-control" id="no_telp"
                            value="{{ old('no_telp') }}" required>
                        @error('no_telp')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="text-start">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('anggota.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
