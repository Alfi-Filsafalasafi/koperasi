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
        $pinjaman = Pinjaman::findOrFail($id);
        return response()->json([
            'cicilan_ke' => $cicilanTerakhir + 1,
            'angsuran_pokok' => $pinjaman->angsuran_pokok,
            'sisa_pinjaman' => $pinjaman->sisa_pinjaman,
            'nisbah' => $pinjaman->nisbah,
        ]);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'pinjaman_id' => 'required|exists:pinjamans,id',
            'tanggal_bayar' => 'required|date',
            'pembayaran_pokok' => 'required|numeric|min:0',
            'pembayaran_nisbah' => 'required|numeric|min:0',
            'pembayaran_denda' => 'required|numeric|min:0',
            'cicilan_ke' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        // DB::transaction(function () use ($request) {
        //     $transaksi = TransaksiPinjaman::create($request->all());

        //     $pinjaman = $transaksi->pinjaman;
        //     $pinjaman->sisa_pinjaman -= $transaksi->pembayaran_pokok + $transaksi->pembayaran_nisbah;;
        //     if ($pinjaman->sisa_pinjaman <= 0) {
        //         $pinjaman->sisa_pinjaman = 0;
        //         $pinjaman->status = 'lunas';
        //     }
        //     $pinjaman->save();
        // });
        $validatedData['user_id'] = auth()->id(); // Set user_id dari auth


        DB::transaction(function () use ($validatedData, $request) {
            $transaksi = TransaksiPinjaman::create($validatedData);
            $transaksi->id_transaksi_pinjaman = 'TRPI-' . str_pad($transaksi->id, 7, '0', STR_PAD_LEFT);
            $transaksi->save();

            $pinjaman = $transaksi->pinjaman;
            $pinjaman->sisa_pinjaman -= $transaksi->pembayaran_pokok + $transaksi->pembayaran_nisbah;
            if ($pinjaman->sisa_pinjaman <= 0) {
                $pinjaman->sisa_pinjaman = 0;
                $pinjaman->status = 'lunas';
            }
            $pinjaman->save();

            if($pinjaman->jenis_pinjaman == 'Pinjaman Modal Usaha'){
                $akun_kredit = '301.01 - Piutang Modal Usaha';
            }
            else if($pinjaman->jenis_pinjaman == 'Pinjaman Pembiayaan Multi Guna'){
                $akun_kredit = '301.02 - Piutang Pembiayaan Multi Guna';
            } else if($pinjaman->jenis_pinjaman == 'Pinjaman Pembiayaan Umroh'){
                $akun_kredit = '301.03 - Piutang Pembiayaan Umroh';
            } else {
                $akun_kredit = '301.00 - Piutang Anggota';
            }



            // Catat ke Jurnal Kas Masuk
            JurnalKasMasuk::create([
                'anggota_id' => $pinjaman->anggota_id,
                'tanggal' => $request->tanggal_bayar,
                'no_bukti' => $transaksi->id_transaksi_pinjaman,
                'uraian' => 'Pembayaran pinjaman ke-' . $request->cicilan_ke ,
                'akun_debit' => '101-Kas',
                'akun_kredit' => $akun_kredit,
                'nominal_debit' => $request->pembayaran_pokok + $request->pembayaran_nisbah + $request->pembayaran_denda,
                'nominal_kredit' => $request->pembayaran_pokok + $request->pembayaran_nisbah + $request->pembayaran_denda,
                'pembayaran_pokok' => $request->pembayaran_pokok,
                'pembayaran_bunga' => $request->pembayaran_nisbah,
                'pembayaran_denda' => $request->pembayaran_denda,
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
            'pembayaran_nisbah' => 'required|numeric|min:0',
            'pembayaran_denda' => 'required|numeric|min:0',
            'cicilan_ke' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $transaksi) {
            $pinjaman = $transaksi->pinjaman;

            // rollback pokok lama
            $pinjaman->sisa_pinjaman += $transaksi->pembayaran_pokok + $transaksi->pembayaran_nisbah;

            // update transaksi
            $transaksi->update($request->all());

            // update sisa pinjaman
            $pinjaman->sisa_pinjaman -= $transaksi->pembayaran_pokok + $transaksi->pembayaran_nisbah;
            $pinjaman->status = $pinjaman->sisa_pinjaman <= 0 ? 'lunas' : 'belum lunas';
            $pinjaman->save();

            if($pinjaman->jenis_pinjaman == 'Pinjaman Modal Usaha'){
                $akun_kredit = '301.01 - Piutang Modal Usaha';
            }
            else if($pinjaman->jenis_pinjaman == 'Pinjaman Pembiayaan Multi Guna'){
                $akun_kredit = '301.02 - Piutang Pembiayaan Multi Guna';
            } else if($pinjaman->jenis_pinjaman == 'Pinjaman Pembiayaan Umroh'){
                $akun_kredit = '301.03 - Piutang Pembiayaan Umroh';
            } else {
                $akun_kredit = '301.00 - Piutang Anggota';
            }

            // hapus jurnal lama
            JurnalKasMasuk::where('no_bukti', $transaksi->id_transaksi_pinjaman)->delete();

            // catat ulang jurnal baru
            JurnalKasMasuk::create([
                'anggota_id' => $transaksi->pinjaman->anggota_id,
                'tanggal' => $request->tanggal_bayar,
                'no_bukti' => $transaksi->id_transaksi_pinjaman,
                'uraian' => 'Update pembayaran pinjaman ke-' . $request->cicilan_ke ,
                'akun_debit' => '101-Kas',
                'akun_kredit' => $akun_kredit,
                'nominal_debit' => $request->pembayaran_pokok + $request->pembayaran_nisbah + $request->pembayaran_denda,
                'nominal_kredit' => $request->pembayaran_pokok + $request->pembayaran_nisbah + $request->pembayaran_denda,
                'pembayaran_pokok' => $request->pembayaran_pokok,
                'pembayaran_bunga' => $request->pembayaran_nisbah,
                'pembayaran_denda' => $request->pembayaran_denda,
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
            $pinjaman->sisa_pinjaman += $transaksi->pembayaran_pokok + $transaksi->pembayaran_nisbah;
            $pinjaman->status = 'belum lunas';
            $pinjaman->save();

            // hapus jurnal kas masuk
            JurnalKasMasuk::where('no_bukti', $transaksi->id_transaksi_pinjaman)->delete();

            $transaksi->delete();
        });


        return redirect()->route('transaksi-pinjaman.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
