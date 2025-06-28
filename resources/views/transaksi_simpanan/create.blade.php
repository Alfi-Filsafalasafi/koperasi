@extends('layouts.master')
@section('title', 'Transaksi Simpanan')
@section('subtitle', 'Tambah')

@section('transaksi_simpanan', '') {{-- sesuaikan dengan sidebar --}}
@section('content')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Tambah Transaksi Simpanan</h5>

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

                <form action="{{ route('transaksi-simpanan.store') }}" method="POST" class="row g-3 needs-validation"
                    novalidate>
                    @csrf

                    <div class="col-md-6">
                        <label for="simpanan_id" class="form-label">ID Simpanan</label>
                        <select name="simpanan_id" id="simpanan_id" class="form-select" required>
                            <option value="">-- Pilih ID Simpanan --</option>
                            @foreach ($simpanans as $simpanan)
                                <option value="{{ $simpanan->id }}"
                                    {{ old('simpanan_id') == $simpanan->id ? 'selected' : '' }}>
                                    {{ $simpanan->id_simpanan }}
                                    ({{ ucfirst($simpanan->anggota->nama_lengkap) }})
                                    -
                                    ({{ number_format($simpanan->total_saldo, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        @error('simpanan_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tanggal" class="form-label">Tanggal Transaksi</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control"
                            value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        @error('tanggal')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="jenis_transaksi" class="form-label">Jenis Transaksi</label>
                        <select name="jenis_transaksi" id="jenis_transaksi" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="setor" {{ old('jenis_transaksi') == 'setor' ? 'selected' : '' }}>Setor</option>
                            <option value="tarik" {{ old('jenis_transaksi') == 'tarik' ? 'selected' : '' }}>Tarik</option>
                        </select>
                        @error('jenis_transaksi')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" name="jumlah" id="jumlah" class="form-control" min="0"
                            max="{{ $simpanan->total_saldo }}" value="{{ old('jumlah') }}" required>
                        @error('jumlah')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="col-md-12">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="2">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="user_id" class="form-label">Petugas Input</label>
                        <select name="user_id" id="user_id" class="form-select" required>
                            <option value="">-- Pilih Petugas --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->nama_lengkap ?? $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="text-start">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('transaksi-simpanan.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
