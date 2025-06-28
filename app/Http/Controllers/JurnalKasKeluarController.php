<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JurnalKasKeluar;
use Barryvdh\DomPDF\Facade\Pdf;

class JurnalKasKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tahunList = JurnalKasKeluar::selectRaw('YEAR(tanggal) as tahun')
                        ->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        $tahun = $request->get('tahun', date('Y'));

        $datas = JurnalKasKeluar::with('anggota')
            ->whereYear('tanggal', $tahun)
            ->latest()
            ->get();

        return view('jurnal_kas_keluar.index', compact('datas', 'tahunList', 'tahun'));
    }


    public function cetakPdf(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        $datas = JurnalKasKeluar::with('anggota')
            ->whereYear('tanggal', $tahun)
            ->get()
            ->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->tanggal)->translatedFormat('F');
            });

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('jurnal_kas_keluar.pdf', [
            'datas' => $datas,
            'tahun' => $tahun,
        ])->setPaper('A4', 'potrait');

        return $pdf->download("jurnal_kas_keluar_{$tahun}.pdf");
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}