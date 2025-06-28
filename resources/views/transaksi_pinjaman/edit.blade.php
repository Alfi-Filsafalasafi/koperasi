@extends('layouts.master')
@section('title', 'Transaksi Pinjaman')
@section('subtitle', 'Edit')

@section('transaksi_pinjaman', '')
@section('content')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Edit Transaksi Pinjaman</h5>

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

                <form action="{{ route('transaksi-pinjaman.update', $transaksi->id) }}" method="POST"
                    class="row g-3 needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="col-md-6">
                        <label for="pinjaman_id" class="form-label">Anggota / Pinjaman</label>
                        <select name="pinjaman_id" id="pinjaman_id" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            @foreach ($pinjamans as $pinjaman)
                                <option value="{{ $pinjaman->id }}"
                                    {{ old('pinjaman_id', $transaksi->pinjaman_id) == $pinjaman->id ? 'selected' : '' }}>
                                    {{ $pinjaman->anggota->nama_lengkap ?? '-' }} - {{ $pinjaman->jenis_pinjaman }} (Rp
                                    {{ number_format($pinjaman->sisa_pinjaman, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="pinjaman_id" value="{{ $transaksi->pinjaman_id }}">
                        {{-- tetap kirim nilainya --}}
                        <span id="sisa_pinjaman">Sisa Pinjaman = {{ $transaksi->pinjaman->sisa_pinjaman }}</span> <br>
                        <span id="angsuran_pokok">Angsuran Pokok = {{ $transaksi->pinjaman->angsuran_pokok }}</span> <br>
                        <span id="nisbah">Nisbah = {{ $transaksi->pinjaman->nisbah }}</span>
                        @error('pinjaman_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="tanggal_bayar" class="form-label">Tanggal Bayar</label>
                        <input type="date" name="tanggal_bayar" class="form-control" id="tanggal_bayar"
                            value="{{ old('tanggal_bayar', $transaksi->tanggal_bayar) }}" required>
                        @error('tanggal_bayar')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="pembayaran_pokok" class="form-label">Pembayaran Pokok</label>
                        <input type="number" name="pembayaran_pokok" class="form-control" id="pembayaran_pokok"
                            value="{{ old('pembayaran_pokok', $transaksi->pembayaran_pokok) }}" step="0.01" required>
                        @error('pembayaran_pokok')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="pembayaran_nisbah" class="form-label">Pembayaran Nisbah</label>
                        <input type="number" name="pembayaran_nisbah" class="form-control" id="pembayaran_nisbah"
                            value="{{ old('pembayaran_nisbah', $transaksi->pembayaran_nisbah) }}" step="0.01" required>
                        @error('pembayaran_nisbah')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="pembayaran_denda" class="form-label">Pembayaran Denda</label>
                        <input type="number" name="pembayaran_denda" class="form-control" id="pembayaran_denda"
                            value="{{ old('pembayaran_denda', $transaksi->pembayaran_denda) }}" step="0.01" required>
                        @error('pembayaran_denda')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="total_pembayaran" class="form-label">Total Pembayaran</label>
                        <input type="number" name="total_pembayaran" class="form-control" id="total_pembayaran"
                            value="{{ old('total_pembayaran', $transaksi->pembayaran_pokok + $transaksi->pembayaran_denda + $transaksi->pembayaran_nisbah) }}"
                            step="0.01" readonly>
                        @error('total_pembayaran')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="cicilan_ke" class="form-label">Cicilan Ke</label>
                        <input type="number" name="cicilan_ke" class="form-control" id="cicilan_ke"
                            value="{{ old('cicilan_ke', $transaksi->cicilan_ke) }}" readonly>
                        @error('cicilan_ke')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-9">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control">{{ old('keterangan', $transaksi->keterangan) }}</textarea>
                        @error('keterangan')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="text-start">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('transaksi-pinjaman.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('transaksi_pinjaman._form_script')
@endsection
