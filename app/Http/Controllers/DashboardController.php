<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Simpanan;
use App\Models\Pinjaman;

class DashboardController extends Controller
{
    //
    public function index() {
        $item = [
            'nasabah' => User::where('role', 'anggota')->count(),
            'simpanan' => Simpanan::sum('total_saldo'),
            'pinjaman' => Pinjaman::sum('sisa_pinjaman')
        ];

        return view('dashboard', compact('item'));
    }
}
