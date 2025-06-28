<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Simpanan;
use App\Models\TransaksiSimpanan;
use App\Models\Pinjaman;
use App\Models\TransaksiPinjaman;
use App\Models\JurnalKasMasuk;
use App\Models\JurnalKasKeluar;
use App\Models\LaporanBagiHasil;

class KoperasiSeeder extends Seeder
{
    public function run(): void
    {
        // 🔑 1. User
        $kasir = User::create([
            'nama_lengkap' => 'Kasir Utama',
            'id_anggota' => '0',
            'no_ktp' => '1234567890',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jember',
            'tanggal_lahir' => '1995-01-01',
            'alamat' => 'Jl. Koperasi No.1',
            'pekerjaan' => 'Kasir',
            'tanggal_masuk' => '2023-01-01',
            'no_telp' => '08123456789',
            'email' => 'kasir@example.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'status_aktif' => 'aktif',
            'is_anggota' => false,
        ]);

        $bendahara = User::create([
            'nama_lengkap' => 'Bendahara Umum',
            'id_anggota' => '00',
            'no_ktp' => '2345678901',
            'jenis_kelamin' => 'P',
            'tempat_lahir' => 'Banyuwangi',
            'tanggal_lahir' => '1990-05-05',
            'alamat' => 'Jl. Bendahara No.2',
            'pekerjaan' => 'Bendahara',
            'tanggal_masuk' => '2022-01-01',
            'no_telp' => '08129876543',
            'email' => 'bendahara@example.com',
            'password' => Hash::make('password'),
            'role' => 'bendahara',
            'status_aktif' => 'aktif',
            'is_anggota' => false,
        ]);

        $pimpinan = User::create([
            'nama_lengkap' => 'Pimpinan Koperasi',
            'id_anggota' => '000',
            'no_ktp' => '3456789012',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Lumajang',
            'tanggal_lahir' => '1985-03-03',
            'alamat' => 'Jl. Pimpinan No.3',
            'pekerjaan' => 'Pimpinan',
            'tanggal_masuk' => '2020-01-01',
            'no_telp' => '08121234567',
            'email' => 'pimpinan@example.com',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
            'status_aktif' => 'aktif',
            'is_anggota' => false,
        ]);

        $anggota = User::create([
            'nama_lengkap' => 'Alfi Anggota',
            'id_anggota' => 'ANG-0000001',
            'no_ktp' => '4567890123',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jember',
            'tanggal_lahir' => '2000-07-07',
            'alamat' => 'Jl. Anggota No.4',
            'pekerjaan' => 'Mahasiswa',
            'tanggal_masuk' => '2024-01-01',
            'no_telp' => '081322233344',
            'email' => 'anggota@example.com',
            'password' => Hash::make('password'),
            'role' => 'anggota',
            'status_aktif' => 'aktif',
            'is_anggota' => true,
        ]);

        // 💰 2. Simpanan
        $simpanan = Simpanan::create([
            'anggota_id' => $anggota->id,
            'id_simpanan' => 'SIRAYA-0000001',
            'jenis_simpanan' => 'Simpanan Wajib',
            'saldo_awal' => 500000,
            'total_saldo' => 500000,
            'keterangan' => 'ini keterangan',
        ]);

        TransaksiSimpanan::create([
            'id_transaksi_simpanan' => 'TRSI-0000001',
            'simpanan_id' => $simpanan->id,
            'tanggal' => now(),
            'jenis_transaksi' => 'setor',
            'jumlah' => 500000,
            'keterangan' => 'Setoran awal wajib',
            'user_id' => $kasir->id,
        ]);

        // 💳 3. Pinjaman
        $pinjaman = Pinjaman::create([
            'id_pinjaman' => 'PIMUSA-0000001',
            'anggota_id' => $anggota->id,
            'jumlah_pinjaman' => 3000000,
            'bunga' => 5,
            'jangka_waktu' => '6 bulan',
            'tanggal_pinjaman' => now()->subMonths(3),
            'tanggal_jatuh_tempo' => now()->subMonths(3),
            'angsuran_pokok' => 500000,
            'sisa_pinjaman' => 3000000,
            'jenis_pinjaman' => 'Pinjaman Modal Usaha',
            'status' => 'belum lunas',
        ]);

        TransaksiPinjaman::create([
            'pinjaman_id' => $pinjaman->id,
            'id_transaksi_pinjaman' => 'TRPI-0000001',
            'tanggal_bayar' => now()->subMonths(2),
            'pembayaran_pokok' => 600000,
            'pembayaran_bunga' => 600000,
            'pembayaran_denda' => 600000,
            'cicilan_ke' => 1,
            'keterangan' => 'Cicilan pertama',
            'user_id' => $kasir->id,
        ]);

        // 🧾 4. Jurnal Kas Masuk & Keluar
        JurnalKasMasuk::create([
            'anggota_id' => $anggota->id,
            'tanggal' => now(),
            'no_bukti' => 'KM001',
            'uraian' => 'Setoran simpanan wajib',
            'akun_debit' => 'Kas',
            'akun_kredit' => 'Simpanan Wajib',
            'nominal_debit' => 500000,
            'nominal_kredit' => 500000,
        ]);

        JurnalKasKeluar::create([
            'anggota_id' => $anggota->id,
            'tanggal' => now(),
            'no_bukti' => 'KK001',
            'uraian' => 'Pemberian pinjaman',
            'akun_debit' => 'Piutang Pinjaman',
            'akun_kredit' => 'Kas',
            'nominal_debit' => 3000000,
            'nominal_kredit' => 3000000,
        ]);

        // 📊 5. Laporan Bagi Hasil
        LaporanBagiHasil::create([
            'kode' => 'BGH-' . date('Ymd-His'),
            'nisbah' => 60.00,
            'tahun_awal' => 2024,
            'tahun_akhir' => 2024,
            'periode' => 'Januari - Maret 2024',
            'besaran_nisbah' => 12000000,
            'total_pendapatan' => 20000000,
            'pendapatan_dibagi' => 12000000,
            'pendapatan_ditahan' => 8000000,
            'jumlah_hari' => 90,
            'is_cetak' => false,
        ]);
    }
}
