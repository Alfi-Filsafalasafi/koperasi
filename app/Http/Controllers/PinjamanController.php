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
        $validatedData = $request->validate([
            'anggota_id' => 'required|exists:users,id',
            'jenis_pinjaman' => 'required',
            'tanggal_pinjaman' => 'required|date',
            'jangka_waktu' => 'required|string',
            'jumlah_pinjaman' => 'required|numeric|min:1',
            'bunga' => 'required|numeric|min:0',
            'nisbah' => 'required|numeric|min:0',
            'angsuran_pokok' => 'required|numeric|min:0',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_pinjaman',
            'total_pinjaman' => 'required|numeric|min:0',
        ]);
        $validatedData['status'] = 'belum lunas'; // Default status
        $validatedData['sisa_pinjaman'] = $validatedData['total_pinjaman']; // Set initial sisa_pinjaman
        $pinjaman = Pinjaman::create($validatedData); ;

        if($pinjaman->jenis_pinjaman == 'Pinjaman Modal Usaha'){
            $pinjaman->id_pinjaman = 'PIMUSA-' . str_pad($pinjaman->id, 7, '0', STR_PAD_LEFT);
            $akun_debit = '301.01 - Piutang Modal Usaha';
        }
        else if($pinjaman->jenis_pinjaman == 'Pinjaman Pembiayaan Multi Guna'){
            $pinjaman->id_pinjaman = 'PEMUGA-' . str_pad($pinjaman->id, 7, '0', STR_PAD_LEFT);
            $akun_debit = '301.02 - Piutang Pembiayaan Multi Guna';
        } else if($pinjaman->jenis_pinjaman == 'Pinjaman Pembiayaan Umroh'){
            $pinjaman->id_pinjaman = 'PEMURO-' . str_pad($pinjaman->id, 7, '0', STR_PAD_LEFT);
            $akun_debit = '301.03 - Piutang Pembiayaan Umroh';
        }

        $pinjaman->save();

        JurnalKasKeluar::create([
            'anggota_id' => $request->anggota_id,
            'tanggal' => $request->tanggal_pinjaman,
            'no_bukti' => $pinjaman->id_pinjaman,
            'uraian' => 'Pencairan pinjaman ',
            'akun_debit' => $akun_debit,
            'akun_kredit' => '101-Kas',
            'nominal_debit' => 0,
            'nominal_kredit' => $request->total_pinjaman,
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
        $validatedData = $request->validate([
            'anggota_id' => 'required|exists:users,id',
            'jenis_pinjaman' => 'required',
            'tanggal_pinjaman' => 'required|date',
            'jangka_waktu' => 'required|string',
            'jumlah_pinjaman' => 'required|numeric|min:1',
            'bunga' => 'required|numeric|min:0',
            'nisbah' => 'required|numeric|min:0',
            'angsuran_pokok' => 'required|numeric|min:0',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_pinjaman',
            'total_pinjaman' => 'required|numeric|min:0',
            'status' => 'required|in:belum lunas,lunas',
            'sisa_pinjaman' => 'required|numeric|min:0',
        ]);

        $pinjaman = Pinjaman::findOrFail($id);

        $pinjaman->update($validatedData);

        // Hapus jurnal lama
        JurnalKasKeluar::where('no_bukti', $pinjaman->id_pinjaman)->delete();


        if($pinjaman->jenis_pinjaman == 'Pinjaman Modal Usaha'){
            $pinjaman->id_pinjaman = 'PIMUSA-' . str_pad($pinjaman->id, 7, '0', STR_PAD_LEFT);
            $akun_debit = '301.01 - Piutang Modal Usaha';
        }
        else if($pinjaman->jenis_pinjaman == 'Pinjaman Pembiayaan Multi Guna'){
            $pinjaman->id_pinjaman = 'PEMUGA-' . str_pad($pinjaman->id, 7, '0', STR_PAD_LEFT);
            $akun_debit = '301.02 - Piutang Pembiayaan Multi Guna';
        } else if($pinjaman->jenis_pinjaman == 'Pinjaman Pembiayaan Umroh'){
             $pinjaman->id_pinjaman = 'PEMURO-' . str_pad($pinjaman->id, 7, '0', STR_PAD_LEFT);
            $akun_debit = '301.03 - Piutang Pembiayaan Umroh';
        }
        $pinjaman->save();



        // Tambah ulang jurnal baru
        JurnalKasKeluar::create([
            'anggota_id' => $request->anggota_id,
            'tanggal' => $request->tanggal_pinjaman,
            'no_bukti' => $pinjaman->id_pinjaman,
            'uraian' => 'Update pencairan pinjaman',
            'akun_debit' => $akun_debit,
            'akun_kredit' => '101-Kas',
            'nominal_debit' => 0,
            'nominal_kredit' => $request->total_pinjaman,
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
