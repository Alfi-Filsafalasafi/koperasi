<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pinjaman;
use App\Models\TransaksiPinjaman;
use PDF;
use Carbon\Carbon;
use DB;

class LaporanNisbahController extends Controller
{
    public function indexTahunan(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        // Ambil tahun unik dari data transaksi
        $list_tahun = TransaksiPinjaman::selectRaw('YEAR(tanggal_bayar) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        // Ambil data transaksi nisbah per jenis pinjaman dan hitung total per tahun
        $laporan = Pinjaman::select('jenis_pinjaman', DB::raw('SUM(transaksi_pinjamans.pembayaran_nisbah) as total_nisbah'))
            ->join('transaksi_pinjamans', 'pinjamans.id', '=', 'transaksi_pinjamans.pinjaman_id')
            ->whereYear('transaksi_pinjamans.tanggal_bayar', $tahun)
            ->groupBy('jenis_pinjaman')
            ->get()
            ->map(function ($item) use ($tahun) {
                $prefix = match ($item->jenis_pinjaman) {
                    'Pinjaman Modal Usaha' => 'NIS-PEMUSA',
                    'Pinjaman Pembiayaan Multi Guna' => 'NIS-PEMUGA',
                    'Pinjaman Pembiayaan Umroh' => 'NIS-PEMURO',
                    default => 'NIS-OTHER'
                };

                return [
                    'kode_nisbah' => $prefix,
                    'periode' => $tahun,
                    'besaran_nisbah' => $item->total_nisbah,
                    'total_pendapatan' => $item->total_nisbah,
                    'pendapatan_dibagi' => $item->total_nisbah * 0.7,
                    'pendapatan_ditahan' => $item->total_nisbah * 0.3,
                    'jumlah_hari' => 365
                ];
            });

        return view('laporan.nisbah_tahunan', compact('laporan', 'tahun', 'list_tahun'));
    }

    public function exportPdfTahunan(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));

        $laporan = Pinjaman::select('jenis_pinjaman', DB::raw('SUM(transaksi_pinjamans.pembayaran_nisbah) as total_nisbah'))
            ->join('transaksi_pinjamans', 'pinjamans.id', '=', 'transaksi_pinjamans.pinjaman_id')
            ->whereYear('transaksi_pinjamans.tanggal_bayar', $tahun)
            ->groupBy('jenis_pinjaman')
            ->get()
            ->map(function ($item) use ($tahun) {
                $prefix = match ($item->jenis_pinjaman) {
                    'Pinjaman Modal Usaha' => 'NIS-PEMUSA',
                    'Pinjaman Pembiayaan Multi Guna' => 'NIS-PEMUGA',
                    'Pinjaman Pembiayaan Umroh' => 'NIS-PEMURO',
                    default => 'NIS-OTHER'
                };

                return [
                    'kode_nisbah' => $prefix,
                    'periode' => $tahun,
                    'besaran_nisbah' => $item->total_nisbah,
                    'total_pendapatan' => $item->total_nisbah,
                    'pendapatan_dibagi' => $item->total_nisbah * 0.7,
                    'pendapatan_ditahan' => $item->total_nisbah * 0.3,
                    'jumlah_hari' => 365
                ];
            });

        $pdf = PDF::loadView('laporan.nisbah_tahunan_pdf', compact('laporan', 'tahun'))
            ->setPaper('A4', 'landscape');

        return $pdf->download("laporan_nisbah_tahun_$tahun.pdf");
    }

    public function indexBulanan(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $bulan = $request->input('bulan', date('m'));

        $list_tahun = TransaksiPinjaman::selectRaw('YEAR(tanggal_bayar) as tahun')
            ->distinct()->orderBy('tahun', 'desc')->pluck('tahun')->toArray();

        $laporan = Pinjaman::select('jenis_pinjaman', DB::raw('SUM(transaksi_pinjamans.pembayaran_nisbah) as total_nisbah'))
            ->join('transaksi_pinjamans', 'pinjamans.id', '=', 'transaksi_pinjamans.pinjaman_id')
            ->whereYear('transaksi_pinjamans.tanggal_bayar', $tahun)
            ->whereMonth('transaksi_pinjamans.tanggal_bayar', $bulan)
            ->groupBy('jenis_pinjaman')
            ->get()
            ->map(function ($item) use ($tahun, $bulan) {
                $prefix = match ($item->jenis_pinjaman) {
                    'Pinjaman Modal Usaha' => 'NIS-PEMUSA',
                    'Pinjaman Pembiayaan Multi Guna' => 'NIS-PEMUGA',
                    'Pinjaman Pembiayaan Umroh' => 'NIS-PEMURO',
                    default => 'NIS-OTHER'
                };

                return [
                    'kode_nisbah' => $prefix,
                    'periode' => Carbon::create()->locale('id')->month((int)$bulan)->translatedFormat('F') . " $tahun",
                    'besaran_nisbah' => $item->total_nisbah,
                    'total_pendapatan' => $item->total_nisbah,
                    'pendapatan_dibagi' => $item->total_nisbah * 0.7,
                    'pendapatan_ditahan' => $item->total_nisbah * 0.3,
                    'jumlah_hari' => cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun)
                ];
            });

        return view('laporan.nisbah_bulanan', compact('laporan', 'tahun', 'bulan', 'list_tahun'));
    }

    public function exportPdfBulanan(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $bulan = $request->input('bulan', date('m'));

        $laporan = Pinjaman::select('jenis_pinjaman', DB::raw('SUM(transaksi_pinjamans.pembayaran_nisbah) as total_nisbah'))
            ->join('transaksi_pinjamans', 'pinjamans.id', '=', 'transaksi_pinjamans.pinjaman_id')
            ->whereYear('transaksi_pinjamans.tanggal_bayar', $tahun)
            ->whereMonth('transaksi_pinjamans.tanggal_bayar', $bulan)
            ->groupBy('jenis_pinjaman')
            ->get()
            ->map(function ($item) use ($tahun, $bulan) {
                $prefix = match ($item->jenis_pinjaman) {
                    'Pinjaman Modal Usaha' => 'NIS-PEMUSA',
                    'Pinjaman Pembiayaan Multi Guna' => 'NIS-PEMUGA',
                    'Pinjaman Pembiayaan Umroh' => 'NIS-PEMURO',
                    default => 'NIS-OTHER'
                };

                return [
                    'kode_nisbah' => $prefix,
                    'periode' => Carbon::create()->locale('id')->month((int)$bulan)->translatedFormat('F') . " $tahun",
                    'besaran_nisbah' => $item->total_nisbah,
                    'total_pendapatan' => $item->total_nisbah,
                    'pendapatan_dibagi' => $item->total_nisbah * 0.7,
                    'pendapatan_ditahan' => $item->total_nisbah * 0.3,
                    'jumlah_hari' => cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun)
                ];
            });

        $pdf = PDF::loadView('laporan.nisbah_bulanan_pdf', compact('laporan', 'tahun', 'bulan'))
            ->setPaper('A4', 'landscape');

            $periode = Carbon::create()->locale('id')->month((int)$bulan)->translatedFormat('F') . " $tahun";

        return $pdf->download("laporan_nisbah_bulan_{$periode}.pdf");
    }
}