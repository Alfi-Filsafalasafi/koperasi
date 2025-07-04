<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurnalKasMasuk extends Model
{
    protected $table = 'jurnal_kas_masuks';
    protected $fillable = ['anggota_id', 'tanggal', 'no_bukti', 'uraian', 'akun_debit', 'akun_kredit', 'nominal_debit', 'nominal_kredit',
                            'pembayaran_pokok', 'pembayaran_bunga', 'pembayaran_denda'];

    public function anggota()
    {
        return $this->belongsTo(User::class, 'anggota_id');
    }
}
