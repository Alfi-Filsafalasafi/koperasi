<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use App\Models\TransaksiPinjaman;
use App\Models\User;
use App\Models\JurnalKasMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiPinjamanController extends Controller
{
    public function index()
    {
        $datas = TransaksiPinjaman::with('pinjaman.anggota', 'petugas')->latest()->get();
        return view('transaksi_pinjaman.index', compact('datas'));
    }

    public function create()
    {
        $pinjamans = Pinjaman::with('anggota')->get();
        $users = User::where('role', '!=', 'anggota')->get(); // Jika ingin simpan info petugas input
        return view('transaksi_pinjaman.create', compact('pinjamans', 'users'));
    }
    public function getCicilanTerakhir($id)
    {
        $cicilanTerakhir = TransaksiPinjaman::where('pinjaman_id', $id)->max('cicilan_ke') ?? 0;
        return response()->json([
            'cicilan_ke' => $cicilanTerakhir + 1
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'pinjaman_id' => 'required|exists:pinjamans,id',
            'tanggal_bayar' => 'required|date',
            'pembayaran_pokok' => 'required|numeric|min:0',
            'pembayaran_bunga' => 'required|numeric|min:0',
            'pembayaran_denda' => 'required|numeric|min:0',
            'cicilan_ke' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);

        // DB::transaction(function () use ($request) {
        //     $transaksi = TransaksiPinjaman::create($request->all());

        //     $pinjaman = $transaksi->pinjaman;
        //     $pinjaman->sisa_pinjaman -= $transaksi->pembayaran_pokok;
        //     if ($pinjaman->sisa_pinjaman <= 0) {
        //         $pinjaman->sisa_pinjaman = 0;
        //         $pinjaman->status = 'lunas';
        //     }
        //     $pinjaman->save();
        // });


        DB::transaction(function () use ($request) {
            $transaksi = TransaksiPinjaman::create($request->all());
            $transaksi->id_transaksi_pinjaman = 'TRPI-' . str_pad($transaksi->id, 7, '0', STR_PAD_LEFT);
            $transaksi->save();

            $pinjaman = $transaksi->pinjaman;
            $pinjaman->sisa_pinjaman -= $transaksi->pembayaran_pokok;
            if ($pinjaman->sisa_pinjaman <= 0) {
                $pinjaman->sisa_pinjaman = 0;
                $pinjaman->status = 'lunas';
            }
            $pinjaman->save();



            // Catat ke Jurnal Kas Masuk
            JurnalKasMasuk::create([
                'anggota_id' => $pinjaman->anggota_id,
                'tanggal' => $request->tanggal_bayar,
                'no_bukti' => $transaksi->id_transaksi_pinjaman,
                'uraian' => 'Pembayaran pinjaman ke-' . $request->cicilan_ke . ' oleh anggota ID: ' . $pinjaman->anggota_id,
                'akun_debit' => 'Kas',
                'akun_kredit' => 'Piutang Anggota',
                'nominal_debit' => $request->pembayaran_pokok + $request->pembayaran_bunga + $request->pembayaran_denda,
                'nominal_kredit' => 0,
            ]);
        });


        return redirect()->route('transaksi-pinjaman.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $transaksi = TransaksiPinjaman::findOrFail($id);
        $pinjamans = Pinjaman::with('anggota')->get();
        $users = User::where('role', '!=', 'anggota')->get();
        return view('transaksi_pinjaman.edit', compact('transaksi', 'pinjamans', 'users'));
    }

    public function update(Request $request, $id)
    {
        $transaksi = TransaksiPinjaman::findOrFail($id);

        $request->validate([
            'pinjaman_id' => 'required|exists:pinjamans,id',
            'tanggal_bayar' => 'required|date',
            'pembayaran_pokok' => 'required|numeric|min:0',
            'pembayaran_bunga' => 'required|numeric|min:0',
            'pembayaran_denda' => 'required|numeric|min:0',
            'cicilan_ke' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);

        DB::transaction(function () use ($request, $transaksi) {
            $pinjaman = $transaksi->pinjaman;

            // rollback pokok lama
            $pinjaman->sisa_pinjaman += $transaksi->pembayaran_pokok;

            // update transaksi
            $transaksi->update($request->all());

            // update sisa pinjaman
            $pinjaman->sisa_pinjaman -= $transaksi->pembayaran_pokok;
            $pinjaman->status = $pinjaman->sisa_pinjaman <= 0 ? 'lunas' : 'belum lunas';
            $pinjaman->save();

            // hapus jurnal lama
            JurnalKasMasuk::where('no_bukti', 'TPJ-' . str_pad($transaksi->id, 5, '0', STR_PAD_LEFT))->delete();

            // catat ulang jurnal baru
            JurnalKasMasuk::create([
                'anggota_id' => $transaksi->pinjaman->anggota_id,
                'tanggal' => $request->tanggal_bayar,
                'no_bukti' => $transaksi->id_transaksi_pinjaman,
                'uraian' => 'Update pembayaran pinjaman ke-' . $request->cicilan_ke ,
                'akun_debit' => 'Kas',
                'akun_kredit' => 'Piutang Anggota',
                'nominal_debit' => $request->pembayaran_pokok + $request->pembayaran_bunga + $request->pembayaran_denda,
                'nominal_kredit' => 0,
            ]);
        });


        return redirect()->route('transaksi-pinjaman.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $transaksi = TransaksiPinjaman::findOrFail($id);

        DB::transaction(function () use ($transaksi) {
            $pinjaman = $transaksi->pinjaman;

            // rollback pokok yang sebelumnya dibayar
            $pinjaman->sisa_pinjaman += $transaksi->pembayaran_pokok;
            $pinjaman->status = 'belum lunas';
            $pinjaman->save();

            // hapus jurnal kas masuk
            JurnalKasMasuk::where('no_bukti', 'TPJ-' . str_pad($transaksi->id, 5, '0', STR_PAD_LEFT))->delete();

            $transaksi->delete();
        });


        return redirect()->route('transaksi-pinjaman.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}