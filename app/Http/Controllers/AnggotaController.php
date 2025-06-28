<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AnggotaController extends Controller
{
    public function index()
    {
        $datas = User::where('role', 'anggota')->latest()->get();
        return view('anggota.index', compact('datas'));
    }

    public function create()
    {
        return view('anggota.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'no_ktp' => 'required|string|max:20|unique:users,no_ktp',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'pekerjaan' => 'required|string',
            'tanggal_masuk' => 'required|date',
            'no_telp' => 'required|string|max:15',
        ]);

        // Simpan dulu user tanpa id_anggota
        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'no_ktp' => $request->no_ktp,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'pekerjaan' => $request->pekerjaan,
            'tanggal_masuk' => $request->tanggal_masuk,
            'no_telp' => $request->no_telp,
            'email' => '-----',
            'password' => Hash::make('------'),
            'role' => 'anggota',
            'is_anggota' => true,
            'status_aktif' => 'aktif',
        ]);

        // Update id_anggota berdasarkan ID-nya
        $user->id_anggota = 'ANG-' . str_pad($user->id, 7, '0', STR_PAD_LEFT);
        $user->save();

        return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil ditambahkan.');
    }


    public function show(User $anggota)
    {
        return view('anggota.show', compact('anggota'));
    }

    public function edit($anggota)
    {
        $anggota = User::findOrFail($anggota);
        return view('anggota.edit', compact('anggota'));
    }

    public function update(Request $request, $anggota)
    {
        $anggota = User::findOrFail($anggota);
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'no_ktp' => 'required|string|max:20|unique:users,no_ktp,' . $anggota->id,
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'pekerjaan' => 'required|string',
            'tanggal_masuk' => 'required|date',
            'no_telp' => 'required|string|max:15',
        ]);

        $anggota->update([
            'nama_lengkap' => $request->nama_lengkap,
            'no_ktp' => $request->no_ktp,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'pekerjaan' => $request->pekerjaan,
            'tanggal_masuk' => $request->tanggal_masuk,
            'no_telp' => $request->no_telp,
            'email' => '-----',
            'password' => Hash::make('------'),
        ]);

        return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil diupdate.');
    }

    public function destroy( $anggota)
    {
        $anggota = User::findOrFail($anggota);
        // Hapus data anggota beserta relasinya
        $anggota->delete();
        return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil dihapus.');
    }
}
