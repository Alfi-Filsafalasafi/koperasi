@extends('layouts.master')
@section('title', 'Pinjaman')
@section('subtitle', 'Tambah')

@section('pinjaman', '') {{-- Sesuaikan jika perlu --}}
@section('content')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Tambah Pinjaman</h5>

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

                <form action="{{ route('pinjaman.store') }}" method="POST" class="row g-3 needs-validation" novalidate>
                    @csrf

                    <div class="col-md-6">
                        <label for="anggota_id" class="form-label">Nama Anggota</label>
                        <select name="anggota_id" id="anggota_id" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            @foreach ($anggota as $item)
                                <option value="{{ $item->id }}" {{ old('anggota_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                        @error('anggota_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="jenis_pinjaman" class="form-label">Jenis Pinjaman</label>
                        <select name="jenis_pinjaman" id="jenis_pinjaman" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="Pinjaman Modal Usaha"
                                {{ old('jenis_pinjaman') == 'Pinjaman Modal Usaha' ? 'selected' : '' }}>Pinjaman Modal Usaha
                            </option>
                            <option value="Pinjaman Pembiayaan Multi Guna"
                                {{ old('jenis_pinjaman') == 'Pinjaman Pembiayaan Multi Guna' ? 'selected' : '' }}>Pinjaman
                                Pembiayaan Multi Guna
                            </option>
                            <option value="Pinjaman Pembiayaan Umroh"
                                {{ old('jenis_pinjaman') == 'Pinjaman Pembiayaan Umroh' ? 'selected' : '' }}>Pinjaman
                                Pembiayaan Umroh
                            </option>
                        </select>
                        @error('jenis_pinjaman')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tanggal_pinjaman" class="form-label">Tanggal Pinjaman</label>
                        <input type="date" name="tanggal_pinjaman" class="form-control" id="tanggal_pinjaman"
                            value="{{ old('tanggal_pinjaman', date('Y-m-d')) }}" required>
                        @error('tanggal_pinjaman')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="jangka_waktu" class="form-label">Jangka Waktu</label>

                        <select name="jangka_waktu" id="jangka_waktu" class="form-select" required>
                            <option value="">-- Pilih Jangka Waktu --</option>
                            @foreach ([12, 18, 24, 30, 36] as $bulan)
                                <option value="{{ $bulan }}" {{ old('jangka_waktu') == $bulan ? 'selected' : '' }}>
                                    {{ $bulan }} Bulan
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="jumlah_pinjaman" class="form-label">Jumlah Pinjaman</label>
                        <input type="number" name="jumlah_pinjaman" class="form-control" id="jumlah_pinjaman"
                            value="{{ old('jumlah_pinjaman') }}" required>
                        @error('jumlah_pinjaman')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="bunga" class="form-label">Bunga (%)</label>
                        <div class="input-group">
                            <input type="number" name="bunga" class="form-control" id="bunga"
                                value="{{ old('bunga', 1.5) }}" step="0.01" required>
                            <span class="input-group-text">/ bulan</span>
                        </div>
                        @error('bunga')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="nisbah" class="form-label">Nisbah</label>
                        <div class="input-group">
                            <input type="number" name="nisbah" class="form-control" id="nisbah"
                                value="{{ old('nisbah') }}" readonly>
                            <div class="input-group-text">/ bulan</div>
                        </div>
                        @error('nisbah')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="col-md-6">
                        <label for="angsuran_pokok" class="form-label">Angsuran Pokok</label>
                        <input type="number" name="angsuran_pokok" class="form-control" id="angsuran_pokok"
                            value="{{ old('angsuran_pokok') }}" required>
                        @error('angsuran_pokok')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="total_pinjaman" class="form-label">Total Pinjaman</label>
                        <input type="number" name="total_pinjaman" class="form-control" id="total_pinjaman"
                            value="{{ old('total_pinjaman') }}" readonly>
                        @error('total_pinjaman')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal_jatuh_tempo" class="form-label">Tanggal Jatuh Tempo</label>
                        <input type="date" name="tanggal_jatuh_tempo" class="form-control" id="tanggal_jatuh_tempo"
                            value="{{ old('tanggal_jatuh_tempo') }}" required readonly>
                        @error('tanggal_jatuh_tempo')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="text-start">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('pinjaman.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @include('pinjaman._form_script')
@endsection
