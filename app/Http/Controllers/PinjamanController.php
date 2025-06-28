<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\JurnalKasKeluar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class PinjamanController extends Controller
{
    public function index()
    {
        $datas = Pinjaman::with('anggota')->latest()->get();
        return view('pinjaman.index', compact('datas'));
    }

    public function create()
    {
        $anggota = User::where('role', 'anggota')->get();
        return view('pinjaman.create', compact('anggota'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:users,id',
            'jenis_pinjaman' => 'required',
            'jumlah_pinjaman' => 'required|numeric|min:1',
            'bunga' => 'required|numeric|min:0',
            'jangka_waktu' => 'required|string',
            'tanggal_pinjaman' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_pinjaman',
            'angsuran_pokok' => 'required|numeric|min:0',
        ]);
        $pinjaman = Pinjaman::create([
            'anggota_id' => $request->anggota_id,
            'jenis_pinjaman' => $request->jenis_pinjaman,
            'jumlah_pinjaman' => $request->jumlah_pinjaman,
            'bunga' => $request->bunga,
            'jangka_waktu' => $request->jangka_waktu,
            'tanggal_pinjaman' => $request->tanggal_pinjaman,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            'angsuran_pokok' => $request->angsuran_pokok,
            'sisa_pinjaman' => $request->jumlah_pinjaman,
            'status' => 'belum lunas',
        ]);

        if($pinjaman->jenis_pinjaman == 'Pinjaman Modal Usaha'){
            $pinjaman->id_pinjaman = 'PIMUSA-' . str_pad($pinjaman->id, 7, '0', STR_PAD_LEFT);
        }
        else if($pinjaman->jenis_pinjaman == 'Pinjaman Pembiayaan Multi Guna'){
            $pinjaman->id_pinjaman = 'PEMUGA-' . str_pad($pinjaman->id, 7, '0', STR_PAD_LEFT);
        } else if($pinjaman->jenis_pinjaman == 'Pinjaman Pembiayaan Umroh'){
             $pinjaman->id_pinjaman = 'PEMURO-' . str_pad($pinjaman->id, 7, '0', STR_PAD_LEFT);
        }

        $pinjaman->save();

        JurnalKasKeluar::create([
            'anggota_id' => $request->anggota_id,
            'tanggal' => $request->tanggal_pinjaman,
            'no_bukti' => $pinjaman->id_pinjaman,
            'uraian' => 'Pencairan pinjaman ke anggota ID: ' . $request->anggota_id,
            'akun_debit' => 'Piutang Anggota',
            'akun_kredit' => 'Kas',
            'nominal_debit' => 0,
            'nominal_kredit' => $request->jumlah_pinjaman,
        ]);

        return redirect()->route('pinjaman.index')->with('success', 'Pinjaman berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pinjaman = Pinjaman::findOrFail($id);
        $anggota = User::where('role', 'anggota')->get();
        return view('pinjaman.edit', compact('pinjaman', 'anggota'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'anggota_id' => 'required|exists:users,id',
            'jenis_pinjaman' => 'required',
            'jumlah_pinjaman' => 'required|numeric|min:1',
            'bunga' => 'required|numeric|min:0',
            'jangka_waktu' => 'required|string',
            'tanggal_pinjaman' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_pinjaman',
            'status' => 'required|in:belum lunas,lunas',
            'angsuran_pokok' => 'required|numeric|min:0',
            'sisa_pinjaman' => 'required|numeric|min:0',
        ]);

        $pinjaman = Pinjaman::findOrFail($id);

        $pinjaman->update([
            'anggota_id' => $request->anggota_id,
            'jenis_pinjaman' => $request->jenis_pinjaman,
            'jumlah_pinjaman' => $request->jumlah_pinjaman,
            'bunga' => $request->bunga,
            'jangka_waktu' => $request->jangka_waktu,
            'tanggal_pinjaman' => $request->tanggal_pinjaman,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            'status' => $request->status,
            'angsuran_pokok' => $request->angsuran_pokok,
            'sisa_pinjaman' => $request->sisa_pinjaman,
        ]);

        // Hapus jurnal lama
        JurnalKasKeluar::where('no_bukti', $pinjaman->id_pinjaman)->delete();


        if($pinjaman->jenis_pinjaman == 'Pinjaman Modal Usaha'){
            $pinjaman->id_pinjaman = 'PIMUSA-' . str_pad($pinjaman->id, 7, '0', STR_PAD_LEFT);
        }
        else if($pinjaman->jenis_pinjaman == 'Pinjaman Pembiayaan Multi Guna'){
            $pinjaman->id_pinjaman = 'PEMUGA-' . str_pad($pinjaman->id, 7, '0', STR_PAD_LEFT);
        } else if($pinjaman->jenis_pinjaman == 'Pinjaman Pembiayaan Umroh'){
             $pinjaman->id_pinjaman = 'PEMURO-' . str_pad($pinjaman->id, 7, '0', STR_PAD_LEFT);
        }
        $pinjaman->save();



        // Tambah ulang jurnal baru
        JurnalKasKeluar::create([
            'anggota_id' => $request->anggota_id,
            'tanggal' => $request->tanggal_pinjaman,
            'no_bukti' => $pinjaman->id_pinjaman,
            'uraian' => 'Update pencairan pinjaman',
            'akun_debit' => 'Piutang Anggota',
            'akun_kredit' => 'Kas',
            'nominal_debit' => 0,
            'nominal_kredit' => $request->jumlah_pinjaman,
        ]);

        return redirect()->route('pinjaman.index')->with('success', 'Pinjaman berhasil diupdate.');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $pinjaman = Pinjaman::findOrFail($id);

            // Hapus jurnal kas terkait
            JurnalKasKeluar::where('no_bukti', $pinjaman->id_pinjaman)->delete();

            $pinjaman->delete();
        });

        return redirect()->route('pinjaman.index')->with('success', 'Pinjaman berhasil dihapus.');
    }
}
