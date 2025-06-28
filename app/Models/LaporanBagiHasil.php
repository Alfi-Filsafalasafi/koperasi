<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanBagiHasil extends Model
{
    protected $table = 'laporan_bagi_hasils';
    protected $fillable = [
        'kode', 'nisbah', 'tahun_awal', 'tahun_akhir', 'periode',
        'besaran_nisbah', 'total_pendapatan', 'pendapatan_dibagi',
        'pendapatan_ditahan', 'jumlah_hari', 'is_cetak'
    ];

    public function detail()
    {
        return $this->hasMany(BagiHasilDetail::class, 'laporan_id');
    }
}