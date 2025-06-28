@extends('layouts.master')
@section('title', 'Transaksi Pinjaman')
@section('subtitle', 'Tambah')

@section('transaksi_pinjaman', '')
@section('content')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Tambah Transaksi Pinjaman</h5>

                {{-- Alert Error Validation --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>Periksa kembali!</strong> Terdapat kesalahan pada form:
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('transaksi-pinjaman.store') }}" method="POST" class="row g-3 needs-validation"
                    novalidate>
                    @csrf

                    <div class="col-md-6">
                        <label for="pinjaman_id" class="form-label">Anggota / Pinjaman</label>
                        <select name="pinjaman_id" id="pinjaman_id" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            @foreach ($pinjamans as $pinjaman)
                                <option value="{{ $pinjaman->id }}"
                                    {{ old('pinjaman_id') == $pinjaman->id ? 'selected' : '' }}>
                                    {{ $pinjaman->anggota->nama_lengkap ?? '-' }} - {{ $pinjaman->jenis_pinjaman }} (Rp
                                    {{ number_format($pinjaman->sisa_pinjaman, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        @error('pinjaman_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tanggal_bayar" class="form-label">Tanggal Bayar</label>
                        <input type="date" name="tanggal_bayar" class="form-control" id="tanggal_bayar"
                            value="{{ old('tanggal_bayar', date('Y-m-d')) }}" required>

                        @error('tanggal_bayar')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="pembayaran_pokok" class="form-label">Pembayaran Pokok</label>
                        <input type="number" name="pembayaran_pokok" class="form-control" id="pembayaran_pokok"
                            value="{{ old('pembayaran_pokok') }}" step="0.01" required>
                        @error('pembayaran_pokok')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="pembayaran_bunga" class="form-label">Pembayaran Bunga</label>
                        <input type="number" name="pembayaran_bunga" class="form-control" id="pembayaran_bunga"
                            value="{{ old('pembayaran_bunga') }}" step="0.01" required>
                        @error('pembayaran_bunga')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="pembayaran_denda" class="form-label">Pembayaran Denda</label>
                        <input type="number" name="pembayaran_denda" class="form-control" id="pembayaran_denda"
                            value="{{ old('pembayaran_denda') }}" step="0.01" required>
                        @error('pembayaran_denda')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="cicilan_ke" class="form-label">Cicilan Ke</label>
                        <input type="number" name="cicilan_ke" class="form-control" id="cicilan_ke"
                            value="{{ old('cicilan_ke') }}" readonly>
                        @error('cicilan_ke')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-9">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="user_id" class="form-label">Petugas</label>
                        <select name="user_id" id="user_id" class="form-select" required>
                            <option value="">-- Pilih --</option>
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
                        <a href="{{ route('transaksi-pinjaman.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectPinjaman = document.getElementById('pinjaman_id');
            const inputCicilan = document.getElementById('cicilan_ke');

            selectPinjaman.addEventListener('change', function() {
                const pinjamanId = this.value;
                if (!pinjamanId) return;

                fetch(`/pinjaman/${pinjamanId}/cicilan-terakhir`)
                    .then(response => response.json())
                    .then(data => {
                        inputCicilan.value = data.cicilan_ke;
                    })
                    .catch(error => {
                        console.error('Gagal ambil cicilan:', error);
                        inputCicilan.value = '';
                    });
            });
        });
    </script>
@endsection
