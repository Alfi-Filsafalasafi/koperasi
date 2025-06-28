<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurnalKasKeluar extends Model
{
    protected $table = 'jurnal_kas_keluars';
    protected $fillable = ['anggota_id', 'tanggal', 'no_bukti', 'uraian', 'akun_debit', 'akun_kredit', 'nominal_debit', 'nominal_kredit'];

    public function anggota()
    {
        return $this->belongsTo(User::class, 'anggota_id');
    }
}
