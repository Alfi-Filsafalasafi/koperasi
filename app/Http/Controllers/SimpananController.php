<?php

namespace App\Http\Controllers;

use App\Models\Simpanan;
use App\Models\User;
use App\Models\JurnalKasMasuk;
use App\Models\JurnalKasKeluar;
use Illuminate\Http\Request;

class SimpananController extends Controller
{
    public function index()
    {
        $datas = Simpanan::with('anggota')->latest()->get();
        return view('simpanan.index', compact('datas'));
    }

    public function create()
    {
        $anggotaList = User::where('role', 'anggota')->get();
        return view('simpanan.create', compact('anggotaList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'anggota_id' => 'required|exists:users,id',
            'jenis_simpanan' => 'required',
            'saldo_awal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $validated['total_saldo'] = $request->saldo_awal;

        $simpanan = Simpanan::create($validated);

        $simpanan->id_simpanan = $request->id_simpanan .'-' . str_pad($simpanan->id, 7, '0', STR_PAD_LEFT);
        $simpanan->save();
        $id_simpanan = $request->id_simpanan;

        $id_simpanan = $request->id_simpanan;
        $jenis_simpanan = $request->jenis_simpanan;
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

        // Catat ke jurnal kas masuk
        JurnalKasMasuk::create([
            'anggota_id' => $request->anggota_id,
            'tanggal' => now()->toDateString(),
            'no_bukti' => $simpanan->id_simpanan,
            'uraian' => 'Setoran ' . ucfirst($request->jenis_simpanan),
            'akun_debit' => '101-Kas',
            'akun_kredit' => '' . $akun_debit_2 . ' ' . $akun_debit_1,
            'nominal_debit' => $request->saldo_awal,
            'nominal_kredit' => 0,
        ]);

        return redirect()->route('simpanan.index')->with('success', 'Data simpanan berhasil ditambahkan.');
    }


    public function edit($id)
    {
        $simpanan = Simpanan::findOrFail($id);
        $id_simpanan = $simpanan->id_simpanan;
        $prefix = explode('-', $id_simpanan)[0];
        $id_simpanan = $prefix;
        $anggotaList = User::where('role', 'anggota')->get();
        return view('simpanan.edit', compact('simpanan', 'anggotaList', 'id_simpanan'));
    }

    public function update(Request $request, $id)
    {
        $simpanan = Simpanan::findOrFail($id);

        $validated = $request->validate([
            'anggota_id' => 'required|exists:users,id',
            'jenis_simpanan' => 'required',
            'saldo_awal' => 'required|numeric|min:0',
            'total_saldo' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        JurnalKasMasuk::where('no_bukti', $simpanan->id_simpanan)->delete();

        $simpanan->id_simpanan = $request->id_simpanan .'-' . str_pad($simpanan->id, 7, '0', STR_PAD_LEFT);
        $id_simpanan = $request->id_simpanan;
        $jenis_simpanan = $request->jenis_simpanan;
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
        // Update simpanan
        $simpanan->update($validated);
                // Tambahan dana, catat ke kas masuk
        JurnalKasMasuk::create([
                    'anggota_id' => $simpanan->anggota_id,
                    'tanggal' => now()->toDateString(),
                    'no_bukti' => $simpanan->id_simpanan,
                    'uraian' => 'Penambahan simpanan ',
                    'akun_debit' => '101-Kas',
                    'akun_kredit' => '' . $akun_debit_2 . ' ' . $akun_debit_1,
                    'nominal_debit' => $request->saldo_awal,
                    'nominal_kredit' => $request->saldo_awal,
                ]);

        return redirect()->route('simpanan.index')->with('success', 'Data simpanan berhasil diupdate.');
    }


    public function destroy($id)
    {
        $simpanan = Simpanan::findOrFail($id);

        JurnalKasKeluar::where('no_bukti', $simpanan->id_simpanan)->delete();
        JurnalKasMasuk::where('no_bukti', $simpanan->id_simpanan)->delete();

        $simpanan->delete();

        return redirect()->route('simpanan.index')->with('success', 'Data simpanan berhasil dihapus.');
    }

}
