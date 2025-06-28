<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BagiHasilDetail extends Model
{
    protected $table = 'bagi_hasil_details';
    protected $fillable = ['laporan_id', 'anggota_id', 'persentase', 'jumlah_dibagi'];

    public function laporan()
    {
        return $this->belongsTo(LaporanBagiHasil::class, 'laporan_id');
    }

    public function anggota()
    {
        return $this->belongsTo(User::class, 'anggota_id');
    }
}
