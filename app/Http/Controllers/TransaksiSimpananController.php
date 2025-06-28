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
        $validatedData = $request->validate([
            'simpanan_id' => 'required|exists:simpanans,id',
            'tanggal' => 'required|date',
            'jenis_transaksi' => 'required|in:setor,tarik',
            'jumlah' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $validatedData['user_id'] = auth()->id();
        $simpanan = Simpanan::findOrFail($request->simpanan_id);


        if ($request->jenis_transaksi === 'tarik' && $simpanan->total_saldo < $request->jumlah) {
            return redirect()->route('transaksi-simpanan.index')->with('error', 'Saldo Anda tidak cukup.');
        }

        $transaksi = TransaksiSimpanan::create($validatedData);
        $transaksi->id_transaksi_simpanan = 'TRSI-' . str_pad($transaksi->id, 7, '0', STR_PAD_LEFT);
        $transaksi->save();

        if ($request->jenis_transaksi === 'setor') {
            $simpanan->total_saldo += $request->jumlah;
        } else {
            $simpanan->total_saldo -= $request->jumlah;
        }
        $simpanan->save();

        $id_simpanan = $simpanan->id_simpanan;
        $id_simpanan = explode('-', $id_simpanan)[0];
        $jenis_simpanan = $simpanan->jenis_simpanan;

        if($id_simpanan == 'SIRAYA') {
            $akun_debit_1 = '210.01 - Simpanan Hari Raya';
        } else if($id_simpanan == 'SISUQUR') {
            $akun_debit_1 = '210.02 - Simpanan Qurban ';
        } else if($id_simpanan == 'SIRELA') {
            $akun_debit_1 = '210.03 - Simpanan Sukarela';
        } else if($id_simpanan == 'SIMASJID') {
            $akun_debit_1 = '210.04 - Simpanan Masjid';
        } else if($id_simpanan == 'SIUMMA') {
            $akun_debit_1 = '210.05 - Simpanan Lainnya';
        } else if($id_simpanan == 'SISUKA') {
            $akun_debit_1 = '210.06 - Simpanan Berjangka';
        } else {
            $akun_debit_1 = '210.00 - Simpanan Umum';
        }

        if($jenis_simpanan == 'Simpanan Wajib') {
            $akun_debit_2 = '201.01 ';
        } else if($jenis_simpanan == 'Simpanan Wajib') {
            $akun_debit_2 = '201.02 ';
        } else if($jenis_simpanan == 'Simpanan Sukarela') {
            $akun_debit_2 = '201.03 ';
        } else {
            $akun_debit_2 = '201.00 ';
        }

        if ($request->jenis_transaksi === 'setor') {
            JurnalKasMasuk::create([
                'anggota_id' => $simpanan->anggota_id,
                'tanggal' => $request->tanggal,
                'no_bukti' => $transaksi->id_transaksi_simpanan,
                'uraian' => 'Setor simpanan ',
                'akun_debit' => '101-Kas',
                'akun_kredit' => '' . $akun_debit_2 . ' ' . $akun_debit_1,
                'nominal_debit' => $request->jumlah,
                'nominal_kredit' => 0,
            ]);
        } else {
            JurnalKasKeluar::create([
                'anggota_id' => $simpanan->anggota_id,
                'tanggal' => $request->tanggal,
                'no_bukti' => $transaksi->id_transaksi_simpanan,
                'uraian' => 'Penarikan simpanan ',
                'akun_debit' => '' . $akun_debit_2 . ' ' . $akun_debit_1,
                'akun_kredit' => '101-Kas',
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

            $id_simpanan = $simpananBaru->id_simpanan;
            $id_simpanan = explode('-', $id_simpanan)[0];
            $jenis_simpanan = $simpananBaru->jenis_simpanan;

            if($id_simpanan == 'SIRAYA') {
                $akun_debit_1 = '210.01 - Simpanan Hari Raya';
            } else if($id_simpanan == 'SISUQUR') {
                $akun_debit_1 = '210.02 - Simpanan Qurban ';
            } else if($id_simpanan == 'SIRELA') {
                $akun_debit_1 = '210.03 - Simpanan Sukarela';
            } else if($id_simpanan == 'SIMASJID') {
                $akun_debit_1 = '210.04 - Simpanan Masjid';
            } else if($id_simpanan == 'SIUMMA') {
                $akun_debit_1 = '210.05 - Simpanan Lainnya';
            } else if($id_simpanan == 'SISUKA') {
                $akun_debit_1 = '210.06 - Simpanan Berjangka';
            } else {
                $akun_debit_1 = '210.00 - Simpanan Umum';
            }

            if($jenis_simpanan == 'Simpanan Wajib') {
                $akun_debit_2 = '201.01 ';
            } else if($jenis_simpanan == 'Simpanan Wajib') {
                $akun_debit_2 = '201.02 ';
            } else if($jenis_simpanan == 'Simpanan Sukarela') {
                $akun_debit_2 = '201.03 ';
            } else {
                $akun_debit_2 = '201.00 ';
            }

            // Hapus jurnal lama dulu
            JurnalKasMasuk::where('no_bukti', $transaksi->id_transaksi_simpanan)->delete();
            JurnalKasKeluar::where('no_bukti', $transaksi->id_transaksi_simpanan)->delete();

            // Tambahkan jurnal baru
            if ($request->jenis_transaksi === 'setor') {
                JurnalKasMasuk::create([
                    'anggota_id' => $simpananBaru->anggota_id,
                    'tanggal' => $request->tanggal,
                    'no_bukti' => $transaksi->id_transaksi_simpanan,
                    'uraian' => 'Perubahan setor simpanan ',
                    'akun_debit' => '101-Kas',
                    'akun_kredit' => '' . $akun_debit_2 . ' ' . $akun_debit_1,
                    'nominal_debit' => $request->jumlah,
                    'nominal_kredit' => 0,
                ]);
            } else {
                JurnalKasKeluar::create([
                    'anggota_id' => $simpananBaru->anggota_id,
                    'tanggal' => $request->tanggal,
                    'no_bukti' => $transaksi->id_transaksi_simpanan,
                    'uraian' => 'Perubahan tarik simpanan ',
                    'akun_debit' => '' . $akun_debit_2 . ' ' . $akun_debit_1,
                    'akun_kredit' => '101-Kas',
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