@extends('layouts.master')
@section('title', 'Simpanan')
@section('subtitle', 'Tambah')

@section('dashboard', 'collapsed')
@section('anggota', 'collapsed')
@section('simpanan', '')
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
                <h5 class="card-title">Tambah Simpanan</h5>

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

                <form action="{{ route('simpanan.store') }}" method="POST" class="row g-3 needs-validation" novalidate>
                    @csrf

                    <div class="col-md-6">
                        <label for="id_simpanan" class="form-label">Kategori Simpanan</label>
                        <select name="id_simpanan" id="id_simpanan" class="form-select" required>
                            <option value="">-- Pilih ID Simpanan --</option>
                            <option value="SIRAYA" {{ old('id_simpanan') == 'SIRAYA' ? 'selected' : '' }}>
                                Simpanan Hari Raya</option>
                            <option value="SISUQUR" {{ old('id_simpanan') == 'SISUQUR' ? 'selected' : '' }}>
                                Simpanan Qurban</option>
                            <option value="SIRELA" {{ old('id_simpanan') == 'SIRELA' ? 'selected' : '' }}>
                                Simpanan Sukarela
                            </option>
                            <option value="SIMASJID" {{ old('id_simpanan') == 'SIMASJID' ? 'selected' : '' }}>
                                Simpanan Masjid
                            </option>
                            <option value="SIUMMA" {{ old('id_simpanan') == 'SIUMMA' ? 'selected' : '' }}>Simpanan
                                Umroh
                            </option>
                            <option value="SISUKA" {{ old('id_simpanan') == 'SISUKA' ? 'selected' : '' }}>
                                Simpanan Berjangka
                            </option>
                        </select>
                        @error('id_simpanan')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="anggota_id" class="form-label">Nama Anggota</label>
                        <select name="anggota_id" id="anggota_id" class="form-select" required>
                            <option value="">-- Pilih Anggota --</option>
                            @foreach ($anggotaList as $anggota)
                                <option value="{{ $anggota->id }}"
                                    {{ old('anggota_id') == $anggota->id ? 'selected' : '' }}>
                                    {{ $anggota->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                        @error('anggota_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="jenis_simpanan" class="form-label">Jenis Simpanan</label>
                        <select name="jenis_simpanan" id="jenis_simpanan" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Simpanan Pokok"
                                {{ old('jenis_simpanan') == 'Simpanan Pokok' ? 'selected' : '' }}>Simpanan Pokok</option>
                            <option value="Simpanan Wajib"
                                {{ old('jenis_simpanan') == 'Simpanan Wajib' ? 'selected' : '' }}>Simpanan Wajib</option>
                            <option value="Simpanan Sukarela"
                                {{ old('jenis_simpanan') == 'Simpanan Sukarela' ? 'selected' : '' }}>Simpanan Sukarela
                            </option>
                        </select>
                        @error('jenis_simpanan')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="saldo_awal" class="form-label">Saldo Awal</label>
                        <input type="number" name="saldo_awal" class="form-control" id="saldo_awal" min="0"
                            value="{{ old('saldo_awal', 0) }}" required>
                        @error('saldo_awal')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" id="keterangan" cols="3" rows="3"> {{ old('keterangan') }} </textarea>

                        @error('keterangan')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="text-start">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('simpanan.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
