<?php

namespace App\Http\Controllers;

use App\Models\Simpanan;
use App\Models\TransaksiSimpanan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\JurnalKasMasuk;
use App\Models\JurnalKasKeluar;
class TransaksiSimpananController extends Controller
{
    public function index()
    {
        $datas = TransaksiSimpanan::with('simpanan.anggota', 'petugas')->latest()->get();
        return view('transaksi_simpanan.index', compact('datas'));
    }

    public function create()
    {
        $simpanans = Simpanan::with('anggota')->get();
        $users = User::where('is_anggota', 0)->get(); // opsional: hanya untuk audit/admin input
        return view('transaksi_simpanan.create', compact('simpanans', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'simpanan_id' => 'required|exists:simpanans,id',
            'tanggal' => 'required|date',
            'jenis_transaksi' => 'required|in:setor,tarik',
            'jumlah' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);
            $simpanan = Simpanan::findOrFail($request->simpanan_id);


        if ($request->jenis_transaksi === 'tarik' && $simpanan->total_saldo < $request->jumlah) {
            return redirect()->route('transaksi-simpanan.index')->with('error', 'Saldo Anda tidak cukup.');
        }

        $transaksi = TransaksiSimpanan::create($request->all());
        $transaksi->id_transaksi_simpanan = 'TRSI-' . str_pad($transaksi->id, 7, '0', STR_PAD_LEFT);
        $transaksi->save();

        if ($request->jenis_transaksi === 'setor') {
            $simpanan->total_saldo += $request->jumlah;
        } else {
            $simpanan->total_saldo -= $request->jumlah;
        }
        $simpanan->save();

        if ($request->jenis_transaksi === 'setor') {
            JurnalKasMasuk::create([
                'anggota_id' => $simpanan->anggota_id,
                'tanggal' => $request->tanggal,
                'no_bukti' => $transaksi->id_transaksi_simpanan,
                'uraian' => 'Setor simpanan ' . ucfirst($simpanan->jenis_simpanan),
                'akun_debit' => 'Kas',
                'akun_kredit' => 'Simpanan ' . ucfirst($simpanan->jenis_simpanan),
                'nominal_debit' => $request->jumlah,
                'nominal_kredit' => 0,
            ]);
        } else {
            JurnalKasKeluar::create([
                'anggota_id' => $simpanan->anggota_id,
                'tanggal' => $request->tanggal,
                'no_bukti' => $transaksi->id_transaksi_simpanan,
                'uraian' => 'Penarikan simpanan ' . ucfirst($simpanan->jenis_simpanan),
                'akun_debit' => 'Simpanan ' . ucfirst($simpanan->jenis_simpanan),
                'akun_kredit' => 'Kas',
                'nominal_debit' => 0,
                'nominal_kredit' => $request->jumlah,
            ]);
        }


        return redirect()->route('transaksi-simpanan.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }


    public function edit($id)
    {
        $transaksi = TransaksiSimpanan::findOrFail($id);
        $simpanans = Simpanan::with('anggota')->get();
        $users = User::all();
        return view('transaksi_simpanan.edit', compact('transaksi', 'simpanans', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'simpanan_id' => 'required|exists:simpanans,id',
            'tanggal' => 'required|date',
            'jenis_transaksi' => 'required|in:setor,tarik',
            'jumlah' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);

            $transaksi = TransaksiSimpanan::findOrFail($id);

            // Kembalikan saldo dari transaksi lama
            $simpananLama = Simpanan::findOrFail($transaksi->simpanan_id);
            if ($transaksi->jenis_transaksi === 'setor') {
                $simpananLama->total_saldo -= $transaksi->jumlah;
            } else {
                $simpananLama->total_saldo += $transaksi->jumlah;
            }
            $simpananLama->save();

            // Ambil simpanan baru
            $simpananBaru = Simpanan::findOrFail($request->simpanan_id);

            // Cek saldo cukup (harus dilakukan setelah rollback saldo lama)
            if ($request->jenis_transaksi === 'tarik') {
                if ($simpananBaru->id == $simpananLama->id) {
                    // Jika transaksi tetap di simpanan yang sama
                    if ($simpananBaru->total_saldo < $request->jumlah) {
                        return redirect()->route('transaksi-simpanan.index')->with('error', 'Saldo Anda tidak cukup.');
                    }
                } else {
                    // Jika pindah ke simpanan lain, maka cek saldo simpananBaru secara terpisah
                    if ($simpananBaru->total_saldo < $request->jumlah) {
                        return redirect()->route('transaksi-simpanan.index')->with('error', 'Saldo pada simpanan baru tidak cukup.');
                    }
                }
            }


            $simpananLama->save();

            // Update transaksi
            $transaksi->update($request->all());

            // Tambahkan total_saldo baru
            if ($request->jenis_transaksi === 'setor') {
                $simpananBaru->total_saldo += $request->jumlah;
            } else {
                $simpananBaru->total_saldo -= $request->jumlah;
            }
            $simpananBaru->save();

            // Hapus jurnal lama dulu
            JurnalKasMasuk::where('no_bukti', $transaksi->id_transaksi_simpanan)->delete();
            JurnalKasKeluar::where('no_bukti', $transaksi->id_transaksi_simpanan)->delete();

            // Tambahkan jurnal baru
            if ($request->jenis_transaksi === 'setor') {
                JurnalKasMasuk::create([
                    'anggota_id' => $simpananBaru->anggota_id,
                    'tanggal' => $request->tanggal,
                    'no_bukti' => $transaksi->id_transaksi_simpanan,
                    'uraian' => 'Perubahan setor simpanan ' . ucfirst($simpananBaru->jenis_simpanan),
                    'akun_debit' => 'Kas',
                    'akun_kredit' => 'Simpanan ' . ucfirst($simpananBaru->jenis_simpanan),
                    'nominal_debit' => $request->jumlah,
                    'nominal_kredit' => 0,
                ]);
            } else {
                JurnalKasKeluar::create([
                    'anggota_id' => $simpananBaru->anggota_id,
                    'tanggal' => $request->tanggal,
                    'no_bukti' => $transaksi->id_transaksi_simpanan,
                    'uraian' => 'Perubahan tarik simpanan ' . ucfirst($simpananBaru->jenis_simpanan),
                    'akun_debit' => 'Simpanan ' . ucfirst($simpananBaru->jenis_simpanan),
                    'akun_kredit' => 'Kas',
                    'nominal_debit' => 0,
                    'nominal_kredit' => $request->jumlah,
                ]);
            }


        return redirect()->route('transaksi-simpanan.index')->with('success', 'Transaksi berhasil diupdate.');
    }


    public function destroy($id)
    {
        $transaksi = TransaksiSimpanan::findOrFail($id);

        $simpanan = Simpanan::findOrFail($transaksi->simpanan_id);
            if ($transaksi->jenis_transaksi === 'setor') {
                $simpanan->total_saldo -= $transaksi->jumlah;
            } else {
                $simpanan->total_saldo += $transaksi->jumlah;
            }
        $simpanan->save();

        $transaksi->delete();
        JurnalKasMasuk::where('no_bukti',  $transaksi->id_transaksi_simpanan)->delete();
        JurnalKasKeluar::where('no_bukti',  $transaksi->id_transaksi_simpanan)->delete();


        return redirect()->route('transaksi-simpanan.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
