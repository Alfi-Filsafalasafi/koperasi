@extends('layouts.master')
@section('title', 'Pinjaman')
@section('subtitle', 'Edit')

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
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Edit Pinjaman</h5>

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

                <form action="{{ route('pinjaman.update', $pinjaman->id) }}" method="POST" class="row g-3 needs-validation"
                    novalidate>
                    @csrf
                    @method('PUT')

                    <div class="col-md-6">
                        <label for="anggota_id" class="form-label">Nama Anggota</label>
                        <select name="anggota_id" id="anggota_id" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            @foreach ($anggota as $item)
                                <option value="{{ $item->id }}"
                                    {{ old('anggota_id', $pinjaman->anggota_id) == $item->id ? 'selected' : '' }}>
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
                            @foreach (['Pinjaman Modal Usaha', 'Pinjaman Pembiayaan Multi Guna', 'Pinjaman Pembiayaan Umroh'] as $jenis)
                                <option value="{{ $jenis }}"
                                    {{ old('jenis_pinjaman', $pinjaman->jenis_pinjaman) == $jenis ? 'selected' : '' }}>
                                    {{ $jenis }}</option>
                            @endforeach
                        </select>
                        @error('jenis_pinjaman')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tanggal_pinjaman" class="form-label">Tanggal Pinjaman</label>
                        <input type="date" name="tanggal_pinjaman" class="form-control" id="tanggal_pinjaman"
                            value="{{ old('tanggal_pinjaman', $pinjaman->tanggal_pinjaman) }}" required>
                        @error('tanggal_pinjaman')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="jangka_waktu" class="form-label">Jangka Waktu</label>
                        <select name="jangka_waktu" id="jangka_waktu" class="form-select" required>
                            <option value="">-- Pilih Jangka Waktu --</option>
                            @foreach ([12, 18, 24, 30, 36] as $bulan)
                                <option value="{{ $bulan }}"
                                    {{ old('jangka_waktu', $pinjaman->jangka_waktu) == $bulan ? 'selected' : '' }}>
                                    {{ $bulan }} Bulan
                                </option>
                            @endforeach
                        </select>
                        @error('jangka_waktu')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="jumlah_pinjaman" class="form-label">Jumlah Pinjaman</label>
                        <input type="number" name="jumlah_pinjaman" class="form-control" id="jumlah_pinjaman"
                            value="{{ old('jumlah_pinjaman', $pinjaman->jumlah_pinjaman) }}" required>
                        @error('jumlah_pinjaman')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="bunga" class="form-label">Bunga (%)</label>
                        <div class="input-group">
                            <input type="number" name="bunga" class="form-control" id="bunga"
                                value="{{ old('bunga', $pinjaman->bunga ?? 1.5) }}" step="0.01" required>
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
                                value="{{ old('nisbah', $pinjaman->nisbah) }}" readonly>
                            <div class="input-group-text">/ bulan</div>
                        </div>
                        @error('nisbah')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="angsuran_pokok" class="form-label">Angsuran Pokok</label>
                        <input type="number" name="angsuran_pokok" class="form-control" id="angsuran_pokok"
                            value="{{ old('angsuran_pokok', $pinjaman->angsuran_pokok) }}" required>
                        @error('angsuran_pokok')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="total_pinjaman" class="form-label">Total Pinjaman</label>
                        <input type="number" name="total_pinjaman" class="form-control" id="total_pinjaman"
                            value="{{ old('total_pinjaman', $pinjaman->total_pinjaman) }}" readonly>
                        @error('total_pinjaman')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tanggal_jatuh_tempo" class="form-label">Tanggal Jatuh Tempo</label>
                        <input type="date" name="tanggal_jatuh_tempo" class="form-control" id="tanggal_jatuh_tempo"
                            value="{{ old('tanggal_jatuh_tempo', $pinjaman->tanggal_jatuh_tempo) }}" readonly required>
                        @error('tanggal_jatuh_tempo')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="sisa_pinjaman" class="form-label">Sisa Pinjaman</label>
                        <input type="number" name="sisa_pinjaman" class="form-control" id="sisa_pinjaman"
                            value="{{ old('sisa_pinjaman', $pinjaman->sisa_pinjaman) }}" required>
                        @error('sisa_pinjaman')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="belum lunas"
                                {{ old('status', $pinjaman->status) == 'belum lunas' ? 'selected' : '' }}>Belum Lunas
                            </option>
                            <option value="lunas" {{ old('status', $pinjaman->status) == 'lunas' ? 'selected' : '' }}>
                                Lunas</option>
                        </select>
                        @error('status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="text-start">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('pinjaman.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('pinjaman._form_script') {{-- Buatkan file JS terpisah jika perlu --}}
@endsection
